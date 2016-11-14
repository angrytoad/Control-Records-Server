<?php

namespace App\Http\Controllers\Api\Store;

use App\Album;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Gig;
use App\News;
use App\Band;
use App\Song;
use App\Venue;
use Carbon\Carbon;


class StoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeSearch($search_string) {
        if(strlen($search_string) >= 3){
            $response = [];

            /*
             * Search for all artists and add them into the response
             */
            $artists = Band::search($search_string)->get();
            $artists_list = [];
            foreach($artists as $artist){
                $list_item = [
                    'name' => $artist->name,
                    'url_safe_name' => $artist->url_safe_name,
                    'band_avatar_url' => $artist->band_additional->band_avatar_url
                ];
                $artists_list[] = $list_item;
            }
            $response['artists'] = $artists_list;

            /*
             * Search for all albums and add them into the response
             */
            $albums = Album::search($search_string)->where('public',1)->get();
            $albums_list = [];
            foreach($albums as $album){
                $list_item = [
                    'name' => $album->album_name,
                    'album_image_url' => $album->album_image_url
                ];
                $albums_list[] = $list_item;
            }
            $response['albums'] = $albums_list;

            /*
             * Search for all songs and add them into the response
             */
            $songs = Song::search($search_string)->where('public',1)->get();
            $songs_list = [];
            foreach($songs as $song){
                $list_item = [
                    'name' => $song->song_name
                ];
                $songs_list[] = $list_item;
            }
            $response['songs'] = $songs_list;

            return response()->json($response, 200);
        }else{
            return response()->json([
                'err' => [
                    'msg' => 'Search string was less than 3 characters'
                ]
            ], 400);
        }


    }

}










