@extends('admin.layouts_admin.master')

@section('title')
    الطلاب
@endsection
@section('page_name')
    الطلاب
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                     <div class="">
                        <label><strong>فلتر :</strong></label>
                        <select class="form-control seasonSelect" style="width: 200px" name="season">
                        <option disabled selected>اختر</option>
                        @foreach($seasons as $season)
                                <option value="{{$season->id}}">{{$season->name_ar}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                                <span>
                                    <i class="fe fe-plus"></i>
                                </span> اضافة طالب
                        </button>
                        <button class="btn btn-danger btn-icon text-white userUnvilable">
                                <span>
                                    <i class="fe fe-user"></i>
                                </span> الطلاب الغائبين
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
                                <th class="min-w-50px">اسم الطالب</th>
                                <th class="min-w-50px">صوره الطالب</th>
                                <th class="min-w-50px">تاريخ الميلاد</th>
                                <th class="min-w-50px">الكود</th>
                                <th class="min-w-50px">رقم الهاتف</th>
                                <th class="min-w-50px">الصف الدراسي</th>
                                <th class="min-w-50px">المدينه</th>
                                <th class="min-w-50px">حاله الحساب</th>
                                <th class="min-w-50px">حاله الطالب</th>
                                <th class="min-w-50px">تسجيل الطالب معنا</th>
                                <th class="min-w-50px">رقم هاتف ولي الامر</th>
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
                        <h5 class="modal-title" id="exampleModalLabel">حذف طالب</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل متاكد من حذف<span id="title" class="text-danger"></span></p>
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
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">الطلاب</h5>
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

        <!-- Show User Unvilable -->
        <div class="modal fade" id="showUserUnvilable" data-backdrop="static" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">الطلاب</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body-unvilable" id="modal-body-unvilable">

                    </div>
                </div>
            </div>
        </div>
        <!-- Show User Unvilable -->

        <!-- Renew Subscribe -->
        <div class="modal fade" id="renew" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">تجديد الاشتراك</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-renew" id="modal-renew">

                    </div>
                </div>
            </div>
        </div>
        <!-- Renew Subscribe -->
    </div>


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
                                <label class="form-label" for="exelFile">يرجي ارفاق ملف الطلبه</label>
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
            {data: 'name', name: 'name'},
            {data: 'image', name: 'image'},
            {data: 'birth_date', name: 'birth_date'},
            {data: 'code', name: 'code'},
            {data: 'phone', name: 'phone'},
            {data: 'season_id', name: 'season_id'},
            {data: 'country_id', name: 'country_id',orderable: true, searchable: true},
            {data: 'login_status',custom: 'login_status', orderable: true, searchable: true},
            {data: 'user_status', name: 'user_status', orderable: true, searchable: true},
            {data: 'center', name: 'center',orderable: true, searchable: true},
            {data: 'father_phone', name: 'father_phone'},
            {data: 'user_status_note', name: 'user_status_note'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]

        const ajax = {
            url: "{{ route('users.index') }}",
            data: function (d) {
                d.season = $('select[name="season"]').val();
            }
        };

        $(document).on('change', 'select[name="season"]', function () {
            const table = $('#dataTable').DataTable();

            table.draw(); // Use ajax.reload() to reload the DataTable data
        });


        showData(ajax,columns);

        showUserModal('{{ route('userUnvilable') }}')

        // Delete Using Ajax
        destroyScript('{{ route('users.destroy', ':id') }}');

        // Add Using Ajax
        showAddModal('{{ route('users.create') }}');
        addScript();
        // Add Using Ajax
        showEditModal('{{ route('users.edit', ':id') }}');
        editScript();

        showEdit1('{{ route('subscrView', ':id') }}')



        $(document).ready(function () {
            $('.exportExel').on('click', function () {
                window.location.href = '{{ route('studentsExport')}}'
            })
        })

        $('.dropify').dropify();

        $(document).on("submit", "#importExelForm", function (event) {
            event.preventDefault();
            event.stopImmediatePropagation();

            var formData = new FormData(this);

            $.ajax({
                url: '{{ route('studentsImport') }}',
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
