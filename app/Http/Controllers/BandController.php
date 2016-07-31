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

        return view('bands', array(
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
        $band->save();

        return redirect('/bands');
    }
}