<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('videoBasic.update', $videoBasic->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الاسم باللغة العربية</label>
                    <input type="text" class="form-control" value="{{ $videoBasic->name_ar }}" name="name_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">الاسم باللغة الانجليزية</label>
                    <input type="text" class="form-control" value="{{ $videoBasic->name_en }}" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="head">لون الخلفية</label>
                    <input type="color" class="form-control" value="{{ $videoBasic->background_color }}" name="background_color">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="time" class="form-control-label">المدة</label>
                    <input type="text" class="form-control" value="{{ $videoBasic->time }}" name="time">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="">لينك الفيديو :</label>
                    <input type="file" name="video_link" class="dropify"
                           data-default-file="{{ asset($videoBasic->video_link) }}"/>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="video_link" class="form-control-label">مسار فيديو مثال (Youtube)*</label>
                    <input type="text" name="youtube_link" value="{{$videoBasic->youtube_link}}" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
<!-- fix -->
