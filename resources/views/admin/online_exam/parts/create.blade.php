<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('onlineExam.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">نوع الامتحان</label>
                    <select name="exam_type" class="form-control select2" id="exam_type">
                        <option value="" selected disabled>اختر نوع الامتحان</option>
                        <option value="pdf">PDF</option>
                        <option value="online">Online</option>
                    </select>
                </div>
            </div>


            <div class="row d-none pdfType">
                <div class="col-md-6 mt-3">
                    <label for="pdf_num_questions" class="form-control-label">عدد اسئله الملف الورقي</label>
                    <input type="number" class="form-control" name="pdf_num_questions">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="pdf_file_upload" class="form-control-label">ملف الpdf</label>
                    <input type="file" class="form-control" name="pdf_file_upload">
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="answer_pdf_file" class="form-control-label">ملف الاجابات pdf</label>
                    <input type="file" class="form-control" name="answer_pdf_file">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="answer_video_file" class="form-control-label">الاجابات فيديو</label>
                    <input type="file" class="form-control" name="answer_video_file">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="answer_video_youtube" class="form-control-label">الاجابه فيديو*يوتيوب</label>
                    <input type="text" class="form-control" name="answer_video_youtube">
                </div>

                <input type="hidden" class="form-control" name="answer_video_is_youtube">

            </div>


            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="name_ar" class="form-control-label">الدرجة</label>
                    <input type="number" class="form-control" name="degree">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="date_exam" class="form-control-label">تاريخ اضافه الامتحان</label>
                    <input type="date" class="form-control" name="date_exam">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="name_en" class="form-control-label"> وقت الامتحان</label>
                    <input type="number" class="form-control" name="quize_minute"
                           placeholder="الوقت بالدقائق">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="name_en" class="form-control-label"> عدد المحاولات </label>
                    <input type="number" class="form-control" value="" name="trying_number"
                           placeholder="عدد المحاولات">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="name_ar" class="form-control-label">الاسم بالعربي</label>
                    <input type="text" class="form-control" name="name_ar" style="text-align: center">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" name="name_en" style="text-align: center">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="note" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control selectSeason select2">
                        <option selected disabled >اختر الصف</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}"
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6 mt-3">
                    <label for="note" class="form-control-label">تيرم</label>
                    <Select name="term_id" class="form-control selectTerm select2">
                        <option selected disabled>اختر تيرم</option>
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label">النوع</label>
                    <Select name="examable_type" id="type" class="form-control type_choose select2">
                        <option selected disabled style="text-align: center">اختار النوع</option>
                        <option value="lesson" style="text-align: center">درس</option>
                        <option value="class" style="text-align: center">فصل</option>
                        <option value="video" style="text-align: center">الفيديو</option>
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">لون الخلفية</label>
                    <input type="color" class="form-control" value="" name="background_color">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label"> عنوان النصيحه </label>
                    <input type="text" class="form-control" name="title_result" style="text-align: center">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">صورة النصيحة</label>
                    <input type="file" class="form-control" name="image_result" style="text-align: center">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label"> وصف النصيحة</label>
                    <textarea class="form-control" name="description_result" rows="4"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="lesson" class="form-control-label typeName">الدرس</label>
                    <Select name="examable_id" class="form-control type_ajax_choose select2">
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label class="control-label">التعليمات بالعربية</label>
                    <div class="form-group itemItems1">
                        <input type="text" name="instruction_ar[]" class="form-control InputItemExtra1" value="">
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="button" class=" mt-5 btn btn-primary MoreItem1">المزيد</button>
                    <button type="button" class=" mt-5 btn btn-danger delItem1">حذف</button>
                </div>
                <span class="badge Issue1 badge-danger"></span>
            </div>


            <div class="row">
                <div class="col-md-12 mt-3">
                    <label class="control-label">التعليمات بالانجليزية</label>
                    <div class="form-group itemItems2">
                        <input type="text" name="instruction_en[]" class="form-control InputItemExtra2" value="">
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="button" class=" mt-5 btn btn-primary MoreItem2">المزيد</button>
                    <button type="button" class=" mt-5 btn btn-danger delItem2">حذف</button>
                </div>
                <span class="badge Issue2 badge-danger"></span>
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trans('admin.add') }}</button>
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

    $(document).ready(function () {
        $('select[name="examable_type"]').on('change', function () {
            var season = $('select[name="season_id"]').val();
            var term = $('select[name="term_id"]').val();
            var type = $(this).val();
            var typeText = $(this).find(":selected").text();
            $('.typeName').html(typeText);
            if (type) {
                $.ajax({
                    url: "{{ route('examble_type_exam') }}",
                    type: "GET",
                    data: {
                        'type': type,
                        'season': season,
                        'term': term,
                    },
                    dataType: "json",
                    success: function (data) {
                        $('select[name="examable_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="examable_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });


    $("#exam_type").on('change', function () {
        let opt = $(this).find('option:selected').val();
        if (opt === 'pdf') {
            $('.pdfType').removeClass('d-none').prop('disabled', 'false');
        } else {
            $('.pdfType').addClass('d-none').prop('disabled', 'ture');
        }
    })


</script>


<script>
    $(document).on('click', '.delItem1', function () {
        var Item = $('.InputItemExtra1').last();
        let issue = $('.Issue1');
        if (Item.val() === '' && $('.InputItemExtra1').length > 1) {
            Item.fadeOut();
            Item.remove();
            issue.addClass('badge-success');
            issue.text('The element deleted');
            setTimeout(function () {
                $('.Issue1').html('');
            }, 3000)
        } else {
            console.log('error')
        }
    })

    $(document).on('click', '.MoreItem1', function () {
        var Item = $('.InputItemExtra1').last();
        if (Item.val() !== '') {
            $('.itemItems1').append('<input type="text" name="instruction_ar[]" class="form-control InputItemExtra1 mt-3">')
        }
    })

    $(document).on('click', '.delItem2', function () {
        var Item = $('.InputItemExtra2').last();
        let issue = $('.Issue2');
        if (Item.val() === '' && $('.InputItemExtra2').length > 1) {
            Item.fadeOut();
            Item.remove();
            issue.addClass('badge-success');
            issue.text('The element deleted');
            setTimeout(function () {
                $('.Issue2').html('');
            }, 3000)
        } else {
            console.log('error')
        }
    })

    $(document).on('click', '.MoreItem2', function () {
        var Item = $('.InputItemExtra2').last();
        if (Item.val() !== '') {
            $('.itemItems2').append('<input type="text" name="instruction_en[]" class="form-control InputItemExtra2 mt-3">')
        }
    })
</script>
