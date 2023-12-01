<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('lifeExam.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">اسم الامتحان باللغه العربيه</label>
                    <input type="text" class="form-control" name="name_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">اسم الامتحان باللغه الانجليزيه</label>
                    <input type="text" class="form-control" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="date_exam" class="form-control-label">تاريخ اداء الامتحان للطلبه*</label>
                    <input type="date" class="form-control" name="date_exam">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="time_start" class="form-control-label">توقيت البدء للامتحان</label>
                    <input type="time"  class="form-control" name="time_start">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="time_end" class="form-control-label">توقيت انتهاء الامتحان</label>
                    <input type="time"  class="form-control" name="time_end">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">الصف الدراسي</label>
                    <Select name="season_id" class="form-control selectSeason select2">
                        <option selected disabled >اختر الصف الدراسي</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">اختر تيرم معين لهذا الصف*</label>
                    <Select name="term_id" class="form-control selectTerm select2">
                        <option selected disabled>اختر تيرم معين لهذا الصف*</option>
                    </Select>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12 mt-3">
                    <label for="answer_video_file" class="form-control-label">ارفاق ملف اجابه(فيديو)</label>
                    <input type="file" class="form-control" name="answer_video_file">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="time_start" class="form-control-label">عدد الدقائق المتاحه لهذا الامتحان*</label>
                    <input type="number" class="form-control" name="quiz_minute">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="time_end" class="form-control-label">الدرجه الكليه لهذا الامتحان*</label>
                    <input type="number" class="form-control" name="degree">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">ملاحظات*غير مطلوبه</label>
                   <textarea class="form-control" name="note" rows="8"></textarea>
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
    $(document).ready(function() {
        $('.select2').select2();
    });

    $('.dropify').dropify()

</script>

<script>
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
</script>
