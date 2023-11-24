@extends('admin.layouts_admin.master')

@section('title')
    المصادر والمراجع
@endsection
@section('page_name')
    المصادر والمراجع
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
                                    <th class="min-w-25px">لون الخلفية</th>
                                    <th class="min-w-50px">عنوان المرجع</th>
                                    <th class="min-w-50px">الصف الدراسي</th>
                                    <th class="min-w-50px">التيرم</th>
                                    <th class="min-w-50px">الملف الورقي للكتاب</th>
                                    <th class="min-w-50px">ملاحظات</th>
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
                        <p>حذف<span id="title" class="text-danger"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                            اغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف</button>
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
                        <h5 class="modal-title" id="example-Modal3">الدليل</h5>
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
            {data: 'background_color', name: 'background_color'},
            {data: 'title_ar', name: 'title_ar'},
            {data: 'season_id', name: 'season_id'},
            {data: 'term_id', name: 'term_id'},
            {data: 'description_ar', name: 'description_ar'},
            {data: 'file', name: 'file'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{ route('guide.index') }}', columns);
        // Delete Using Ajax
        destroyScript('{{ route('guide.destroy', ':id') }}');
        // Add Using Ajax
        showAddModal('{{ route('guide.create') }}');
        addScript();
        // Add Using Ajax
        showEditModal('{{ route('guide.edit', ':id') }}');
        editScript();
    </script>
@endsection
