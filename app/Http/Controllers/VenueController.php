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
use DB;
use App\Venue;
use App\Gig;

class VenueController extends Controller
{
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

        return redirect('/venues');
    }

    public function venueEdit(Request $request, $venueId){
        $venue = Venue::find($venueId);
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

        return redirect('/venues/'.$venueId);
    }
}