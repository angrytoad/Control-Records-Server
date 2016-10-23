<?php
namespace App\Http\Controllers\MusicManager;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Gig;
use App\Album;
use App\Song;
use App\News;
use App\Band;
use App\Venue;
use Carbon\Carbon;

class MusicManagerController extends Controller
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
        $albumCount = Album::all()->count();
        $songCount = Song::all()->count();
        $lastSongUploads = Song::orderBy('created_at','DESC')->limit(10)->get();
        $lastAlbumUploads = Album::orderBy('created_at','DESC')->limit(10)->get();
        
        return view('music_manager/index', array(
            'albumCount' => $albumCount,
            'songCount' => $songCount,
            'lastSongUploads' => $lastSongUploads,
            'lastAlbumUploads' => $lastAlbumUploads
        ));
    }
}