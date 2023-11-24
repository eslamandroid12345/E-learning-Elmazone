@extends('admin.layouts_admin.master')

@section('title')
    الفيديوهات الاساسية
@endsection
@section('page_name')
    الفيديوهات الاساسية
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
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">الاسم</th>
                                <th class="min-w-50px">لون الخلفية</th>
                                <th class="min-w-50px">الوقت</th>
                                <th class="min-w-50px">لينك الفيديو</th>
                                <th class="min-w-50px">تقييم</th>
                                <th class="min-w-50px">مشاهدة</th>
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
                        <h5 class="modal-title" id="example-Modal3">الفيديو الاساسي</h5>
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
            {data: 'background_color', name: 'background_color'},
            {data: 'time', name: 'time'},
            {data: 'video_link', name: 'video_link'},
            {data: 'like_active', name: 'like_active'},
            {data: 'view_active', name: 'view_active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route('videoBasic.index')}}', columns);
        // Delete Using Ajax
        destroyScript('{{route('videoBasic.destroy',':id')}}');
        // Add Using Ajax
        showAddModal('{{route('videoBasic.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route('videoBasic.edit',':id')}}');


        $(document).on('click', '.addFile', function() {
            var id = $(this).data('id')
            var url = '{{ route('showFiles', ':id') }}';
            url = url.replace(':id', id)
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')

            setTimeout(function() {
                $('#modal-body').load(url)
            }, 500)
        })


        $(document).on('click', '.like_active', function() {
            var id = $(this).data('id');
            var val = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('likeActiveBasic') }}',
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                    'like_active': val
                },
                success: function(data) {

                    // Check if val is not equal to 0 before executing toastr.success()
                    if (val !== 0) {
                        toastr.success('Success', 'تم التفعيل بنجاح');
                    }
                    else
                    {
                        toastr.warning('Success', 'تم الغاء التفعيل');
                    }
                },
            });
        });

        $(document).on('click', '.view_active', function() {
            var id = $(this).data('id');
            var val = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('viewActiveBasic') }}',
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                    'view_active': val
                },
                success: function(data) {

                    // Check if val is not equal to 0 before executing toastr.success()
                    if (val !== 0) {
                        toastr.success('Success', 'تم التفعيل بنجاح');
                    }
                    else
                    {
                        toastr.warning('Success', 'تم الغاء التفعيل');
                    }
                },
            });
        });


        editScript();
    </script>
@endsection

<!-- fix -->

