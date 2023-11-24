<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" enctype="multipart/form-data" action="{{ route('videoResource.update', $videoResource->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الاسم باللغة العربية</label>
                    <input type="text" class="form-control" value="{{ $videoResource->name_ar }}" name="name_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">الاسم باللغة الانجليزية</label>
                    <input type="text" class="form-control" value="{{ $videoResource->name_en }}" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الصف</option>
                        @foreach ($data['seasons'] as $season)
                            <option value="{{ $season->id }}"
                                    {{ $videoResource->season_id == $season->id ? 'selected' : '' }}
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الترم</label>
                    <Select name="term_id" class="form-control" required>
                        <option selected disabled style="text-align: center">اختار الترم</option>
                        @foreach ($data['terms'] as $term)
                            <option value="{{ $term->id }}"
                                    {{ $videoResource->term_id == $term->id ? 'selected' : '' }} style="text-align: center">
                                {{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="head">لون الخلفية</label>
                    <input type="color" class="form-control" name="background_color"
                           value="{{ $videoResource->background_color }}" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="time" class="form-control-label" disabled="">الوقت</label>
                    <input type="text" class="form-control time_video" value="{{ $videoResource->time }}" name="time">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">النوع :</label>
                    <select class="form-control" name="type" id="type_choose">
                        <option style="text-align: center" value="video" {{ $videoResource->type == 'video' ? 'selected' : '' }}>فيديو</option>
                        <option style="text-align: center" value="pdf" {{ $videoResource->type == 'pdf' ? 'selected' : '' }}>ملف ورقي</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="">لينك الفيديو :</label>
                    <input type="file" name="video_link" class="video form-control" disabled
                           data-default-file="{{ asset($videoResource->video_link) }}"/>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="video_link" class="form-control-label">مسار فيديو مثال (Youtube)*</label>
                    <input type="text" name="youtube_link" value="{{$videoResource->youtube_link}}" class="form-control"/>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">لينك الملفات الورقية :</label>
                    <input type="file" name="pdf_file" class="pdf form-control" disabled
                           data-default-file="{{ asset($videoResource->pdf_file) }}"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mt-3">
                <label for="">الصورة :</label>
                <input type="file" name="image" class="dropify"
                       data-default-file="{{ asset('videos_resources/images/' . $videoResource->image) }}"/>
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
        $('#type_choose').on('change', function() {
            var value = $('#type_choose option:selected').val();
            if('video' === value) {
                $('.video').prop('disabled', false);
                $('.pdf').prop('disabled', true);
                $('.time_video').prop('disabled', false);
            }
            else {
                $('.pdf').prop('disabled', false);
                $('.video').prop('disabled', true);
                $('.time_video').prop('disabled', true);
            }
        })
    });



</script>
<!-- fix -->
