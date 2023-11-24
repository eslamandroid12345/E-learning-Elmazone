@extends('admin.layouts_admin.master')

@section('title')
    كوبونات الخصم
@endsection
@section('page_name')
    كوبونات الخصم
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
                                <th class="min-w-50px">كوبون الخصم</th>
                                <th class="min-w-50px">نوع الخصم</th>
                                <th class="min-w-50px">قيمه الخصم</th>
                                <th class="min-w-50px">تاريخ بدايه صلاحيه الخصم</th>
                                <th class="min-w-50px">تاريخ نهايه صلاحيه الخصم</th>
                                <th class="min-w-50px">متاح</th>
                                <th class="min-w-50px">اجمالي الطلاب مستخدمي الكوبون</th>
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
                        <p>هل انت متاكد من عملية الحذف<span id="title" class="text-danger"></span></p>
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
                        <h5 class="modal-title" id="example-Modal3">كوبون</h5>
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
{{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script> --}}
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'coupon', name: 'coupon'},
            {data: 'discount_type', name: 'discount_type'},
            {data: 'discount_amount', name: 'discount_amount'},
            {data: 'valid_from', name: 'valid_from'},
            {data: 'valid_to', name: 'valid_to	'},
            {data: 'is_enabled', name: 'is_enabled'},
            {data: 'total_usage', name: 'total_usage'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];

        showData('{{ route('discount_coupons.index') }}', columns);
        // Delete Using Ajax
        destroyScript('{{ route('discount_coupons.destroy', ':id') }}');
        // Add Using Ajax
        showAddModal('{{route('discount_coupons.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route('discount_coupons.edit',':id')}}');
        editScript();


    </script>
@endsection

