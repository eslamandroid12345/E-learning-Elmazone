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


            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="name_ar" class="form-control-label">الصف الدراسي</label>
                    <Select name="season_id" id="season_id" class="form-control select2">

                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}" {{$videosPart->season_id == $season->id ? 'selected' : ''}}>{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>


                <div class="col-md-6 mt-3">
                    <label for="name_ar" class="form-control-label">اختر تيرم</label>
                    <Select name="term_id"  id="term_id" class="form-control select2">
                        @foreach($terms as $term)
                            <option value="{{$term->id}}" {{$videosPart->term_id == $term->id ? 'selected' : ''}}>{{$term->name_ar}}</option>
                        @endforeach

                    </Select>
                </div>


                <div class="col-md-6 mt-3">
                    <label for="name_ar" class="form-control-label">اختر فصل معين</label>
                    <select name="subject_class_id"  id="subject_class_id" class="form-control select2">
                        <option disabled>اختر فصل معين</option>
                        <option selected>{{$lesson->subject_class->name_ar}}</option>

                    </select>
                </div>

                <div class="col-md-6 mt-3">
                    <label for="name_ar" class="form-control-label">اختر درس معين</label>
                    <select name="lesson_id"  id="lesson_id" class="form-control select2">
                        <option disabled>اختر درس معين</option>
                        <option value="{{$lesson->id}}" selected>{{$lesson->name_ar}}</option>

                    </select>
                </div>


            </div>

            <div class="row mb-3">


                <div class="col-md-12 mt-3">
                    <label for="head">شهر</label>
                    <select name="month" class="form-control" id="signup_birth_month" >
                        <option value="">اختر شهر</option>
                        @for ($i = 1; $i <= 12; $i++){
                            <option value="{{$i}}" {{$videosPart->month == $i ? 'selected' : ''}}> {{date( 'F', strtotime( "$i/12/10" ) )}}</option>
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


<script>
    $('.dropify').dropify()

    $(document).ready(function () {
        $('select[name="season_id"]').on('change', function () {
            var season_id = $(this).val();
            if (season_id) {
                $.ajax({
                    url: "{{ URL::to('terms/season/') }}/" + season_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('select[name="term_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="term_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });



    $(document).ready(function () {
        $('select[name="term_id"]').on('click', function () {

            let season_id = $("#season_id").val();
            let term_id = $("#term_id").val();

            if (term_id) {
                $.ajax({
                    url: "{{ URL::to('getAllSubjectClassesBySeasonAndTerm') }}",
                    type: "GET",
                    data: {
                        "season_id" : season_id,
                        "term_id" : term_id,
                    },
                    dataType: "json",
                    success: function (data) {
                        $('select[name="subject_class_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="subject_class_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });



    $(document).ready(function () {
        $('select[name="subject_class_id"]').on('click', function () {

            let subject_class_id = $("#subject_class_id").val();

            if (subject_class_id) {
                $.ajax({
                    url: "{{ URL::to('getAllLessonsBySubjectClass') }}",
                    type: "GET",
                    data: {
                        "subject_class_id" : subject_class_id,
                    },
                    dataType: "json",
                    success: function (data) {
                        $('select[name="lesson_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="lesson_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

</script>
<!-- fix -->
