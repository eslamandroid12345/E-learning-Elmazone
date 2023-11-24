@extends('admin.layouts_admin.master')

@section('title')
    الجمل التحفيزية
@endsection
@section('page_name')
    الجمل التحفيزية
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
                        <button class="btn btn-success btn-icon text-white exportExel">
									<span>
										<i class="fa fa-file-pdf"></i>
									</span> تصدير ملف اكسيل
                        </button>
                        <button class="btn btn-info btn-icon text-white importExel"
                                data-toggle="modal" data-target="#importExel">
									<span>
										<i class="fa fa-file-pdf"></i>
									</span> استيراد ملف اكسيل
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
                                <th class="min-w-50px">الاسم</th>
                                <th class="min-w-50px">النسبه من</th>
                                <th class="min-w-50px">النسبه الي</th>
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
                        <p>هل نت متأكد من عملية الحذف<span id="title" class="text-danger"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                            اغلاق
                        </button>
                        <button type="button" class="btn btn-danger"
                                id="delete_btn">حذف</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">  الجمل التحفيزية </h5>
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


        <!-- Import Modal -->
        <div class="modal fade" id="importExel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">استيراد ملف اكسيل</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <form method="post" id="importExelForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <label class="form-label" for="exelFile">ملف الجمل التحفيزية</label>
                                    <input class="form-control form-control-file dropify" type="file" name="exelFile">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary importBtn">رفع</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Import Modal -->
    </div>
    @include('admin.layouts_admin.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'title_ar', name: 'title_ar'},
            {data: 'percentage_from', name: 'percentage_from'},
            {data: 'percentage_to', name: 'percentage_to'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route('motivational.index')}}', columns);
        // Delete Using Ajax
        destroyScript('{{route('motivational.destroy',':id')}}');
        // Add Using Ajax
        showAddModal('{{route('motivational.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route('motivational.edit',':id')}}');
        editScript();


        $(document).ready(function () {
            $('.exportExel').on('click', function () {
                window.location.href = '{{ route('motivationalExport')}}'
            })
        })

        $('.dropify').dropify();

        $(document).on("submit", "#importExelForm", function (event) {
            event.preventDefault();
            event.stopImmediatePropagation();

            var formData = new FormData(this);

            $.ajax({
                url: '{{ route('motivationalImport') }}',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data.status === 200) {
                        toastr.success('تم استيراد الملف بنجاح')
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000)

                    } else if (data.status === 500) {
                        toastr.error('فشل في استيراد الملف')
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000)
                    }
                },
                error: function (data) {
                    toastr.error('فشل في استيراد الملف')
                    setTimeout(function () {
                        // window.location.reload();
                    }, 2000)
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
    </script>
@endsection

