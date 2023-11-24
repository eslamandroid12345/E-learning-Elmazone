
@extends('admin.layouts_admin.master')
@section('content')


        <div class="form-group">


               {{--

                $text_exam_users_completed
                $online_exam_count_text_questions
               --}}

                {{--start text exam of students--}}
                <table class="styled-table table-text">
                    <thead>
                    <tr>
                        <th>السؤال</th>
                        <th>الاجابه</th>
                        <th>درجه السؤال</th>
                        <th>درجه الطالب</th>
                        <th>نوع الاجابه</th>
                        <th>اعتماد الدرجه</th>
                    </tr>
                    </thead>
                    <tbody>

                        @foreach($text_exam_users as  $text_exam_user)
                            <tr>

                        <td>{{  $text_exam_user->question->question }}</td>
                        <td>{{ $text_exam_user->answer}}</td>


                        <td><button type="button" class="btn btn-pill btn-success">{{ $text_exam_user->question->degree}}</button></td>
                                <td><button type="button" class="btn btn-pill btn-success">{{ $text_exam_user->degree}}</button></td>
                                @if($text_exam_user->answer_type == 'file')
                                    <td><button type="button" class="btn btn-pill btn-success">صوره</button></td>

                                @elseif($text_exam_user->answer_type == 'text')
                                    <td><button type="button" class="btn btn-pill btn-success">نص</button></td>
                                @else
                                    <td><button type="button" class="btn btn-pill btn-success">ملف صوتي</button></td>
                                @endif

                                @if($text_exam_user->degree_status == 'completed')
                                    <td><button type="button" class="btn btn-pill btn-info">معتمد</button></td>
                                @else

                                    <td>
                                    <form action="" method="post">
                                        <input type="number" id="degree-user-{{$text_exam_user->id}}" class="form-control"><br>
                                        <button type="button" id="add-degree-submit-{{$text_exam_user->id}}" class="btn btn-pill btn-danger add-degree" data-id="{{$text_exam_user->id}}">اعتماد الدرجه</button>

                                    </form></td>

                                @endif


                            </tr>

                        @endforeach


                    </tbody>
                </table>


            @if(!$exam_depends_for_user)
                @if($text_exam_users_completed == $online_exam_count_text_questions)

                    <form action="" method="post">
                        <button type="button" class="btn btn-pill btn-twitter add-exam-depends">اعتماد درجه الامتحان للطالب</button>

                    </form>

                @else
                    <button type="button" class="btn btn-pill btn-danger" disabled>اعتماد درجه الامتحان غير مفتوح</button>

                @endif

            @else

                <button type="button" class="btn btn-pill btn-info">!الامتحان اعتمد للطالب</button>

            @endif


        </div>

                {{--end text exam of students--}}


            {{--start answers of choice--}}


            <table class="styled-table">
                <thead>
                <tr>
                    <th>السؤال</th>
                    <th>الاجابه</th>
                    <th>درجه السؤال</th>
                    <th>درجه الطالب</th>
                    <th>حاله الاجابه</th>
                </tr>
                </thead>
                <tbody>

                    @foreach($online_exam_users as  $online_exam_user)
                        <tr>
                     <td>{{$online_exam_user->question->question}}</td>
                     <td>{{$online_exam_user->answer->answer}}</td>
                            <td>{{$online_exam_user->question->degree}}</td>
                            <td>{{$online_exam_user->degree}}</td>
                            <td>

                                @if($online_exam_user->status == 'solved')
                                    <button type="button" class="btn btn-pill btn-success">صح</button>

                                @elseif($online_exam_user->status == 'un_correct')
                                    <button type="button" class="btn btn-pill btn-danger">خطاء</button>

                                @else
                                    <button type="button" class="btn btn-pill btn-warning">غير محلول</button>

                                @endif
                            </td>


                        </tr>

                    @endforeach

                </tbody>
            </table>



          {{--end of choice--}}
    @include('admin.layouts_admin.myAjaxHelper')
@endsection

@section('scripts')


    <script>
        $(document).on('click','.add-degree',function(e) {
            e.preventDefault();
            var exam_id = $(this).data("id");
            var degree = $("#degree-user-"+exam_id).val();

            // alert(exam_id);

            $.ajax({
                url: '{{route("add-degree-to-text-exam")}}',
                type: 'POST',
                data: {
                    '_token': "{{csrf_token()}}",
                    'exam_id': exam_id,
                    'degree': degree,
                },

                beforeSend: function () {
                    $('#add-degree-submit-'+exam_id).html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">working</span>').attr('disabled', true);
                },

                success: function (data) {
                    var url  = '{{route("paperExam",['user_id' => ':user_id', 'exam_id' => ':exam_id'])}}';
                    url = url.replace(':user_id', {{$user_exam->id}});
                    url = url.replace(':exam_id', {{$exam_user->id}});
                    if (data.status == 200){

                        $(".form-group").load(url + " .form-group").fadeIn("slow");
                        toastr.success(data.message);
                    }
                    else
                        toastr.error('There is an error');
                },

                error: function (data) {

                    if (data.status === 422) {

                        toastr.error('There is an error');

                        // var errors = $.parseJSON(data.responseText);
                        // $.each(errors, function (key, value) {
                        //     if ($.isPlainObject(value)) {
                        //         $.each(value, function (key, value) {
                        //             toastr.error(value, key);
                        //         });
                        //     }
                        // });
                    }
                }//


            });
        });


        //start degree depends of exam for user -------------------------------------------
        $(document).on('click','.add-exam-depends',function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{route("exam-depends",[$user_exam->id,$exam_user->id])}}',
                type: 'POST',
                data: {
                    '_token': "{{csrf_token()}}",
                },

                beforeSend: function () {
                    $('.add-exam-depends').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">working</span>').attr('disabled', true);
                },

                success: function (data) {
                    var url  = '{{route("paperExam",['user_id' => ':user_id', 'exam_id' => ':exam_id'])}}';
                    url = url.replace(':user_id', {{$user_exam->id}});
                    url = url.replace(':exam_id', {{$exam_user->id}});
                    if (data.status == 200){

                        $(".form-group").load(url + " .form-group").fadeIn("slow");
                        toastr.success(data.message);
                    }
                    else
                        toastr.error('There is an error');
                },

                error: function (data) {

                    if (data.status === 422) {

                        toastr.error('There is an error');

                        // var errors = $.parseJSON(data.responseText);
                        // $.each(errors, function (key, value) {
                        //     if ($.isPlainObject(value)) {
                        //         $.each(value, function (key, value) {
                        //             toastr.error(value, key);
                        //         });
                        //     }
                        // });
                    }
                }//


            });
        });

    </script>

@endsection
