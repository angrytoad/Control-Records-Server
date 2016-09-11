<?php

namespace App\Http\Controllers\Api;

/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 01/08/2016
 * Time: 20:32
 */
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Gig;
use App\News;
use App\Band;
use Carbon\Carbon;

class ApiController extends Controller
{

    /**
     * Gets all gigs and then returns them
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllGigs()
    {
        $gigs = Gig::take(30)->orderBy('date','ASC')->where('date','>=',date('Y-m-d').' 00:00:00')->get();

        $response = array();
        foreach($gigs as $gig){
            $gig = [
                'gig' => [
                    'date' => $gig->date,
                    'additional' => $gig->additional_comments,
                    'id' => $gig->id
                ],
                'venue' => [
                    'name' => $gig->venue->venue_name,
                    'id' => $gig->venue->id,
                    'coordinates' => json_decode($gig->venue->coordinates),
                    'url_name' => $gig->venue->url_safe_name
                ],
                'band' => $gig->band->name
            ];
            array_push($response, $gig);
        }

        return response()->json([
            'gigs' => $response
        ]);
    }

    public function getAllNews()
    {
        $articles = News::orderBy('created_at','DESC')->get();

        $response = array();
        foreach($articles as $article){
            $article = [
                'article' => [
                    'title' => $article->title,
                    'body' => $article->body,
                    'author' => $article->user->name,
                    'id' => $article->id,
                    'url_name' => $article->url_safe_name
                ]
            ];
            array_push($response, $article);
        }

        return response()->json([
            'articles' => $response
        ]);
    }

    public function getAllBands()
    {
        $bands = Band::orderBy('name','ASC')->get();

        $response = array();
        foreach($bands as $band){
            $band = [
                'band' => [
                    'name' => $band->name,
                    'id' => $band->id,
                    'url_name' => $band->url_safe_name
                ]
            ];
            array_push($response, $band);
        }

        return response()->json([
            'bands' => $response
        ]);
    }
    
    public function getBandPage($url_name)
    {
        $band = Band::where('url_safe_name', $url_name)->first();
        $gigs = Gig::where('band_id', $band->id)->with('venue')->where('date','>=',Carbon::now())->get();
        $response = [
            'name' => $band->name,
            'url_name' => $band->url_safe_name,
            'extra' => [
                'banner_url' => (isset($band->band_additional) ? $band->band_additional->band_banner_url : null),
                'avatar_url' => (isset($band->band_additional) ? $band->band_additional->band_avatar_url : null),
                'about' => (isset($band->band_additional) ? $band->band_additional->about : null),
            ],
            'gigs' => (count($gigs) > 0 ? $gigs : null)
        ];

        return response()->json([
            'band' => $response
        ]);
    }

    public function getNewsPage($url_name)
    {
        $news = News::where('url_safe_name', $url_name)->first();

        $response = [
            'title' => $news->title,
            'url_name' => $news->url_safe_name,
            'created_at' => Carbon::parse($news->created_at)->toDayDateTimeString(),
            'body' => $news->body
        ];

        return response()->json([
            'news' => $response
        ]);
    }
}