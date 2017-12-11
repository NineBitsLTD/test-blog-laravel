<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends \App\Http\Controllers\Controller
{
    public static $page = 'admin/user';
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
        $pagination = User::select()->orderBy('created_at','desc')->paginate(5);
        $pagination->setPath(url(static::$page));
        if($pagination->currentPage()!=1) $this->list['first_page_url'] = $pagination->url(1);
        if($pagination->currentPage()!=$pagination->lastPage()) $this->list['last_page_url'] = $pagination->url($pagination->lastPage());
        return view('user.list',['items'=>$pagination->toArray()]);
    }
    
    public function create(){
        return view('user.edit',[]);
    }
    
    public function edit($id){
        return view('user.edit',['item'=>User::find($id)]);
    }
    
    public function save($id){
        $input = Request::capture()->input();
        if(isset($input['_token'])) unset($input['_token']);
        $image = null;
        if (Request::capture()->hasFile('image')) $image = Request::capture()->file('image');
        $validator = $this->validator(Request::capture()->all(), $id);
        foreach ($input as $key=>$value) if($value=='') $input[$key] = null;
        $item = User::find($id);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            if(isset($item)) {
                $item->fill($input)->save();
            } else {
                $item = User::create($input);
            }
            return redirect(static::$page)->with('success',\Lang::get('messages.success_create'));
        }
    }
    
    public function delete($id){
        User::destroy($id);
        return redirect(static::$page);
    }
    
    protected function validator(array $data, $id)
    {
        return Validator::make($data, User::rules($id));
    }
}
