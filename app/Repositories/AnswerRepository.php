<?php
/**
 * Created by PhpStorm.
 * User: xiaohai
 * Date: 17/7/17
 * Time: 下午2:34
 */

namespace App\Repositories;


use App\Answer;

class AnswerRepository
{


    public function create(array $attributes){
        return Answer::create($attributes);
    }


    public function byId($id){
        return Answer::find($id);
    }

    public function getQuestionsFeed(){
        return Answer::published()->latest('updated_at')->with('user')->get();
    }
}