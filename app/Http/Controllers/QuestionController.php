<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;
use Auth;
use MercurySeries\Flashy\Flashy;
class QuestionController extends Controller
{

    protected $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {

        $this->middleware('auth')->except(['index','show']);

        $this->questionRepository = $questionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $questions = $this->questionRepository->getQuestionsFeed();
        return view('question.index',compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('question.create');
    }

    /**
     * @param QuestionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(QuestionRequest $request)
    {

        $topics = $this->questionRepository->normalizeTopic($request->get('topics'));

        $data = [
          'title' => $request->get('title'),
          'body' => $request->get('body'),
          'user_id' => Auth::id()
        ];

        $question = $this->questionRepository->create($data);

        $question->topics()->attach($topics);

        Flashy::success('创建成功!');
        return redirect()->route('question.show',[$question->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = $this->questionRepository->byIdWithTopicsAndAnswers($id);

        return view('question.show',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = $this->questionRepository->byId($id);
        if (Auth::user()->owns($question)){
            return view('question.edit',compact('question'));
        }
        return back();
    }

    /**
     * @param QuestionRequest $request
     * @param $id
     */
    public function update(QuestionRequest $request, $id)
    {
        $topics = $this->questionRepository->normalizeTopic($request->get('topics'));
        $question = $this->questionRepository->byId($id);

        $question->update([
           'title' => $request->get('title'),
           'body' => $request->get('body')
        ]);

        $question->topics()->sync($topics);

        Flashy::success('修改成功!');
        return redirect()->route('question.show',[$question->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $question = $this->questionRepository->byId($id);
        if (Auth::user()->owns($question)){
            $question->delete();
            Flashy::success('删除成功！');
            return redirect('/');
        }

        abort(403,'Forbidden');
    }

}
