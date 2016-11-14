<?php
namespace App\Http\Controllers\MusicManager;

use App\Band;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Album;
use App\Song;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;
use Exception;
use AWS;
use Faker\Provider\Uuid;

class SongManagerController extends Controller
{
    private $allowed_extensions = [
        'mp3',
        'wav',
        'flac',
        'ogg',
        'aac',
        'mpga',
        'm4a'
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
        $songs = Song::orderBy('song_name', 'ASC')->with('band')->get();
        $songLatest = Song::orderBy('created_at', 'DESC')->first();
        $songCount = Song::all()->count();
        $lastWeek = Song::where('created_at','>=',Carbon::now()->subWeek(1))->count();

        return view('music_manager/songs/index', array(
            'songs' => $songs,
            'songCount' => $songCount,
            'songLatest' => $songLatest,
            'lastWeek' => $lastWeek
        ));
    }

    public function create()
    {
        $bands = Band::orderBy('name', 'ASC')->get();
        $albums = Album::orderBy('album_name', 'ASC')->get();
        return view('music_manager/songs/create', array(
            'bands' => $bands,
            'albums' => $albums
        ));
    }

    public function songMakePrivate($uuid){
        $song = Song::find($uuid);
        $song->public = false;
        $song->save();
        return redirect('/music/song/'.$uuid);
    }

    public function songMakePublic($uuid){
        $song = Song::find($uuid);
        $song->public = true;
        $song->save();
        return redirect('/music/song/'.$uuid);
    }

