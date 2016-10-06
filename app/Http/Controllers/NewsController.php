<?php
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 31/07/2016
 * Time: 11:22
 */

namespace App\Http\Controllers;

use App\Band;
use App\Http\Requests;
use App\User;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\News;
use App\Http\Controllers\Auth;

class NewsController extends Controller
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
        $articles = News::orderBy('created_at','DESC')->get();


        return view('news/index', array(
            'articles' => $articles,
        ));
    }

    public function createPage()
    {
        return view('news/create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $news = new News;
        $news->title = $request->input('title');
        $news->body = $request->input('body');
        $news->user_id = $request->user()->id;
        $news->url_safe_name = str_slug($request->input('title'), '-');
        $news->save();

        return redirect('/news');
    }

    public function newsPage($newsId)
    {
        $article = News::find($newsId);

        return view('news/article', array(
            'article' => $article
        ));
    }

    public function newsEdit(Request $request, $newsId)
    {
        $news = News::find($newsId);
        $news->title = $request->input('title');
        $news->body = $request->input('body');
        $news->save();

        return redirect('/news');
    }

    public function newsDelete($newsId){
        $news = News::find($newsId);
        $news->delete();

        return redirect('/news');
    }
}










