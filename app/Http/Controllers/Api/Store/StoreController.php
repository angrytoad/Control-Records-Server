<?php

namespace App\Http\Controllers\Api\Store;

use App\Album;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MusicManager\AlbumManagerController;
use App\Gig;
use App\News;
use App\Band;
use App\Song;
use App\Venue;
use App\Store_Configuration;
use Carbon\Carbon;

use App\Order;
use App\Line_Item;
use App\Item_Type;


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
                $album_artists = [];
                foreach($album->songs as $song){
                    $album_artists[$song->band->name] = $song->band->name;
                }

                $list_item = [
                    'name' => $album->album_name,
                    'artists' => $album_artists,
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
            if($song->public) {
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
        }

        $featured_artist_albums = Album::join('album_song', 'albums.id', '=', 'album_song.album_id')
            ->join('songs', 'songs.id', '=', 'album_song.song_id')
            ->select('albums.*')
            ->where('songs.band_id', '=', $store_configuration->store_artist->id)
            ->groupBy('albums.id')
            ->get();

        $album_artist_albums = [];
        foreach($featured_artist_albums as $album){
            if($album->public) {
                $album_artist_album = [
                    'album_id' => $album->id,
                    'album_name' => $album->album_name,
                    'album_image_url' => $album->album_image_url
                ];
                $album_artist_albums[] = $album_artist_album;
            }
        }

        $recent_songs = Song::where('public',true)->orderBy('created_at','DESC')->limit(4)->get();
        $recent_song_uploads = [];
        foreach($recent_songs as $recent_song){
            $song_upload = [
                'id' => $recent_song->id,
                'song_name' => $recent_song->song_name,
                'sample_url' => $recent_song->sample_url,
                'artist_name' => $recent_song->band->name,
                'artist_avatar' => (isset($recent_song->band->band_additional) ? $recent_song->band->band_additional->band_avatar_url : false),
                'url_safe_name' => $recent_song->url_safe_name,
            ];
            $recent_song_uploads[] = $song_upload;
        }

        $recent_albums = Album::where('public', true)->orderBy('created_at','DESC')->limit(4)->get();
        $recent_album_uploads = [];
        foreach($recent_albums as $recent_album){
            $recent_album_artists = [];
            foreach($recent_album->songs as $song){
                $recent_album_artists[$song->band->name] = $song->band->name;
            }

            $album_upload = [
                'id' => $recent_album->id,
                'album_image_url' => $recent_album->album_image_url,
                'artist_name' => (
                                    count($recent_album_artists) > 1
                                        ?
                                        $recent_album->songs[0]->band->name.' and '.(count($recent_album_artists)-1).' other(s)'
                                        :
                                        $recent_album->songs[0]->band->name
                                ),
                'url_safe_name' => $recent_album->url_safe_name
            ];
            $recent_album_uploads[] = $album_upload;
        }

        $recently_purchased_items = $this->getRecentlyPurchased(4);

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
                'artist_albums' => $album_artist_albums,
                'url_safe_name' => $store_configuration->store_artist->url_safe_name
            ],
            'recent_song_uploads' => $recent_song_uploads,
            'recent_album_uploads' => $recent_album_uploads,
            'recently_purchased_items' => $recently_purchased_items,
        ];

        return response()->json($response);
    }

    public function getRecentlyPurchased($limit){
        /**
         * Get all recently purchased albums: Song, Album or Otherwise.
         */
        $recent_purchases = [];
        if(isset($limit)){
            /**
             * Select the last $limit line items that have been purchased, don't do by order else that adds another layer
             * of confusion to the whole process.
             */
            $purchased = Line_Item::orderBy('created_at','DESC')->limit($limit)->get();
            foreach($purchased as $purchase){
                /**
                 * Create an array of the different item types and their purchase->id's
                 */
                $recent_purchases[$purchase->item_type->type][] = $purchase->item_type->item_id;
            }

            /**
             * Loop over recently purchased line items and search for either the song or the album depending on what the
             * item type was, Other will cover items that are not normally music, one off items etc.
             */
            foreach($recent_purchases as $recent_purchase_key => $recent_purchase){
              switch($recent_purchase_key){
                  case 'song':
                      $songs = Song::whereIn('id', $recent_purchase)->get();
                      foreach($songs as $song){
                          $recent_purchase[] = [
                              'name' => $song->song_name,
                              'artist' => $song->band->name,
                              'image_url' => (isset($song->band->band_additional) ? $song->band->band_additional->band_avatar_url : false),
                              'full_url_path' => '/song/'.$song->url_safe_name,
                          ];
                      }
                      break;
                  case 'album':
                      $albums = Album::whereIn('id', $recent_purchase)->get();
                      foreach($albums as $album){
                          /**
                           * If an album has various artists, we want to display various
                           */
                          $album_artists = AlbumManagerController::getAlbumSongArtists($album);
                          $recent_purchase[] = [
                              'name' => $album->album_name,
                              'artist' => (count($album_artists) > 1 ? 'Various' : key($album_artists)),
                              'image_url' => $album->album_image_url,
                              'full_url_path' => '/album/'.$album->url_safe_name,
                          ];
                      }
                     break;
                  case 'other':
                      break;
                  default:
                      break;
              }
            }
            return $recent_purchases;
        }
    }

}










