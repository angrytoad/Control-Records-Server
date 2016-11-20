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
use App\Store_Configuration;
use Carbon\Carbon;


class StoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {

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
                    'band_avatar_url' => ($artist->band_additional !== null ? $artist->band_additional->band_avatar_url : false)
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
                    'artist' => $album->band->name,
                    'album_image_url' => $album->album_image_url,
                    'url_safe_name' => $album->url_safe_name
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
                    'name' => $song->song_name,
                    'artist' => $song->band->name,
                    'url_safe_name' => $song->url_safe_name
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

    public function getCurrentStoreFront(){
        $store_configuration = Store_Configuration::where('configuration_active',true)->first();

        $album_songs = [];
        $album_artists = [];

        foreach($store_configuration->store_album->songs as $song){
            $album_song = [
                'song_id' => $song->id,
                'song_name' => $song->song_name,
                'song_artist' => $song->band->name,
                'song_sample_url' => $song->sample_url,
                'song_url' => $song->url_safe_name
            ];
            $album_songs[] = $album_song;
            $album_artists[$song->band->name] = $song->band->name;
        }

        $featured_artist_albums = Album::join('album_song', 'albums.id', '=', 'album_song.album_id')
            ->join('songs', 'songs.id', '=', 'album_song.song_id')
            ->select('albums.*')
            ->where('songs.band_id', '=', $store_configuration->store_artist->id)
            ->groupBy('albums.id')
            ->get();

        $album_artist_albums = [];
        foreach($featured_artist_albums as $album){
            $album_artist_album = [
                'album_id' => $album->id,
                'album_name' => $album->album_name,
                'album_image_url' => $album->album_image_url
            ];
            $album_artist_albums[] = $album_artist_album;
        }

        $response = [
            'featured_article' => [
                'article_banner' => 'https://ctrl-records.com/assets/404banner.jpg',
                'article_title' => $store_configuration->store_article->title,
                'article_body' => $store_configuration->store_article->body,
                'article_url' => $store_configuration->store_article->url_safe_name
            ],
            'featured_album' => [
                'album_artists' => $album_artists,
                'album_image' => $store_configuration->store_album->album_image_url,
                'album_name' => $store_configuration->store_album->album_name,
                'album_songs' => $album_songs,
                'album_url' => $store_configuration->store_album->url_safe_name
            ],
            'featured_artist' => [
                'artist_name' => $store_configuration->store_artist->name,
                'artist_avatar' => (isset($store_configuration->store_artist->band_additional) ? $store_configuration->store_artist->band_additional->band_avatar_url : false),
                'artist_albums' => $album_artist_albums
            ]
        ];

        return response()->json($response);
    }

}










