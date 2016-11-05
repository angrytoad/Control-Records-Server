<?php
namespace App\Http\Controllers\MusicManager;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Album;
use App\Song;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;
use AWS;
use Faker\Provider\Uuid;
use Mockery\CountValidator\Exception;

class AlbumManagerController extends Controller
{
    private $allowed_extensions = [
        'jpg',
        'jpeg',
        'png',
        'gif'
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = Album::orderBy('created_at', 'DESC')->get();
        $albumCount = Album::all()->count();
        $lastWeek = Album::where('created_at','>=',Carbon::now()->subWeek(1))->count();

        return view('music_manager/albums/index', array(
            'albums' => $albums,
            'albumCount' => $albumCount,
            'lastWeek' => $lastWeek
        ));
    }

    public function create()
    {
        return view('music_manager/albums/create', array(

        ));
    }

    public function albumIndex($uuid)
    {
        $album = Album::where('id',$uuid)->with('songs')->first();
        $amountPublic = 0;
        foreach($album->songs as $song){
            if($song->public){
                $amountPublic += 1;
            }
        }
        return view('music_manager/albums/album/index', array(
            'album' => $album,
            'songs_public' => $amountPublic
        ));
    }

    public function albumDelete($uuid){
        $album = Album::find($uuid);
        $album->songs()->detach();
        $s3 = AWS::createClient('s3');

        $album_image = $s3->deleteObject([
            'Bucket' => env('S3_BUCKET_GENERAL_STORAGE'), // REQUIRED
            'Key' => 'images/albums/'.$album->album_image
        ]);

        $album->delete();
        return redirect('/music/albums');
    }

    public function albumSongDelete($uuid, $song_uuid){
        $song = Song::find($song_uuid);
        $song->albums()->detach($uuid);
        $song->save();
        return redirect('/music/album/'.$uuid);
    }

    public function albumUnlinkAll($uuid){
        $album = Album::find($uuid);
        $album->songs()->detach();
        $album->public = false;
        $album->save();
        return redirect('/music/album/'.$uuid);
    }

    public function albumMakePrivate($uuid){
        $album = Album::find($uuid);
        $album->public = false;
        $album->save();
        return redirect('/music/album/'.$uuid);
    }

    public function albumMakePublic($uuid){
        $album = Album::find($uuid);
        $album->public = true;
        $album->save();
        return redirect('/music/album/'.$uuid);
    }

    public function storeCreate(Request $request)
    {
        /**
         * Grab file, true extension and given extension
         */
        $image = $request->file('album-image');
        $extension = $image->extension();
        $org_extension = $image->getClientOriginalExtension();

        try {
            /**
             * Check to see if the extension is in the allowed list of image extensions otherwise just exit out
             * and throw an error.
             */
            if(in_array($extension,$this->allowed_extensions)){
                /**
                 * Generate a UUID and find the extra band info if available.
                 */
                $uuid = Uuid::uuid();

                /**
                 * Move the temp file into another temp directory that we can read from and re-name it in.
                 */
                $image->move('tmp/images/uploads', $uuid . '.' . $org_extension);

                /**
                 * Create the initial AWS S3 Client
                 */
                $s3 = AWS::createClient('s3');

                /**
                 * Run a putObject command to upload the image into the correct directory.
                 */
                $result = $s3->putObject([
                    'ACL' => "public-read",
                    'SourceFile' => 'tmp/images/uploads/' . $uuid . '.' . $org_extension,
                    'Bucket' => env('S3_BUCKET_GENERAL_STORAGE'), // REQUIRED
                    'Key' => 'images/albums/'.$uuid, // REQUIRED
                ]);

                /**
                 * Check the result and if we get a 200, its all gravy. If not then we need to throw an exception.
                 * to let the user know there was an issue with uploading their file.
                 */
                if($result['@metadata']['statusCode'] == 200){

                    Album::create([
                        'id' => Uuid::uuid(),
                        'album_name' => $request['album-name'],
                        'album_image' => $uuid,
                        'album_image_url' => $result['ObjectURL'],
                        'public' => false
                    ]);

                }else{
                    throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                }

            }else{
                throw new Exception('You are uploading a file which is not allowed, please use .jpg, .jpeg, .png or .gif');
            }
        }catch(\Exception $e){
            return redirect('/music/albums/create')->with('upload_error', $e->getMessage());
        }

        File::cleanDirectory('tmp/images/uploads');
        return redirect('/music/albums');
    }
}