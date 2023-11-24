@extends('admin.layouts_admin.master')

@section('title')
    {{ $setting->title ?? '' }} | سجلات الادمن
@endsection
@section('page_name') سجلات الادمن  @endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> السجلات {{ $setting->title ?? '' }}</h3>
                    <div class="form-group d-flex">
                        <label class="form-label ml-2">From</label>
                        <input class="form-control fromDate" value="{{ $startDate }}" name="from" type="date">
                        <label class="form-label ml-2 mr-4">To</label>
                        <input class="form-control toDate" value="{{ $endDate }}" name="to" type="date">
                    </div>
                    <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal_all">
                        حذف المحدد <i class="fas fa-trash"></i>
                    </button>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">ID</th>
                                <th class="min-w-50px">اسم الادمن</th>
                                <th class="min-w-125px">العملية</th>
                                <th class="min-w-125px">الدور</th>
                                <th class="min-w-125px">تاريخ</th>
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

        <!--Delete All MODAL -->
        <div class="modal fade" id="delete_modal_all" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <p>هل انت متأكد من حذف جميع البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_deleteAll_modal">
                            اغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_all_btn">حذف !</button>
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
                        <h5 class="modal-title" id="example-Modal3">بيانات المشرف</h5>
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
            {data: 'admin_id', name: 'admin_id'},
            {data: 'action', name: 'action'},
            {data: 'role', name: 'role'},
            {data: 'created_at', name: 'created_at'},
            {data: 'button', name: 'button', orderable: false, searchable: false},
        ]

        var ajax = {
            url: "{{ route('adminLog') }}",
            data: function (d) {
                d.to = $('.toDate').val()
                d.from = $('.fromDate').val()
            }
        };

        $(document).on('change','.toDate',function (){
            var table = $('#dataTable').DataTable(); // Get the DataTable instance
            table.draw(); // Reload the data
        })

        $(document).on('change','.fromDate',function (){
            var table = $('#dataTable').DataTable(); // Get the DataTable instance
            table.draw(); // Reload the data
        })




        showData(ajax, columns);

        // Delete Using Ajax
        deleteScript('{{ route('adminLogDelete') }}');


        // Delete Using Ajax
        function deleteAllScript(routeOfDelete) {
            $(document).ready(function() {
                //Show data in the delete form
                $('#delete_modal_all').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget)
                    var id = button.data('id')
                    var title = button.data('title')
                    var modal = $(this)
                    modal.find('.modal-body #delete_id').val(id);
                    modal.find('.modal-body #title').text(title);
                });
            });


            $(document).on('click', '#delete_all_btn', function(event) {
                var id = $("#delete_id").val();
                $.ajax({
                    type: 'POST',
                    url: routeOfDelete,
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id,
                        'from' : $('.fromDate').val(),
                        'to' : $('.toDate').val(),
                    },
                    success: function(data) {
                        if (data.status === 200) {
                            $('#delete_modal_all').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            toastr.success(data.message)
                        } else {
                            $("#dismiss_deleteAll_modal")[0].click();
                            toastr.error(data.message)
                        }
                    }
                });
            });
        }

        deleteAllScript('{{ route('adminLogDeleteAll') }}');


    </script>
@endsection
