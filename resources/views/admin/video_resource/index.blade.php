@extends('admin.layouts_admin.master')

@section('title')
    مصادر الفيديوهات
@endsection
@section('page_name')
    مصادر الفيديوهات
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <form action="" class="d-flex" method="get">
                        <div class="row">
{{--                            <div class="col-5">--}}
{{--                                <label><strong>فلتر :</strong></label>--}}
{{--                                <select id='type' class="form-control" style="width: 200px">--}}
{{--                                    @foreach(DB::table('seasons')->get() as $season)--}}
{{--                                        <option value="{{ $season->id }}">{{ $season->name_ar }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="col-5">--}}
{{--                                <label><strong>فلتر :</strong></label>--}}
{{--                                <select id='type' class="form-control" style="width: 200px">--}}
{{--                                    @foreach(DB::table('terms')->get() as $terms)--}}
{{--                                        <option value="{{ $terms->id }}">{{ $terms->name_ar }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
                        </div>
                    </form>
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
                                <th class="min-w-50px">الصورة</th>
                                <th class="min-w-50px">الاسم</th>
                                <th class="min-w-50px">الصف</th>
                                <th class="min-w-50px">الترم</th>
                                <th class="min-w-50px">لون الخلفية</th>
                                <th class="min-w-50px">لينك الفيديو</th>
                                <th class="min-w-50px">النوع</th>
                                <th class="min-w-50px">الوقت</th>
                                <th class="min-w-50px">لينك الملف الورقي</th>
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
                        <h5 class="modal-title" id="example-Modal3">مصدر الفيديو</h5>
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
            {data: 'image', name: 'image'},
            {data: 'name_ar', name: 'name_ar'},
            {data: 'season_id', name: 'season_id'},
            {data: 'term_id', name: 'term_id'},
            {data: 'background_color', name: 'background_color'},
            {data: 'video_link', name: 'video_link'},
            {data: 'type', name: 'type'},
            {data: 'time', name: 'time'},
            {data: 'pdf_file', name: 'pdf_file'},
            {data: 'like_active', name: 'like_active'},
            {data: 'view_active', name: 'view_active'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]

        var ajax = {
            url: "{{ route('videoResource.index') }}",
            data: function (d) {
                d.type = $('#type').val()
            }
        };

        showData(ajax, columns);
        // Delete Using Ajax
        destroyScript('{{route('videoResource.destroy',':id')}}');
        // Add Using Ajax
        showAddModal('{{route('videoResource.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route('videoResource.edit',':id')}}');
        editScript();


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
                url: '{{ route('likeActiveResource') }}',
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
                url: '{{ route('viewActiveResource') }}',
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


    </script>
@endsection

<!-- fix -->

