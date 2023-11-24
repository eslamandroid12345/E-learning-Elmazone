@extends('admin.layouts_admin.master')

@section('title')
    الردود على التعليقات
@endsection
@section('page_name')
    الرد على التعليقات
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">التعليق</th>
                                <th class="min-w-50px">الصوت</th>
                                <th class="min-w-50px">الصورة</th>
                                <th class="min-w-50px">النوع</th>
                                <th class="min-w-50px">الفيديو</th>
                                <th class="min-w-50px">المستخدم</th>
                                <th class="min-w-50px rounded-end">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($replyComments as $data)
                                <tr>
                                    <td class="min-w-25px">{{ $data->id }}</td>
                                    <td class="min-w-25px">{{ $data->comment }}</td>
                                    <td class="min-w-25px">{{ $data->audio }}</td>
                                    <td class="min-w-25px">{{ $data->image }}</td>
                                    <td class="min-w-25px">{{ $data->type }}</td>
                                    <td class="min-w-25px">{{ $data->video }}</td>
                                    <td class="min-w-25px">{{ $data->user_id }}</td>
                                    <td class="min-w-25px">
                                        <button class="btn btn-pill btn-danger-light" data-toggle="modal"
                                                data-target="#delete_modal{{ $data->id }}"
                                                data-id="" data-title="">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        @foreach($replyComments as $item)
            <div class="modal fade" id="delete_modal{{ $item->id }}" tabindex="-1" role="dialog"

                 aria-labelledby="exampleModalLabel"
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
                            <form id="addForm" class="addForm" method="POST"
                                  action="{{ route('replyCommentDelete', $item->id) }}">
                                @csrf
                                <input id="delete_id" name="id" type="hidden">
                                <p>حذف<span id="title" class="text-danger">{{ $item->comment }}</span></p>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"
                                            id="dismiss_delete_modal">
                                        اغلاق
                                    </button>
                                    <button type="submit" class="btn btn-danger">حذف</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- MODAL CLOSED -->

    </div>

@endsection
@section('ajaxCalls')
    <script>
        // Delete Using Ajax
        destroyScript('{{ route('replyCommentDelete', ':id') }}');
    </script>
@endsection


