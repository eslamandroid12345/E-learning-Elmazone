<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('modifyFiles', $id) }}">
        @csrf
        {{--        @method('PUT')--}}
        <input type="hidden" value="{{ $id }}" name="id">
        <div class="form-group">
            <input type="hidden" name="ordered" value=""/>
            <div class="row mt-3">
                <div class="col-md-12 mt-3">
                    <label for="name_ar">الاسم بالعربية</label>
                    <input type="text" class="form-control" id="name_ar" name="name_ar" placeholder="الاسم بالعربي">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" id="name_en" name="name_en" placeholder="الاسم بالانجليزية">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 video_link">
                    <label for="video_link" class="form-control-label">الملف</label>
                    <input type="file" name="file_link" value="" class="form-control"
                           data-default-file="" />
                </div>
                <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label">نوع الملف</label>
                    <select class="form-control type" id="type" name="type">
                        <option value="pdf">ملف ورقي</option>
                        <option value="audio">صوت</option>
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="background_color" class="form-control-label">لون الخلفية</label>
                    <input type="color" id="background_color" class="form-control" name="background_color" value="">
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="updateButton">اضافة</button>

        </div>
    </form>
    <div class="row">
        <div class="card">
            <div class="card-header">
                <label>الملحقات السابقه</label>
            </div>
            <div class="card-body">
                <table class="table styled-table">

                    <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th>#</th>
                        <th>الاسم</th>
                        <th>النوع</th>
                        <th>العمليات</th>
                    </tr>
                    </thead>
                    @foreach($files as $file)
                        <tbody>
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->name_ar }}</td>
                        <td>{{ $file->file_type }}</td>
                        <td>
                            <button class="btn btn-sm btn-danger DeleteFiles" data-id="{{ $file->id }}">
                                حذف
                            </button>
                        </td>
                        </tbody>
                    @endforeach
                </table>
            </div>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
    </div>
</div>
<script>
    $('.dropify').dropify()

    $(document).on('click', '.DeleteFiles', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ route('deleteFiles') }}',
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function (data) {
                toastr.success(data.status);
                location.reload();
            }
        })
    })
</script>
<!-- fix -->
