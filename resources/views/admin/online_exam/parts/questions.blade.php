@extends('admin.layouts_admin.master')

@section('title')
    الاسئلة
@endsection
@section('page_name')
    الاسئلة
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">السؤال</th>
                                <th class="min-w-50px">درجه السؤال</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل أنت متأكد من عملية الحذف<span id="title" class="text-danger"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                            أغلاق
                        </button>
                        <button type="button" class="btn btn-danger"
                                id="delete_btn">حذف</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade bd-example-modal-lg" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">امتحان الاونلاين</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts_admin.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'question_id', name: 'question_id'},
            {data: 'degree', name: 'degree'},
        ]
        showData('{{route('indexQuestion', $id)}}', columns);
        // Delete Using Ajax
        destroyScript('{{route('onlineExam.destroy',':id')}}');
        // Add Using Ajax
        showAddModal('{{route('onlineExam.create')}}');
        addScript();
        showAddQuestion('{{ route('indexQuestion',':id') }}')
        // Add Using Ajax
        showEditModal('{{route('onlineExam.edit',':id')}}');
        editScript();
    </script>
@endsection

<!-- fix -->




{{--@extends('Admin/layouts_admin/master')--}}

{{--@section('title')--}}
{{--    اسئلة امتحان الاونلاين--}}
{{--@endsection--}}
{{--@section('page_name')--}}
{{--    اسئلة امتحان الاونلاين--}}
{{--@endsection--}}
{{--@section('content')--}}
{{--    <form method="POST" action="">--}}
{{--        @csrf--}}
{{--        <input type="hidden" name="online_exam_id" value="{{ $exam->id }}">--}}
{{--        <input type="hidden" name="all_exam_id">--}}
{{--        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12 col-lg-12">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header">--}}
{{--                        <h3 class="card-title"></h3>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="table-responsive">--}}
{{--                            <!--begin::Table-->--}}
{{--                            <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">--}}
{{--                                <thead>--}}
{{--                                    <tr class="fw-bolder text-muted bg-light">--}}
{{--                                        <th class="min-w-25px">#</th>--}}
{{--                                        <th class="min-w-50px">السؤال</th>--}}
{{--                                        <th class="min-w-50px">ملاحظة</th>--}}
{{--                                        <th class="min-w-50px">فصل</th>--}}
{{--                                        <th class="min-w-50px">الترم</th>--}}
{{--                                        <th class="min-w-50px">نوع المثال</th>--}}
{{--                                        <th class="min-w-50px">المثال</th>--}}
{{--                                        <th class="min-w-50px rounded-end">العمليات</th>--}}
{{--                                    </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                    @foreach ($questions as $question)--}}
{{--                                        <tr>--}}
{{--                                            <td>{{ $question->id }}</td>--}}
{{--                                            <td>{{ $question->question }}</td>--}}
{{--                                            <td>{{ $question->note }}</td>--}}
{{--                                            <td>{{ $question->season_id }}</td>--}}
{{--                                            <td>{{ $question->term_id }}</td>--}}
{{--                                            <td>{{ $question->examable_type }}</td>--}}
{{--                                            <td>{{ $question->examable_id }}</td>--}}
{{--                                            <td><input type="checkbox"--}}
{{--                                                 class="form-control check1 check" name="question_id" --}}
{{--                                                 {{ (in_array($question->id,$online_questions_ids)? "checked":'') }}--}}
{{--                                                    value="{{ $question->id }}"--}}
{{--                                                     id="check"></td>--}}
{{--                                                     --}}

{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--    @include('Admin/layouts_admin/myAjaxHelper')--}}
{{--@endsection--}}
{{--@section('ajaxCalls')--}}
{{--    <script>--}}
{{--        $(".check").on('click', function() {--}}
{{--           --}}
{{--            var exam = $('input[name="online_exam_id"]').val();--}}
{{--            var question = $(this).val();--}}
{{--            --}}
{{--            if ($(this).is(':checked')) {--}}
{{--                $.ajax({--}}
{{--                    type: 'POST',--}}
{{--                    url: '{{ route('addQuestion') }}',--}}
{{--                    data: {--}}
{{--                       --}}
{{--                        online_exam_id: exam,--}}
{{--                        question_id: question,--}}
{{--                        "_token": $('#token').val()--}}

{{--                    },--}}
{{--                    success: function(data) {--}}
{{--                        toastr.success('تم الاضافة بنجاح');--}}
{{--                    },--}}
{{--                    error: function(data) {--}}
{{--                        toastr.error('هناك خطأ ما ..');--}}
{{--                    }--}}

{{--                });--}}
{{--            } else {--}}
{{--                $.ajax({--}}
{{--                    type: 'POST',--}}
{{--                    url: '{{ route('deleteQuestion') }}',--}}
{{--                    data: {--}}

{{--                        online_exam_id: exam,--}}
{{--                        question_id: question,--}}
{{--                        "_token": $('#token').val()--}}

{{--                    },--}}
{{--                    success: function(data) {--}}
{{--                        toastr.success('تم الحذف بنحاح بنجاح');--}}
{{--                    },--}}
{{--                    error: function(data) {--}}
{{--                        toastr.error('هناك خطأ ما ..');--}}
{{--                    }--}}
{{--                })--}}
{{--            }--}}
{{--        })--}}
{{--    </script>--}}
{{--@endsection--}}
