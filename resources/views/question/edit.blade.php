@extends('layouts.app')

@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">发布问题</div>

                    <div class="panel-body">
                    @include('errors')
                    {!! Former::vertical_open(url(route('question.update',[$question->id])))
                     ->id('MyForm')
                     ->method('post') !!}

                        {{method_field('PATCH')}}
                        {!! Former::text('title')
                                ->label('标题')
                                ->value($question->title)
                                ->required() !!}


                        <div class="form-group required">
                            <label for="topics[]" class="control-label">话题<sup>*</sup></label>
                            <select name="topics[]" class="form-control js-example-basic-multiple js-example-placeholder-multiple" multiple="multiple">
                                @foreach($question->topics as $topic)
                                    <option value="{{$topic->id}}" selected="selected">{{$topic->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group required">
                            <label for="title" class="control-label">描述<sup>*</sup></label>
                            <!-- 编辑器容器 -->
                            <script  id="container" name="body"  type="text/plain">
                                {!! $question->body !!}
                            </script>
                        </div>



                        {!!  Former::actions()
                            ->large_success_submit('提交')->class('pull-right') !!}

                        {!! Former::close() !!}
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        $('#container').height(300);
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
    <script>
        $(document).ready(function(){
            function formatTopic (topic) {
                return "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" +
                topic.name ? topic.name : "Laravel"   +
                    "</div></div></div>";
            }

            function formatTopicSelection (topic) {
                return topic.name || topic.text;
            }

            $(".js-example-placeholder-multiple").select2({
                tags: true,
                placeholder: '选择相关话题',
                minimumInputLength: 2,
                ajax: {
                    url: '/api/topics',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                templateResult: formatTopic,
                templateSelection: formatTopicSelection,
                escapeMarkup: function (markup) { return markup; }
            });
        });
    </script>
@endsection
