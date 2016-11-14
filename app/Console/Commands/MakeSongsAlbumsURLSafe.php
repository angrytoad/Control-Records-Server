<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Song;
use App\Album;

class MakeSongsAlbumsURLSafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:song-album-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =    'This command will update all songs and albums with a new url-safe field that will be used on 
                                frontend of the application.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $songs = Song::orderBy('created_at','DESC')->get();
        foreach($songs as $song){
            $song->url_safe_name = str_slug($song->song_name, '-');
            $song->save();
            $this->info('Changed '.$song->song_name.' URL SAFE NAME TO '.$song->url_safe_name);
        }

        $albums = Album::orderBy('created_at', 'DESC')->get();
        foreach($albums as $album){
            $album->url_safe_name = str_slug($album->album_name, '-');
            $album->save();
            $this->info('Changed '.$album->album_name.' URL SAFE NAME TO '.$album->url_safe_name);
        }
    }
}