    public function songIndex($uuid)
    {
        $song = Song::where('id', $uuid)->first();
        $band = Band::where('id', $song->band_id)->first();
        $bandSongs = Song::where('band_id', $song->band_id)->get();

        $s3 = AWS::createClient('s3');
        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => env('S3_AUDIO_STORAGE'),
            'Key'    => 'songs/paid/'.$song->song_id
        ]);
        $request = $s3->createPresignedRequest($cmd, '+20 minutes');
        $paid_link = (string) $request->getUri();

        $bands = Band::orderBy('name','ASC')->get();
        $albums = Album::orderBy('album_name','ASC')->get();

        return view('music_manager/songs/song/index', array(
            'song' => $song,
            'band' => $band,
            'bands' => $bands,
            'albums' => $albums,
            'bandSongs' => $bandSongs,
            'paid_link' => $paid_link
        ));
    }

    public function songEdit(Request $request, $uuid)
    {
        $song = Song::where('id',$uuid)->first();
        try{
            if($request->hasFile('song-sample')){
                $sample = $request->file('song-sample');
                $sample_extension = $sample->extension();
                $sample_org_extension = $sample->getClientOriginalExtension();
                if(in_array($sample_extension,$this->allowed_extensions)){
                    $sample->move('tmp/audio/uploads', $song->sample_id . '.' . $sample_org_extension);
                    $s3 = AWS::createClient('s3');
                    $sample_result = $s3->putObject([
                        'ACL' => "public-read",
                        'SourceFile' => 'tmp/audio/uploads/' . $song->sample_id . '.' . $sample_org_extension,
                        'Bucket' => env('S3_AUDIO_STORAGE'), // REQUIRED
                        'Key' => 'songs/samples/'.$song->sample_id, // REQUIRED
                    ]);
                    if($sample_result['@metadata']['statusCode'] == 200){

                    }else{
                        throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                    }
                }else{
                    throw new Exception('You are uploading a file which is not allowed, please use .mp3 .wav .flac .ogg or .aac');
                }
            }

            if($request->hasFile('song-paid')){
                $paid = $request->file('song-paid');
                $paid_extension = $paid->extension();
                $paid_org_extension = $paid->getClientOriginalExtension();
                if(in_array($paid_extension,$this->allowed_extensions)){
                    $paid->move('tmp/audio/uploads', $song->song_id . '.' . $paid_org_extension);
                    $s3 = AWS::createClient('s3');
                    $paid_result = $s3->putObject([
                        'ACL' => "private",
                        'SourceFile' => 'tmp/audio/uploads/' . $song->song_id . '.' . $paid_org_extension,
                        'Bucket' => env('S3_AUDIO_STORAGE'), // REQUIRED
                        'Key' => 'songs/paid/'.$song->song_id, // REQUIRED
                    ]);
                    if($paid_result['@metadata']['statusCode'] == 200){

                    }else{
                        throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                    }
                }else{
                    throw new Exception('You are uploading a file which is not allowed, please use .mp3 .wav .flac .ogg or .aac');
                }
            }

            $song->song_name = $request['song-name'];
            $song->band_id = $request['song-artist'];
            $song->url_safe_name = str_slug($request['song-name'], '-');
            $song->save();
            $song->albums()->sync($request['song-album']);
        }catch(Exception $e){
            return redirect('/music/song/'.$uuid)->with('upload_error', $e->getMessage());
        }

        File::cleanDirectory('tmp/audio/uploads');
        return redirect('/music/song/'.$uuid);

    }

    public function songDelete($uuid){
        $song = Song::find($uuid);
        $song->albums()->detach();
        $s3 = AWS::createClient('s3');


        $sample = $s3->deleteObject([
            'Bucket' => env('S3_AUDIO_STORAGE'), // REQUIRED
            'Key' => 'songs/samples/'.$song->sample_id
        ]);
        $paid = $s3->deleteObject([
            'Bucket' => env('S3_AUDIO_STORAGE'), // REQUIRED
            'Key' => 'songs/paid/'.$song->song_id
        ]);

        $song->delete();
        return redirect('/music/songs');
    }

    public function storeCreate(Request $request)
    {
        /**
         * Grab file, true extension and given extension
         */
        $sample = $request->file('song-sample');
        $sample_extension = $sample->extension();
        $sample_org_extension = $sample->getClientOriginalExtension();

        $paid = $request->file('song-paid');
        $paid_extension = $paid->extension();
        $paid_org_extension = $paid->getClientOriginalExtension();


        try {
            /**
             * Check to see if the extension is in the allowed list of audio extensions otherwise just exit out
             * and throw an error.
             */
            if(in_array($sample_extension,$this->allowed_extensions)){
                if(in_array($paid_extension,$this->allowed_extensions)){
                    /**
                     * Generate a UUID for the sample
                     */
                    $sample_uuid = Uuid::uuid();
                    $paid_uuid = Uuid::uuid();

                    /**
                     * Move the temp file into another temp directory that we can read from and re-name it in.
                     */
                    $sample->move('tmp/audio/uploads', $sample_uuid . '.' . $sample_org_extension);
                    $paid->move('tmp/audio/uploads', $paid_uuid . '.' . $paid_org_extension);

                    /**
                     * Create the initial AWS S3 Client
                     */
                    $s3 = AWS::createClient('s3');

                    /**
                     * Run a putObject command to upload the image into the correct directory.
                     */
                    $sample_result = $s3->putObject([
                        'ACL' => "public-read",
                        'SourceFile' => 'tmp/audio/uploads/' . $sample_uuid . '.' . $sample_org_extension,
                        'Bucket' => env('S3_AUDIO_STORAGE'), // REQUIRED
                        'Key' => 'songs/samples/'.$sample_uuid, // REQUIRED
                    ]);

                    /**
                     * Check the result and if we get a 200, its all gravy. If not then we need to throw an exception.
                     * to let the user know there was an issue with uploading their file.
                     */
                    if($sample_result['@metadata']['statusCode'] == 200){

                        $song = new Song();
                        $song->id = Uuid::uuid();
                        $song->song_name = $request['song-name'];
                        $song->url_safe_name = str_slug($request['song-name'], '-');
                        $song->band_id = $request['song-artist'];
                        $song->song_id = $paid_uuid;
                        $song->sample_id = $sample_uuid;
                        $song->sample_url = $sample_result['ObjectURL'];
                        $song->public = false;

                        $paid_result = $s3->putObject([
                            'ACL' => "private",
                            'SourceFile' => 'tmp/audio/uploads/' . $paid_uuid . '.' . $paid_org_extension,
                            'Bucket' => env('S3_AUDIO_STORAGE'), // REQUIRED
                            'Key' => 'songs/paid/'.$paid_uuid, // REQUIRED
                        ]);

                        if($paid_result['@metadata']['statusCode'] == 200){
                            $song->song_url = $paid_result['ObjectURL'];
                            $song->save();
                            $song->albums()->attach($request['song-album']);
                        }else{
                            throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                        }


                    }else{
                        throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                    }
                }else{
                    throw new Exception('You are uploading a file which is not allowed, please use .mp3 .wav .flac .ogg or .aac');
                }
            }else{
                throw new Exception('You are uploading a file which is not allowed, please use .mp3 .wav .flac .ogg or .aac');
            }
        }catch(Exception $e){
            return redirect('/music/songs/create')->with('upload_error', $e->getMessage());
        }

        File::cleanDirectory('tmp/audio/uploads');
        return redirect('/music/songs');
    }
}