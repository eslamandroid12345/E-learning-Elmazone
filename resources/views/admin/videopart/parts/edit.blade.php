<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('videosParts.update', $videosPart->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $videosPart->id }}" name="id">

        <div class="form-group">
            <input type="hidden" name="ordered" value="" />
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">الاسم باللغة العربية</label>
                    <input type="text" class="form-control" name="name_ar" value="{{$videosPart->name_ar}}">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">الاسم باللغة الانجليزية</label>
                    <input type="text" class="form-control" name="name_en" value="{{$videosPart->name_en}}">
                </div>

                <div class="col-md-12 mt-3"><label class="labels">صوره خلفيه الفيديو</label>
                    <input type="file" class="form-control" name="background_image">
                </div>
            </div>
            <div class="row mb-3">


                <div class="col-md-12 mt-3">
                    <label for="head">شهر</label>
                    <select name="month" class="form-control" id="signup_birth_month" >
                        <option value="" style="text-align: center">اختر شهر</option>
                        @for ($i = 1; $i <= 12; $i++){
                            <option style="text-align: center" value="{{$i}}" {{$videosPart->month == $i ? 'selected' : ''}}> {{date( 'F', strtotime( "$i/12/10" ) )}}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="row mb-3">


                <div class="col-md-12 video_link">
                    <label for="video_link" class="form-control-label">ارفاق ملف *</label>
                    <input type="file" name="link" class="form-control"
                           data-default-file=""/>
                </div>

                <div class="col-md-12 video_link mt-3">
                    <label for="video_link" class="form-control-label">مسار فيديو مثال (Youtube)*</label>
                    <input type="text" name="youtube_link" class="form-control" value="{{$videosPart->youtube_link}}"/>
                </div>

                <div class="col-md-12 video_date mt-3">
                    <label for="video_date" class="form-control-label">وقت الفيديو</label>
                    <input type="text" id="date_video" class="form-control" name="video_time" value="{{$videosPart->video_time}}">
                </div>
            </div>
            <div class="row">

                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">ملاحظة</label>
                    <textarea class="form-control" name="note" rows="10"></textarea>
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
