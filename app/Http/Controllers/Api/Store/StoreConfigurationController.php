<?php

namespace App\Http\Controllers\Api\Store;

use App\Album;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Gig;
use App\News;
use App\Band;
use App\Song;
use App\Venue;
use App\Store_Configuration;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\View\View;


class StoreConfigurationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function index(){
        $current_config = Store_Configuration::where('configuration_active',true)->first();
        $other_configurations = Store_Configuration::where('configuration_active',false)->orderBy('created_at','DESC')->get();
        $articles = News::where('created_at','>=',Carbon::now()->subMonth(6))->get();
        $artists = Band::orderBy('name','DESC')->get();
        $albums = Album::orderBy('album_name','DESC')->get();

        return view('store_manager/index', array(
            'current_config' => $current_config,
            'other_configurations' => $other_configurations,
            'articles' => $articles,
            'artists' => $artists,
            'albums' => $albums
        ));
    }
    
    public function store(Request $request){
        $this->validate($request, [
            'config-name' => 'required|max:255',
            'featured-article' => 'required|max:64',
            'featured-album' => 'required|max:64',
            'featured-artist' => 'required|max:64',
        ]);

        Store_Configuration::create([
            'id' => Uuid::uuid(),
            'configuration_name' => $request['config-name'],
            'configuration_active' => false,
            'featured_article' => $request['featured-article'],
            'featured_album' => $request['featured-album'],
            'featured_artist' => $request['featured-artist'],
        ]);

        return redirect('/store/config');
    }

    public function deleteConfiguration($id){
        $config = Store_Configuration::find($id);
        if($config->configuration_active){
            return redirect('/store/config')->with('errors', 'You cannot delete an active configuration, please make another configuration active before trying again');
        }else{
            $config->delete();
            return redirect('/store/config');
        }

    }

    public function setActiveConfiguration($id){

        $current_active_configs = Store_Configuration::where('configuration_active', true)->get();

        foreach($current_active_configs as $current_active_config){
            $current_active_config->configuration_active = false;
            $current_active_config->save();
        }

        $config = Store_Configuration::find($id);
        $config->configuration_active = true;
        $config->save();

        return redirect('/store/config');

    }

}










