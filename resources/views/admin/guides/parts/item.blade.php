@extends('admin.layouts_admin.master')

@section('title')
    عناصر
@endsection
@section('page_name')
    عنصر
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
									</span> اضافة
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
                                <th class="min-w-50px">عنوان العنصر</th>
                                <th class="min-w-25px">محتوي الشهر(مثال شهر 1)</th>
                                <th class="min-w-50px">الفصل</th>
                                <th class="min-w-50px">الدرس</th>
                                <th class="min-w-50px">  رابط ملف المراجعه</th>
                                <th class="min-w-50px">   رابط ملف الاجابه الورقي</th>
                                <th class="min-w-50px"> رابط فيديو الاجابه</th>
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
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('admin.delete') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متاكد من عملية الحذف<span id="title" class="text-danger"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                            اغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حدف</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade bd-example-modal-lg" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">اضافة عنصر</h5>
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
            {data: 'title_ar', name: 'title_ar'},
            {data: 'month', name: 'month'},
            {data: 'subject_class_id', name: 'subject_class_id'},
            {data: 'lesson_id', name: 'lesson_id'},
            {data: 'file', name: 'file'},
            {data: 'answer_pdf_file', name: 'answer_pdf_file'},
            {data: 'answer_video_file', name: 'answer_video_file'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{ route('indexItem', $id) }}', columns);
        // Delete Using Ajax
        destroyScript('{{ route('guide.destroy', ':id') }}');
        // Add Using Ajax
        showAddModal('{{route('addItem', $id)}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route('editItem', ':id')}}');
        editScript();


        $(document).ready(function () {
            $('.season_id').on('change', function () {
                let season = $(this).val();
                $.ajax({
                    url: '{{ route("subjectClassSort")}}',
                    method: 'GET',
                    data: {
                        'id': season,
                    }, success: function (data) {
                        $('.term_id').html(data);
                        console.log(data);
                    }
                })
            })
        });


    </script>

@endsection

