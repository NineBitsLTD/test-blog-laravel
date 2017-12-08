<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;

class BlogController extends \App\Http\Controllers\Controller
{
    public static $page = 'admin/blog';
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
        Paginator::currentPageResolver(function() {
            return Request::capture()->has('page')?(int)Request::capture()->get('page'):0;
        });
        $pagination = Post::select()->orderBy('created_at','desc')->paginate(5);
        $pagination->setPath(url(static::$page));
        if($pagination->currentPage()!=1) $this->list['first_page_url'] = $pagination->url(1);
        if($pagination->currentPage()!=$pagination->lastPage()) $this->list['last_page_url'] = $pagination->url($pagination->lastPage());
        return view('blog.list',['items'=>$pagination->toArray()]);
    }
    
    public function create(){
        return view('blog.edit',[]);
    }
    
    public function edit($id){
        return view('blog.edit',['item'=>Post::find($id)]);
    }
    
    public function save($id){
        $input = Request::capture()->input();
        if(isset($input['_token'])) unset($input['_token']);
        $image = null;
        if (Request::capture()->hasFile('image')) $image = Request::capture()->file('image');
        $validator = $this->validator(Request::capture()->all(), $id);
        foreach ($input as $key=>$value) if($value=='') $input[$key] = null;
        $item = Post::find($id);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            if(isset($item)) {
                $item->fill($input)->save();
            } else {
                $item = Post::create($input);
            }
            if (isset($image)) {
                $destinationPath = public_path('/img/post');
                $image->move($destinationPath, $item->id);
                $img = new \App\Helper\Image($destinationPath."/".$item->id);
                $img->resize(100, 100)->save();
            }
            return redirect(static::$page)->with('success',\Lang::get('messages.success_create'));
        }
    }
    
    public function delete($id){
        Post::destroy($id);
        return redirect(static::$page);
    }
    
    protected function validator(array $data, $id)
    {
        return Validator::make($data, Post::rules($id)+[
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }
}
