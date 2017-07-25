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

Route::get('/', 'QuestionController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('email/verify/{token}', ['as'=>'email.verify','uses'=>'EmailController@verify']);

Route::resource('question','QuestionController',['name' => [
    'index' => 'question.index',
    'create' => 'question.create',
    'store' => 'question.store',
    'show' => 'question.show',
    'edit' => 'question.edit',
    'update' => 'question.update',
    'destroy' => 'question.destroy',
]]);

Route::post('question/{question}/answer','AnswerController@store');

Route::get('question/{question}/follow','QuestionFollowController@follow');