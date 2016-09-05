<?php
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 31/07/2016
 * Time: 11:22
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use App\Band_Additional;
use App\Band;
use AWS;
use Faker\Provider\Uuid;
use Mockery\CountValidator\Exception;

class BandAdditionalController extends Controller
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
        $bands = Band::orderBy('name','ASC')->get();

        return view('band/bands', array(
            'bands' => $bands
        ));
    }

    public function bandAdditionalPage($bandId){
        $bandAdditional = Band_Additional::where('band_id',$bandId)->first();
        $band = Band::find($bandId);
        return view('band/bandAdditional', array(
            'band' => $band,
            'bandAdditional' => $bandAdditional
        ));
    }

    public function storeBanner(Request $request, $bandId){
        /**
         * Uploads and Stores the avatar image in S3 Storage, saves it to the Database so that we can retrieve it at a
         * later point on the backend.
         */
        $band = Band::find($bandId);
        if($request->hasFile('banner') && $request->file('banner')->isValid()){
            /**
             * Grab file, true extension and given extension
             */
            $banner = $request->file('banner');
            $extension = $banner->extension();
            $org_extension = $banner->getClientOriginalExtension();
            
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
                    $band_additional = Band_Additional::firstOrCreate(['band_id' => $band->id]);
                    if(strlen($band_additional->band_banner_key) > 0) {
                        /**
                         * If a UUID has already been set then we'll set the uuid to that to make sure we replace the
                         * existing file on S3
                         */
                        $uuid = $band_additional->band_banner_key;
                    }

                    /**
                     * Move the temp file into another temp directory that we can read from and re-name it in.
                     */
                    $banner->move('tmp/images/uploads', $uuid . '.' . $org_extension);

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
                        'Key' => 'images/bands/banners/'.$uuid, // REQUIRED
                    ]);

                    /**
                     * Check the result and if we get a 200, its all gravy. If not then we need to throw an exception.
                     * to let the user know there was an issue with uploading their file.
                     */
                    if($result['@metadata']['statusCode'] == 200){

                        $band_additional->band_banner_url = $result['ObjectURL'];
                        $band_additional->band_banner_key = $uuid;
                        $band_additional->save();

                    }else{
                        throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                    }

                }else{
                    throw new Exception('You are uploading a file which is not allowed, please use .jpg, .jpeg, .png or .gif');
                }
            }catch(\Exception $e){
                return redirect('/bands/'.$band->id.'/additional')->with('upload_error', $e->getMessage());
            }
        }

        File::cleanDirectory('tmp/images/uploads');
        return redirect('/bands/'.$band->id.'/additional');
    }



    public function storeAvatar(Request $request, $bandId){
        /**
         * Uploads and Stores the avatar image in S3 Storage, saves it to the Database so that we can retrieve it at a
         * later point on the backend.
         */
        $band = Band::find($bandId);

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()){
            /**
             * Grab file, true extension and given extension
             */
            $banner = $request->file('avatar');
            $extension = $banner->extension();
            $org_extension = $banner->getClientOriginalExtension();
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
                    $band_additional = Band_Additional::firstOrCreate(['band_id' => $band->id]);
                    if(strlen($band_additional->band_avatar_key) > 0) {
                        /**
                         * If a UUID has already been set then we'll set the uuid to that to make sure we replace the
                         * existing file on S3
                         */
                        $uuid = $band_additional->band_avatar_key;
                    }

                    /**
                     * Move the temp file into another temp directory that we can read from and re-name it in.
                     */
                    $banner->move('tmp/images/uploads', $uuid . '.' . $org_extension);

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
                        'Key' => 'images/bands/avatars/'.$uuid, // REQUIRED
                    ]);

                    /**
                     * Check the result and if we get a 200, its all gravy. If not then we need to throw an exception.
                     * to let the user know there was an issue with uploading their file.
                     */
                    if($result['@metadata']['statusCode'] == 200){

                        $band_additional->band_avatar_url = $result['ObjectURL'];
                        $band_additional->band_avatar_key = $uuid;
                        $band_additional->save();

                    }else{
                        throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                    }

                }else{
                    throw new Exception('You are uploading a file which is not allowed, please use .jpg, .jpeg, .png or .gif');
                }
            }catch(\Exception $e){
                return redirect('/bands/'.$band->id.'/additional')->with('upload_error', $e->getMessage());
            }
        }

        File::cleanDirectory('tmp/images/uploads');
        return redirect('/bands/'.$band->id.'/additional');
    }
    
    public function storeAbout(Request $request, $bandId){
        $this->validate($request, [
            'about' => 'required',
        ]);

        $band = Band::find($bandId);
        $band_additional = Band_Additional::firstOrCreate(['band_id' => $band->id]);
        $band_additional->about = $request->get('about');
        $band_additional->save();

        return redirect('/bands/'.$band->id.'/additional');
    }
}