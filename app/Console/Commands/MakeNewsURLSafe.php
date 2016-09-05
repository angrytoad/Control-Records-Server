<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\News;

class MakeNewsURLSafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:news-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =    'This command will update all news articles with a new url-safe field that will be used on 
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
        $news = News::orderBy('created_at','DESC')->get();
        foreach($news as $article){
            $article->url_safe_name = str_slug($article->title, '-');
            $article->save();
            $this->info('Changed '.$article->title.' URL SAFE NAME TO '.$article->url_safe_name);
        }
    }
}
