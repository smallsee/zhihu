<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Repositories\AnswerRepository;
use Illuminate\Http\Request;
use Auth;
use MercurySeries\Flashy\Flashy;

class AnswerController extends Controller
{
    protected $answer;

    public function __construct(AnswerRepository $answer)
    {
        $this->answer = $answer;
    }

    public function store(AnswerRequest $request, $question){

        $answer = $this->answer->create([
            'question_id' => $question,
            'user_id' => Auth::id(),
            'body' => $request->get('body')
        ]);
        $answer->question()->increment('answers_count');
        Flashy::success('成功添加回答！欢迎回来');

        return back();
    }
}
