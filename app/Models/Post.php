<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public static $rules = [
        //'id' => '',
        'title' => 'required|string|unique:posts,title',
        'code' => 'required|string|unique:posts,code',
        //'preview' => '',
        //'text' => '',
        //'created_at' => '',
        //'updated_at' => '',
    ];
    
    public static function rules($id){
        if($id>0){
            $rules = static::$rules;
            $rules['title'] .= ",{$id}";
            $rules['code'] .= ",{$id}";
            return $rules;
        } else return static::$rules;
    }
    protected $fillable = [
        'title', 'code', 'preview', 'text'
    ];
}
