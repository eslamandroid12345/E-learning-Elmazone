<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('addItems') }}">
        @csrf
        <input type="hidden" name="from_id" value="{{ $id }}" />
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="section_name_ar" class="form-control-label">عنوان العنصر باللغه العربيه</label>
                    <input type="text" class="form-control" name="title_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="section_name_en" class="form-control-label">عنوان العنصر باللغه الانجليزيه</label>
                    <input type="text" class="form-control" name="title_en">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="head">شهر</label>
                    <select name="month" class="form-control select2">
                        <option value="">اختر شهر</option>
                        <?php for ($i = 1; $i <= 12; $i++){
                            echo '<option  value="' . $i . '">' . date( 'F', strtotime( "$i/12/10" ) ) . '</option>';
                        }?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">جميع الفصول</label>
                    <Select name="subject_class_id" id="subject_id" class="form-control subject_id select2">
                        <option selected disabled >اختر فصل معين</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">جميع الدروس</label>
                    <select name="lesson_id" id="lesson_id" class="form-control lesson_id select2">
                        <option value="" >اختر درس</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">نوع الملف المرفق</label>
                    <Select name="file_type" id="file_type" class="form-control file_type select2">
                        <option selected disabled>اختار النوع</option>
                        <option value="pdf">ملف ورقي</option>
                        <option value="video">فيديو</option>
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="file" class="form-control-label">ارفق الملف الورقي او الفيديو لهذا العنصر*</label>
                   <input type="file" name="file" class="form-control"/>
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
