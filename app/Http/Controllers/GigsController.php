<?php
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 31/07/2016
 * Time: 11:22
 */

namespace App\Http\Controllers;

use App\Band;
use App\Http\Requests;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Gig;
use App\GigVenue;

class GigsController extends Controller
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
        $gigs = Gig::orderBy('date','ASC')->where('date','>=',date('Y-m-d').' 00:00:00')->get();
        $venues = Venue::orderBy('venue_name','ASC')->get();
        $bands = Band::orderBy('name','ASC')->get();


        return view('gigs', array(
            'gigs' => $gigs,
            'venues' => $venues,
            'bands' => $bands
        ));
    }

    public function store(Request $request){
        $this->validate($request, [
            'venue' => 'required|max:16',
            'band' => 'required|max:16',
            'time' => 'required|max:255'
        ]);

        $gig = new Gig;
        $gig->additional_comments = $request->input('additional-comments');
        $gig->venue_id = $request->input('venue');
        $gig->band_id = $request->input('band');
        $gig->date = Carbon::parse($request->input('time'))->toDateTimeString();
        $gig->save();

        return redirect('/gigs');
    }

    public function gigPage($gigId){
        $gig = Gig::find($gigId);
        return view('gig/gig', array(
            'gig' => $gig
        ));
    }

    public function gigDelete($gigId){
        $gig = Gig::find($gigId);
        $gig->delete();

        return redirect('/gigs');
    }
    
    public function gigEdit(Request $request, $gigId){
        $gig = Gig::find($gigId);
        $gig->additional_comments = $request->input('additional-comments');
        $gig->date = Carbon::parse($request->input('time'))->toDateTimeString();
        $gig->save();

        return redirect('/gigs/'.$gigId);
    }
}










