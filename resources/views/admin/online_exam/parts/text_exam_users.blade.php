@extends('admin.layouts_admin.master')

@section('title')
    الطلاب الذين امتحنوا
@endsection
@section('page_name')
    الطلاب الذين امتحنوا
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
									<span>
										<i class="fe fe-plus"></i>
									</span> أضافة
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-50px">الاسم</th>
                                <th class="min-w-50px rounded-end">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                                <tr>
                                    <td>{{ ($user->name) }}</td>
                                    <td>
                                        <a href="{{ route('paperExam',[$user->id,$exam->id])}}"><button type="button" class="btn btn-pill btn-info-light" data-toggle="modal"
                                                data-target=""><i
                                                class="fa fa-book-open"></i></button></a>


{{--                                        <a href="{{ route('paperExam', $user->user->id) }}"><button type="button" class="btn btn-pill btn-info-light" data-toggle="modal"--}}
{{--                                                                                                    data-target=""><i--}}
{{--                                                        class="fa fa-book-medical"></i></button></a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

{{--        <!--Delete MODAL -->--}}
{{--        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"--}}
{{--             aria-hidden="true">--}}
{{--            <div class="modal-dialog" role="document">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>--}}
{{--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                            <span aria-hidden="true">×</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <input id="delete_id" name="id" type="hidden">--}}
{{--                        <p>هل أنت متأكد من عملية الحذف<span id="title" class="text-danger"></span></p>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">--}}
{{--                            أغلاق--}}
{{--                        </button>--}}
{{--                        <button type="button" class="btn btn-danger"--}}
{{--                                id="delete_btn">حذف--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <!-- MODAL CLOSED -->--}}

        <!-- Create Or Edit Modal -->
{{--        @foreach($online_exams as $user)--}}
{{--            <div class="modal fade bd-example-modal-lg" id="editOrCreate{{ $user->user->id }}" data-backdrop="static"--}}
{{--                 tabindex="-1" role="dialog"--}}
{{--                 aria-hidden="true">--}}
{{--                <div class="modal-dialog modal-lg" role="document">--}}
{{--                    <div class="modal-content">--}}
{{--                        <div class="modal-header">--}}
{{--                            <h5 class="modal-title" id="example-Modal3">ورقة الطالب {{ $user->user->name }}</h5>--}}
{{--                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                <span aria-hidden="true">&times;</span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        <div class="modal-body" id="modal-body">--}}
{{--                            <form action="" method="post">--}}
{{--                                @csrf--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="question_text">Question text:</label>--}}
{{--                                    <textarea class="form-control" name="question_text" id="question_text"></textarea>--}}
{{--                                </div>--}}

{{--                                <div class="form-group">--}}
{{--                                    <label for="answer_choices">Answer choices:</label>--}}
{{--                                    <textarea class="form-control" name="answer_choices" id="answer_choices"></textarea>--}}
{{--                                </div>--}}

{{--                                <div class="form-group">--}}
{{--                                    <label for="correct_answer">Correct answer:</label>--}}
{{--                                    <input type="text" class="form-control" name="correct_answer" id="correct_answer" value="">--}}
{{--                                </div>--}}

{{--                                <button type="submit" class="btn btn-primary">Submit</button>--}}
{{--                            </form>--}}

{{--                            --}}{{--                            <div class="form-group">--}}
{{--                                <div class="row">--}}
{{--                                    @foreach($exam->questions as $questions)--}}
{{--                                        <p>{{ $questions->question }}</p>--}}

{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                                <div class="row">--}}
{{--                                    @foreach($answers as $answer)--}}
{{--                                        <p>{{ $answer->answer }}</p>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}
    </div>
    @include('admin.layouts_admin.myAjaxHelper')
@endsection


