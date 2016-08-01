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
                    'additional' => $gig->additional_comments
                ],
                'venue' => [
                    'name' => $gig->venue->venue_name,
                    'coordinates' => json_decode($gig->venue->coordinates)
                ],
                'band' => $gig->band->name
            ];
            array_push($response, $gig);
        }

        return response()->json([
            'gigs' => $response
        ]);
    }
}