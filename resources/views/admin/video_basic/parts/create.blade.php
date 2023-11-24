<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('videoBasic.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الاسم باللغة العربية</label>
                    <input type="text" class="form-control" name="name_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">الاسم باللغة الانجليزية</label>
                    <input type="text" class="form-control" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="head">لون الخلفية</label>
                    <input type="color" class="form-control" name="background_color"
                           value="#D7EAF9">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="time" class="form-control-label">توقيت الفيديو</label>
                    <input type="text" class="form-control" name="time">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="">لينك الفيديو :</label>
                    <input type="file" name="video_link" class="dropify"
                           data-default-file=""/>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <label for="video_link" class="form-control-label">مسار فيديو مثال (Youtube)*</label>
                <input type="text" name="youtube_link" class="form-control"/>
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
</script>
<!-- fix -->

