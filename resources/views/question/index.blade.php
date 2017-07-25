@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>

                    <div class="panel-body">
                        @foreach($questions as $question)
                            <div class="media">
                                <div class="media-left">
                                    <a href="">
                                        <img width="48" src="{{$question->user->avatar}}" alt="{{$question->user->name}}">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="{{route('question.show',[$question->id])}}">
                                            {{ str_limit($question->title,50,'...') }}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
