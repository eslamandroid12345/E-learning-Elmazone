@extends('admin.layouts_admin.master')
@foreach ($answers as $answer)
    @section('title')
        ورقة الطالب {{ $answer->user->name }}
    @endsection
    @section('page_name')
        ورقة الطالب {{ $answer->user->name }}
    @endsection
@endforeach
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">بيانات امتحان الطالب </h1>

                </div>
                <div class="card-body">
                    @foreach ($answers as $answer)
                        <form action="">
                            <input type="hidden" id="user" name="user_id" value="{{ $answer->user_id }}" />
                            <input type="hidden" id="sheetExam" name="papel_sheet_exam_id"
                                value="{{ $answer->papel_sheet_exam_id }}" />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">الامتحان</label>
                                        <input class="form-control" name=""
                                            value="{{ $answer->papelSheetExam->name_ar }}" disabled />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">وقت الامتحان</label>
                                        <input class="form-control" name=""
                                            value="{{ $answer->papelSheetExamTime->from }}" disabled />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">الطالب</label>
                                        <input class="form-control" name="" value="{{ $answer->user->name }}"
                                            disabled />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">القاعة</label>
                                        <input class="form-control" name=""
                                            value="{{ $answer->sections->section_name_ar }}" disabled />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">الدرجة</label>
                                        <input type="number" class="form-control" id="degreeUser"
                                            style="text-align: center" name="degree" value="" />
                                    </div>
                                </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="addDegree" data-dismiss="modal">اضافة
                        علامة</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    @include('admin.layouts_admin.myAjaxHelper')
@endsection
@section('scripts')
    <script>
        $(document).on('click', '#addDegree', function(e) {
            e.preventDefault();
            var user = $("#user").val();
            var sheetExamId = $("#sheetExam").val();
            var degree = $("#degreeUser").val();
            var url = '/store-paper-exam-sheet/';
            var data = {
                '_token': "{{ csrf_token() }}",
                'user_id': user,
                'papel_sheet_exam_id': sheetExamId,
                'degree': degree,
            };
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(data) {
                    toastr.success('success', 'تم اضافة الدرجة');
                },
                error: function() {
                    toastr.danger('error', 'لم تتم اضافة الدرجة');
                },
            });
        });
    </script>
@endsection
