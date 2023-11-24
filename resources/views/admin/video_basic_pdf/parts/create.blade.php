<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('videoBasicPdf.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الاسم باللغة العربية</label>
                    <input type="text" class="form-control" name="name_ar" required>
                </div>
                <div class="col-md-6">
                    <label for="name_en" class="form-control-label">الاسم باللغة الانجليزية</label>
                    <input type="text" class="form-control" name="name_en" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="">النوع :</label>
                    <select name="type" class="form-control type_choose" required>
                        <option style="text-align: center" value="video_basic">فيديو تأسيسي</option>
                        <option style="text-align: center" value="video_resource">مصدر الفيديو</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الفيديو التأسيسي</label>
                    <Select name="video_basic_id" class="form-control video_basics" disabled>
                        <option selected disabled style="text-align: center">اختار الفيديو</option>
                        @foreach ($data['video_basics'] as $video_basics)
                            <option value="{{ $video_basics->id }}" style="text-align: center">{{ $video_basics->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">مصدر الفيديو</label>
                    <Select name="video_resource_id" class="form-control Video_resources" disabled>
                        <option selected disabled style="text-align: center">اختار الفيديو</option>
                        @foreach ($data['Video_resources'] as $Video_resource)
                            <option value="{{ $Video_resource->id }}" style="text-align: center">
                                {{ $Video_resource->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div><div class="row">
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">ملفات ورقية</label>
                    <input type="file" class="dropify" name="files[]" multiple="multiple"
                           data-default-file=""
                           accept="image/png,image/webp , image/gif, image/jpeg,image/jpg"/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()

    $(document).ready(function () {
        $('.type_choose').on('change', function() {
            var value = $('.type_choose option:selected').val();
            if('video_resource' === value) {
                $('.video_basics').prop('disabled', true);
                $('.Video_resources').prop('disabled', false);
            }
            else {
                $('.video_basics').prop('disabled', false);
                $('.Video_resources').prop('disabled', true);
            }
        })
    });

</script>
<!-- fix -->

