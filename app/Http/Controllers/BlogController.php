<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Post;

class BlogController extends Controller
{
    public static $page = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Request::capture()->has('friend')) return redirect()->route('register', ['friend' => Request::capture()->get('friend')]);
        Paginator::currentPageResolver(function() {
            return Request::capture()->has('page')?(int)Request::capture()->get('page'):0;
        });
        $pagination = Post::select()->orderBy('created_at','desc')->paginate(5);
        $pagination->setPath(url(self::$page));
        if($pagination->currentPage()!=1) $this->list['first_page_url'] = $pagination->url(1);
        if($pagination->currentPage()!=$pagination->lastPage()) $this->list['last_page_url'] = $pagination->url($pagination->lastPage());
        return view('blog',['items'=>$pagination->toArray()]);
    }
    
    public function read($code){
        return view('blog.view',['item'=>(new Post)->where('code',$code)->first()->toArray()]);
    }
}