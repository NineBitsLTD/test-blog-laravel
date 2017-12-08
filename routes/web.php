<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\Post;

Route::get('/', 'BlogController@index');
Route::get('/blog/{code}', 'BlogController@read');

Auth::routes();

Route::get('admin/blog', 'Admin\BlogController@index');
Route::get('admin/blog/edit', 'Admin\BlogController@create');
Route::get('admin/blog/create', 'Admin\BlogController@create');
Route::post('admin/blog/save/{id}', 'Admin\BlogController@save');
Route::get('admin/blog/edit/{id}', 'Admin\BlogController@edit');
Route::get('admin/blog/delete/{id}', 'Admin\BlogController@delete');


