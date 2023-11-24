@extends('admin.layouts_admin.master')

@section('title')
    دروس
@endsection
@section('page_name')
    درس
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="row">
                        <form action="" method="get" class="d-flex">
                        <div class="col-5">
                            <label for="">الصف</label>
                            <select class="form-control seasonSort">
                                <option value="" selected>الكل</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="col-5">
                                <label for="">الوحدة</label>
                                <select class="form-control subjectClass" name="subject_class_id">
                                    <option value="" selected>الكل</option>
                                </select>
                            </div>
                            <div class="col-2 mt-6">
                                <button class="btn btn-success" type="submit">فلتر</button>
                            </div>
                        </form>
                    </div>
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
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">الأسم</th>
                                <th class="min-w-50px">الوحدة</th>
                                <th class="min-w-50px">ملاحظة</th>
                                <th class="min-w-50px rounded-end">العمليات</th>
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
                                id="delete_btn">حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">درس</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Or Edit Modal -->
    </div>
    @include('admin.layouts_admin.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'subject_class_id', name: 'subject_class_id'},
            {data: 'note', name: 'note'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        let subjectClass = $('.subjectClass').val();

        // $('.subjectClass').on('change', function(){
        //     let subjectClass = $('.subjectClass').val();
        // })

        var ajax = $.ajax({
            url: '{{ route("lessons.index")}}',
            method: 'GET',
            data: {
                'subject_class_id': subjectClass,
            }, success: function (data) {
                table.draw();
            }
        })
        showData(ajax, columns);
        // Delete Using Ajax
        destroyScript('{{route('lessons.destroy',':id')}}');
        // Add Using Ajax
        showAddModal('{{route('lessons.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route('lessons.edit',':id')}}');
        editScript();


        $(document).ready(function () {
            $('.seasonSort').on('change', function () {
                let season = $(this).val();
                $.ajax({
                    url: '{{ route("seasonSort")}}',
                    method: 'GET',
                    data: {
                        'id': season,
                    }, success: function (data) {
                        $('.subjectClass').html(data);
                        console.log(data);
                    }
                })
            })
        });

        {{--$(document).ready(function () {--}}
        {{--    $('.subjectClass').on('change', function () {--}}
        {{--        let subjectClass = $('.subjectClass').val();--}}

        {{--        $.ajax({--}}
        {{--            url: '{{ route("lessons.index")}}',--}}
        {{--            method: 'GET',--}}
        {{--            data: {--}}
        {{--                'subject_class_id': subjectClass,--}}
        {{--            }, success: function (data) {--}}
        {{--                table.draw();--}}
        {{--            }--}}
        {{--        })--}}
        {{--    })--}}
        {{--})--}}


    </script>
@endsection

