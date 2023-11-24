<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('allExam.update', $allExam->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $allExam->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="exam_type" class="form-control-label">نوع الامتحان</label>
                    <select class="form-control" name="exam_type" id="exam_type">
                        <option value="" disabled>اختر النوع</option>
                        <option value="all_exam" class="form-control" {{ $allExam->exam_type == 'all_exam' ? 'selected' :''}}>امتحان شامل</option>
                        <option value="pdf" class="form-control" {{ $allExam->exam_type == 'pdf' ? 'selected' :''}}>ملف ورقي*pdf</option>
                    </select>
                </div>
            </div>
            <div class="row d-none pdfType">
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="pdf_file_upload">رفع الملف (pdf)</label>
                    <input type="file" class="form-control" value="{{ $allExam->pdf_file_upload }}"
                           name="pdf_file_upload">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="pdf_num_questions">عدد الاسئلة</label>
                    <input type="number" class="form-control" value="{{ $allExam->pdf_num_questions }}"
                           name="pdf_num_questions">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="answer_pdf_file">رفع الاجابة pdf</label>
                    <input type="file" class="form-control" name="answer_pdf_file"
                           value="{{ $allExam->answer_pdf_file }}">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="answer_video_file">رفع الاجابة video</label>
                    <input type="file" class="form-control" name="answer_video_file"
                           value="{{  $allExam->answer_video_file }}">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="answer_video_youtube" class="form-control-label">الاجابه فيديو*يوتيوب</label>
                    <input type="text" class="form-control" name="answer_video_youtube" value="{{$allExam->answer_video_youtube}}">
                </div>

                <input type="hidden" class="form-control" name="answer_video_is_youtube">

            </div>
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="date_exam">تاريخ اضافه الامتحان</label>
                    <input type="date" class="form-control" name="date_exam" value="{{ $allExam->date_exam }}"
                           required="required">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="quize_minute">وقت الامتحان بالدقائق</label>
                    <input type="number" class="form-control" name="quize_minute" value="{{ $allExam->quize_minute }}"
                           required="required">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="trying_number">عدد المحاولات</label>
                    <input type="number" class="form-control" name="trying_number" value="{{ $allExam->trying_number }}"
                           required="required">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-control-label" for="degree">درجة الامتحان</label>
                    <input type="number" class="form-control" name="degree" value="{{ $allExam->degree }}"
                           required="required">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الاسم بالعربية</label>
                    <input type="text" class="form-control" name="name_ar" required="required"
                           value="{{ $allExam->name_ar }}">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" name="name_en" required="required"
                           value="{{ $allExam->name_en }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mt-3">
                    <label for="note" class="form-control-label">اختر الصف الدراسي</label>
                    <Select name="season_id" required="required"
                            class="form-control" @selected(old('season_id',$allExam->season_id))>
                        <option disabled>اختر الصف الدراسي</option>
                        @foreach($data['seasons'] as $season)
                            <option value="{{ $season->id }}"
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6 mt-3">
                    <label for="note" class="form-control-label">اختر تيرم معين</label>
                    <Select name="term_id" required="required"
                            class="form-control">
                        <option disabled>اختر التيرم</option>
                        <option class="form-control"
                                value="{{ $allExam->term_id }}">{{ $allExam->term->name_ar }}</option>
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">لون الخلفية</label>
                    <input type="color" class="form-control" name="background_color"
                           value="#D7EAF9">
                </div>

            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">نصيحة</label>
                    <input type="text" name="title_result" value="{{ $allExam->title_result }}" class="form-control">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">وصف النصيحة</label>
                    <textarea name="description_result" class="form-control" rows="8">{{ $allExam->description_result }}</textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">صورة النصيحة</label>
                    <input type="file" name="image_result" value="{{ $allExam->image_result }}" class="form-control">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-9">
                    <label class="control-label">التعليمات بالعربية</label>
                    <div class="form-group itemItems1">
                        @foreach($allExam->instruction_ar as $val1)
                            <input type="text" name="instruction_ar[]" class="form-control mt-3 InputItemExtra1"
                                   value="{{ $val1 }}">
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class=" mt-5 btn btn-primary MoreItem1">المزيد</button>
                    <button type="button" class=" mt-5 btn btn-danger delItem1">حذف</button>
                </div>
                <span class="badge Issue1 badge-danger"></span>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <label class="control-label">التعليمات بالانجليزية</label>
                    <div class="form-group itemItems2">
                        @foreach($allExam->instruction_en as $val2)
                            <input type="text" name="instruction_en[]" class="form-control mt-3 InputItemExtra2"
                                   value="{{ $val2 }}">
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class=" mt-5 btn btn-primary MoreItem2">المزيد</button>
                    <button type="button" class=" mt-5 btn btn-danger delItem2">حذف</button>
                </div>
                <span class="badge Issue2 badge-danger"></span>
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
    $(document).ready(function () {
        $('.colorInput').addClass('d-none');

        const colorSelect = document.querySelector('#colorSelect');
        colorSelect.addEventListener('click', () => {
            $('.colorInput').removeClass('d-none');
            $('.colorInput').css('background-color', colorSelect.value);
        });
    })

</script>
