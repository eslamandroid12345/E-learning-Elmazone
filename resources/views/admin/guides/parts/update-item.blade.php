<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('updateItem', $guide->id) }}">
        @csrf

        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="section_name_ar" class="form-control-label">عنوان العنصر باللغه العربيه</label>
                    <input type="text" class="form-control" value="{{ $guide->title_ar }}" name="title_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="section_name_en" class="form-control-label">عنوان العنصر باللغه الانجليزيه</label>
                    <input type="text" class="form-control" value="{{ $guide->title_en }}" name="title_en">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="head">شهر</label>
                    <select name="month" class="form-control select2" id="signup_birth_month">
                        <option value="">اختر شهر</option>
                        @for ($i = 1; $i <= 12; $i++){
                        <option  value="{{$i}}" {{ $guide->month == $i ? 'selected' : ''}}> {{date( 'F', strtotime( "$i/12/10" ) )}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">جميع الفصول</label>
                    <select name="subject_class_id" id="subject_id" class="form-control subject_id select2">
                        <option selected disabled >اختر فصل معين</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $guide->subject_class_id == $subject->id ? 'selected' : '' }}>{{ $subject->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">جميع الدروس</label>
                    <select name="lesson_id" id="lesson_id" class="form-control lesson_id select2">
                        <option value="{{ $guide->lesson_id }}">{{ $guide->lesson->name_ar ?? '' }}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">نوع الملف المرفق</label>
                    <Select name="file_type" id="file_type" class="form-control file_type select2">
                        <option selected disabled >اختار النوع</option>
                        <option  value="pdf" {{ $guide->file_type == 'pdf' ? 'selected' : '' }}>ملف ورقي</option>
                        <option  value="video" {{ $guide->file_type == 'video' ? 'selected' : '' }}>فيديو</option>
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="file" class="form-control-label">ارفق الملف الورقي او الفيديو لهذا العنصر*</label>
                   <input type="file" name="file" class="form-control" />
                </div>
                <div class="col-md-12 mt-3">
                    <label for="answer_pdf_file" class="form-control-label">ملف الاجابة (ملف ورقي)</label>
                    <input type="file" name="answer_pdf_file" class="form-control" />
                </div>
                <div class="col-md-12 mt-3">
                    <label for="answer_video_file" class="form-control-label">ملف الاجابة (فيديو)</label>
                    <input type="file" name="answer_video_file" class="form-control" />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="updateButton">تحديث</button>
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
        $('.subject_id').on('change', function () {
            let season = $(this).val();
            $.ajax({
                url: '{{ route("lessonSort")}}',
                method: 'GET',
                data: {
                    'id': season,
                }, success: function (data) {
                    $('.lesson_id').html(data);
                    console.log(data);
                }
            })
        })
    });

</script>
