@extends('admin.layouts_admin.master')
@section('title')
    {{ $setting->title ?? '' }} معلومات عن الاستاذ
@endsection

@section('page_name')
    معلومات عن الاستاذ
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user text-center">
                        <div class="wideget-user-desc">
                            <div class="wideget-user-img">
                                <img class=""
                                     src="{{ $setting->teacher_image != null ? asset($setting->teacher_image) : asset('default/avatar2.jfif') }}"
                                     alt="img">
                            </div>
                            <div class="user-wrap">
                                <h4 class="mb-1 text-capitalize">{{ $setting->teacher_name_ar }}</h4>
                                <h6 class="badge badge-primary-gradient">{{ $setting->department_ar }}</h6>
                                <h6 class="text-muted mb-4"></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse"
                            data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        اضافة معلومة جديدة
                    </button>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="card-body">
                        <form action="{{ route('qualification.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="type" class="form-label">النوع</label>
                                        <select name="type" class="form-control" id="type">
                                            <option class="form-control" value="qualifications">المؤهلات</option>
                                            <option class="form-control" value="experience">الخبرات</option>
                                            <option class="form-control" value="skills">المهارات</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="title_ar" class="form-label">التاريخ</label>
                                        <input type="date" class="form-control" name="year">
                                    </div>
                                    <div class="col-6">
                                        <label for="title_ar" class="form-label">الاسم بالعربي</label>
                                        <input type="text" class="form-control" name="title_ar">
                                    </div>
                                    <div class="col-6">
                                        <label for="title_en" class="form-label">الاسم بالانجليزية</label>
                                        <input type="text" class="form-control" name="title_en">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="description_ar" class="form-label">التفاصيل بالعربي</label>
                                        <textarea type="text" rows="5" class="form-control"
                                                  name="description_ar"></textarea>
                                    </div>
                                    <div class="col-6">
                                        <label for="description_en" class="form-label">التفاصيل بالانجليزية</label>
                                        <textarea type="text" rows="5" class="form-control"
                                                  name="description_en"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">
                                    اضافة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-1" class="active show" data-toggle="tab">المؤهلات</a></li>
                                <li class=""><a href="#tab-2" class="show" data-toggle="tab">الخبرات</a></li>
                                <li class=""><a href="#tab-3" class="show" data-toggle="tab">المهارات</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="table-responsive">
                                    @if($qualifications->count() > 0)

                                        <div class="py-2 mt-3">
                                            <h3 class="font-size-15 col-6 fw-bold">معلومات المؤهلات</h3>
                                        </div>
                                        <div class="styled-table">
                                            <table class="styled-table">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th style="width: 70px;">#</th>
                                                    <th>الاسم</th>
                                                    <th class="text-end">التفاصيل</th>
                                                    <th class="text-end">التاريخ</th>
                                                    <th class="text-end">العمليات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($qualifications as $qualification)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $qualification->title_ar }}</td>
                                                        <td class="text-end">{{ $qualification->description_ar }}</td>
                                                        <td>{{ $qualification->year }}</td>
                                                        <td>
                                                            <button type="button" data-id="{{ $qualification->id }}"
                                                                    class="btn btn-pill btn-info-light editBtn"><i
                                                                    class="fa fa-edit"></i></button>
                                                            <button class="btn btn-pill btn-danger-light"
                                                                    data-toggle="modal" data-target="#delete_modal"
                                                                    data-id="{{ $qualification->id }}"
                                                                    data-title="{{ $qualification->title_ar }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>
                                    @else
                                        <h5>لا يوجد بيانات حاليا</h5>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-2">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="table-responsive ">
                                    @if($experiences->count() > 0)

                                        <div class="py-2 mt-3">
                                            <h3 class="font-size-15 col-6 fw-bold">معلومات الخبرات</h3>
                                        </div>
                                        <div class="styled-table">
                                            <table class="styled-table">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th style="width: 70px;">#</th>
                                                    <th>الاسم</th>
                                                    <th class="text-end">التفاصيل</th>
                                                    <th class="text-end">التاريخ</th>
                                                    <th class="text-end">العمليات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($experiences as $experience)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $experience->title_ar }}</td>
                                                        <td class="text-end">{{ $experience->description_ar }}</td>
                                                        <td>{{ $experience->year }}</td>
                                                        <td>
                                                            <button type="button" data-id="{{ $experience->id }}"
                                                                    class="btn btn-pill btn-info-light editBtn"><i
                                                                    class="fa fa-edit"></i></button>
                                                            <button class="btn btn-pill btn-danger-light"
                                                                    data-toggle="modal" data-target="#delete_modal"
                                                                    data-id="{{ $experience->id }}"
                                                                    data-title="{{ $experience->title_ar }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>
                                    @else
                                        <h5>لا يوجد بيانات حاليا</h5>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-3">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="table-responsive ">
                                    @if($skills->count() > 0)
                                        <div class="py-2 mt-3">
                                            <h3 class="font-size-15 col-6 fw-bold">معلومات المهارات</h3>
                                        </div>
                                        <div class="styled-table">
                                            <table class="styled-table">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th style="width: 70px;">#</th>
                                                    <th>الاسم</th>
                                                    <th class="text-end">التفاصيل</th>
                                                    <th class="text-end">التاريخ</th>
                                                    <th class="text-end">العمليات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($skills as $skill)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $skill->title_ar }}</td>
                                                        <td class="text-end">{{ $skill->description_ar }}</td>
                                                        <td>{{ $skill->year }}</td>
                                                        <td>
                                                            <button type="button" data-id="{{ $skill->id }}"
                                                                    class="btn btn-pill btn-info-light editBtn"><i
                                                                    class="fa fa-edit"></i></button>
                                                            <button class="btn btn-pill btn-danger-light"
                                                                    data-toggle="modal" data-target="#delete_modal"
                                                                    data-id="{{ $skill->id }}"
                                                                    data-title="{{ $skill->title_ar }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>
                                    @else
                                        <h5>لا يوجد بيانات حاليا</h5>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>

    <!--Delete MODAL -->
    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="delete_id" name="id" type="hidden">
                    <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                        اغلاق
                    </button>
                    <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
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
                    <h5 class="modal-title" id="example-Modal3"> تحديث البيانات</h5>
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




    @include('admin.layouts_admin.myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        // Add Using Ajax
        showEditModal('{{ route('qualification.edit', ':id') }}');

        function editScriptModl() {
            $(document).on('submit', 'Form#updateForm', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $('#updateForm').attr('action');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    beforeSend: function () {
                        $('#updateButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                            ' ></span> <span style="margin-left: 4px;">انتظر ..</span>').attr(
                            'disabled', true);
                    },
                    success: function (data) {
                        $('#updateButton').html(`تعديل`).attr('disabled', false);
                        if (data.status == 200) {
                            $('#dataTable').DataTable().ajax.reload();
                            toastr.success('تم التعديل بنجاح');
                            setTimeout(function () {
                                location.reload();
                            }, 2000)
                        } else
                            toastr.error('هناك خطأ ما ..');

                        $('#editOrCreate').modal('hide')
                    },
                    error: function (data) {
                        if (data.status === 500) {
                            toastr.error('هناك خطأ ما ..');
                        } else if (data.status === 422) {
                            var errors = $.parseJSON(data.responseText);
                            $.each(errors, function (key, value) {
                                if ($.isPlainObject(value)) {
                                    $.each(value, function (key, value) {
                                        toastr.error(value, 'خطأ');
                                    });
                                }
                            });
                        } else
                            toastr.error('هناك خطأ ما ..');
                        $('#updateButton').html(`تعديل`).attr('disabled', false);
                    }, //end error method

                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        }

        editScriptModl();


        // Delete Using Ajax
        function deleteScriptModal(routeOfDelete) {
            $(document).ready(function () {
                //Show data in the delete form
                $('#delete_modal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget)
                    var id = button.data('id')
                    var title = button.data('title')
                    var modal = $(this)
                    modal.find('.modal-body #delete_id').val(id);
                    modal.find('.modal-body #title').text(title);
                });
            });
            $(document).on('click', '#delete_btn', function (event) {
                var id = $("#delete_id").val();
                $.ajax({
                    type: 'POST',
                    url: routeOfDelete,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id,
                    },
                    success: function (data) {
                        if (data.status === 200) {
                            $("#dismiss_delete_modal")[0].click();
                            toastr.success(data.message)
                            setTimeout(function () {
                                location.reload();
                            }, 2000)
                        } else {
                            $("#dismiss_delete_modal")[0].click();
                            toastr.error(data.message)
                            setTimeout(function () {
                                location.reload();
                            }, 2000)
                        }
                    }
                });
            });
        }

        // Delete Using Ajax
        deleteScriptModal('{{ route('qualificationDelete') }}');
    </script>
@endsection
