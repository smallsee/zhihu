@extends('layouts.app')
@section('css')
@endsection
@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$question->title}}

                        @foreach($question->topics as $topic)
                            <a style="float: right" href="/topic/{{$topic->id}}" class="topic">{{$topic->name}}</a>
                        @endforeach
                    </div>

                    <div class="panel-body content">
                        {!! $question->body !!}
                    </div>
                    <div class="panel-footer actions">
                        @if(Auth::check() && Auth::user()->owns($question))
                            <span class="edit">
                                <a  href="{{route('question.edit',[$question->id])}}">编辑</a>
                            </span>
                            <form method="post" action="{{route('question.destroy',[$question->id])}}">
                                {{csrf_field()}}
                                {{method_field('DELETE')}}
                                <button class="button is-naked delete-button" type="submit">删除</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading question-follow">
                        <h2>
                            <h2>{{ $question->followers_count }}</h2>
                            <span>关注者</span>
                        </h2>
                    </div>
                    <div class="panel-body">
                        {{--<a href="/question/{{$question->id}}/follow" class="btn btn-default--}}
                            {{--{{Auth::user()->followed($question->id) ? 'btn-success' : '' }}--}}
                        {{--">--}}
                            {{--{{Auth::user()->followed($question->id) ? '已关注' : '关注该问题' }}--}}
                        {{--</a>--}}

                        <question-follow-button  :question="{{$question->id}}"></question-follow-button>

                        <a href="#editor" class="btn btn-primary">撰写答案</a>
                    </div>
                </div>

            </div>

            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{$question->answers_count}}个回答
                    </div>

                    <div class="panel-body">

                        @foreach($question->answers as $answer)
                            <div class="media">
                                <div class="media-left">
                                    <a href="">
                                        <img width="36" src="{{$answer->user->avatar}}" alt="{{$answer->user->name}}">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="/user/{{$answer->user->name}}">
                                            {{$answer->user->name}}
                                        </a>
                                    </h4>
                                    {!! $answer->body !!}
                                </div>
                            </div>
                        @endforeach


                    @if(Auth::check())
                        {!! Former::vertical_open(url('/question/'.$question->id.'/answer'))
                         ->id('MyForm')
                         ->method('post') !!}

                        @include('errors')

                        <div class="form-group required">
                            <!-- 编辑器容器 -->
                            <script  id="container" name="body" type="text/plain">
                                {!! old('body') !!}
                            </script>
                        </div>

                        {!!  Former::actions()
                            ->large_success_submit('发布回答')->class('pull-right') !!}

                        {!! Former::close() !!}
                            @else
                                <a href="/login" class="btn btn-success btn-block">登陆提交评论</a>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        $('#container').height(100);
        var ue = UE.getEditor('container',{
                toolbars: [
                    ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'insertimage', 'fullscreen']
                ],
                elementPathEnabled: false,
                enableContextMenu: false,
                autoClearEmptyNode:true,
                wordCount:false,
                imagePopup:false,
                autotypeset:{ indent: true,imageBlockLine: 'center' }
            }
        );
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection