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
use DB;
use App\Band;

class BandController extends Controller
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
        $bands = Band::orderBy('name','ASC')->get();

        return view('band/bands', array(
            'bands' => $bands
        ));
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'primary_name' => 'required|max:255',
            'primary_email' => 'required|max:255',
            'primary_telephone' => 'required|max:32'
        ]);

        $band = new Band;
        $band->name = $request->input('name');
        $band->primary_name = $request->input('primary_name');
        $band->primary_email = $request->input('primary_email');
        $band->primary_telephone = $request->input('primary_telephone');
        $band->url_safe_name = str_slug($request->input('name'), '-');
        $band->save();

        return redirect('/bands');
    }

    public function bandPage($bandId){
        $band = Band::find($bandId);
        return view('band/band', array(
            'band' => $band
        ));
    }

    public function bandDelete($bandId){
        $band = Band::find($bandId);
        $band->delete();

        return redirect('/bands');
    }

    public function bandEdit(Request $request, $bandId){
        $band = Band::find($bandId);
        $band->name = $request->input('name');
        $band->primary_name = $request->input('primary_name');
        $band->primary_email = $request->input('primary_email');
        $band->primary_telephone = $request->input('primary_telephone');
        $band->url_safe_name = str_slug($request->input('name'), '-');
        $band->save();

        return redirect('/bands/'.$bandId);
    }
}