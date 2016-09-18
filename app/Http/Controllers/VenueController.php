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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use DB;
use App\Venue;
use App\Gig;
use AWS;
use Faker\Provider\Uuid;

class VenueController extends Controller
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
        $venues = DB::table('venues')->orderBy('venue_name','ASC')->get();

        return view('venue/venues', array(
            'venues' => $venues
        ));
    }

    public function store(Request $request){
        $this->validate($request, [
            'venue-name' => 'required|max:255',
            'contact-name' => 'required|max:255',
            'contact-email' => 'required|max:255',
            'contact-telephone' => 'required|max:32',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        
        $venue = new Venue;
        $venue->venue_name = $request->input('venue-name');
        $venue->contact_name = $request->input('contact-name');
        $venue->contact_email = $request->input('contact-email');
        $venue->contact_telephone = $request->input('contact-telephone');
        $coordinates = json_encode([
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude')
        ]);
        $venue->coordinates = $coordinates;
        $venue->url_safe_name = str_slug($request->input('venue-name'), '-');
        $venue->save();

        return redirect('/venues');
    }

    public function venuePage($venueId){
        $venue = Venue::find($venueId);
        return view('venue/venue', array(
            'venue' => $venue
        ));
    }

    public function venueDelete($venueId){
        $venue = Venue::find($venueId);
        $venue->delete();

        $gigs = Gig::where('venue_id',$venueId)->get();
        foreach($gigs as $gig){
            $gig->delete();
        }

        $s3 = AWS::createClient('s3');
        if(isset($venue->venue_logo_key)) {
            $logo = $s3->deleteObject([
                'Bucket' => env('S3_BUCKET_GENERAL_STORAGE'), // REQUIRED
                'Key' => 'images/venues/logos/'.$venue->venue_logo_key
            ]);
        }

        return redirect('/venues');
    }

    public function venueEdit(Request $request, $venueId){
        $venue = Venue::find($venueId);
        $venue->venue_name = $request->input('venue-name');
        $venue->contact_name = $request->input('contact-name');
        $venue->contact_email = $request->input('contact-email');
        $venue->contact_telephone = $request->input('contact-telephone');
        $venue->show_on_homepage = $request->input('show_on_homepage');
        $coordinates = json_encode([
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude')
        ]);
        $venue->coordinates = $coordinates;
        $venue->url_safe_name = str_slug($request->input('venue-name'), '-');
        $venue->save();

        return redirect('/venues/'.$venueId);
    }

    public function storeLogo(Request $request, $venueId){
        /**
         * Uploads and Stores the logo in S3 Storage, saves it to the Database so that we can retrieve it at a
         * later point on the backend.
         */
        $venue = Venue::find($venueId);
        if($request->hasFile('logo') && $request->file('logo')->isValid()){
            /**
             * Grab file, true extension and given extension
             */
            $logo = $request->file('logo');
            $extension = $logo->extension();
            $org_extension = $logo->getClientOriginalExtension();

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
                    if(strlen($venue->venue_logo_key) > 0) {
                        /**
                         * If a UUID has already been set then we'll set the uuid to that to make sure we replace the
                         * existing file on S3
                         */
                        $uuid = $venue->venue_logo_key;
                    }

                    /**
                     * Move the temp file into another temp directory that we can read from and re-name it in.
                     */
                    $logo->move('tmp/images/uploads', $uuid . '.' . $org_extension);

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
                        'Key' => 'images/venues/logos/'.$uuid, // REQUIRED
                    ]);

                    /**
                     * Check the result and if we get a 200, its all gravy. If not then we need to throw an exception.
                     * to let the user know there was an issue with uploading their file.
                     */
                    if($result['@metadata']['statusCode'] == 200){

                        $venue->venue_logo_url = $result['ObjectURL'];
                        $venue->venue_logo_key = $uuid;
                        $venue->save();

                    }else{
                        throw new Exception('There was an issue uploading this file to AWS S3. Let me know');
                    }

                }else{
                    throw new Exception('You are uploading a file which is not allowed, please use .jpg, .jpeg, .png or .gif');
                }
            }catch(\Exception $e){
                return redirect('/venues/'.$venue->id)->with('upload_error', $e->getMessage());
            }
        }

        File::cleanDirectory('tmp/images/uploads');
        return redirect('/venues/'.$venue->id);
    }
}