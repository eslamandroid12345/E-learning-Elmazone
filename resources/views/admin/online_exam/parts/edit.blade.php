<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('onlineExam.update', $onlineExam->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $onlineExam->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">نوع الامتحان</label>
                    <select name="exam_type" class="form-control select2" id="exam_type">
                        <option value="" disabled>اختر نوع الامتحان</option>
                        <option value="pdf" {{ $onlineExam->exam_type == 'pdf' ? 'selected' :''}}>PDF</option>
                        <option value="online" {{ $onlineExam->exam_type == 'online' ? 'selected' : ''}}>Online</option>
                    </select>
                </div>
            </div>


            <div class="row d-none pdfType">
                <div class="col-md-12 mt-3">
                    <label for="pdf_num_questions" class="form-control-label">عدد اسئله الملف الورقي</label>
                    <input type="number" class="form-control" name="pdf_num_questions"
                           value="{{ $onlineExam->pdf_num_questions }}">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="pdf_file_upload" class="form-control-label">ملف الpdf</label>
                    <input type="file" class="form-control" name="pdf_file_upload"
                           value="{{ $onlineExam->pdf_file_upload }}">
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="answer_pdf_file" class="form-control-label">ملف الاجابات pdf</label>
                    <input type="file" class="form-control" name="answer_pdf_file"
                           value="{{ $onlineExam->answer_pdf_file }}">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="answer_video_file" class="form-control-label">الاجابات فيديو</label>
                    <input type="file" class="form-control" name="answer_video_file"
                           value="{{ $onlineExam->answer_video_file }}">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="answer_video_youtube" class="form-control-label">الاجابه فيديو*يوتيوب</label>
                    <input type="text" class="form-control" name="answer_video_youtube"   value="{{ $onlineExam->answer_video_youtube }}">
                </div>

                <input type="hidden" class="form-control" name="answer_video_is_youtube">

            </div>


            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الدرجة</label>
                    <input type="number" class="form-control" name="degree" value="{{ $onlineExam->degree }}"
                           required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="date_exam" class="form-control-label">موعد الامتحان</label>
                    <input type="date" class="form-control" name="date_exam" value="{{ $onlineExam->date_exam }}"
                         required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label"> وقت الامتحان</label>
                    <input type="number" class="form-control" name="quize_minute"
                           value="{{ $onlineExam->quize_minute }}"
                           placeholder="الوقت بالدقائق" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label"> عدد المحاولات </label>
                    <input type="number" class="form-control" name="trying_number"
                           value="{{ $onlineExam->trying_number }}"
                           placeholder="عدد المحاولات" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الاسم بالعربي</label>
                    <input type="text" class="form-control" value="{{ $onlineExam->name_ar }}" name="name_ar"
                           required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" value="{{ $onlineExam->name_en }}" name="name_en" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">الصف</label>
                    <select name="season_id"
                            class="form-control selectSeason select2" @selected( old('season_id',$onlineExam->season_id))  required>
                        <option disabled >اختر الصف</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}"
                                   >{{ $season->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">تيرم</label>
                    <select name="term_id"
                            class="form-control selectTerm select2" @selected( old('term_id',$onlineExam->term_id)) required>
                        <option disabled>اختر تيرم</option>
                        <option value="{{ $onlineExam->term_id }}" class="form-control">{{ $onlineExam->term->name_ar ?? '' }}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label">النوع</label>
                    <Select name="examable_type" id="type" class="form-control type_choose select2"
                            required="required" @selected( old('examable_type',$onlineExam->type))>
                        <option disabled style="text-align: center">اختار النوع</option>
                        <option value="lesson" style="text-align: center">درس</option>
                        <option value="class" style="text-align: center">فصل</option>
                        <option value="video" style="text-align: center">الفيديو</option>
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">لون الخلفية</label>
                    <input type="color" class="form-control" value="{{ $onlineExam->background_color }}" name="background_color" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="lesson" class="form-control-label typeName">الدرس</label>
                    <Select name="examable_id" class="form-control type_ajax_choose select2" required="required">
                        <option value="{{ $onlineExam->term_id ?? $onlineExam->class_id ?? $onlineExam->video_id }}">
                            {{ $onlineExam->lesson->name_ar ?? $onlineExam->class->name_ar ?? $onlineExam->video->name_ar ?? '' }}
                        </option>
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label"> نصيحة</label>
                    <input type="text" class="form-control" value="{{ $onlineExam->title_result }}" name="title_result" style="text-align: center" required>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">صورة النصيحة</label>
                    <input type="file" class="form-control" name="image_result" {{ $onlineExam->image_result }} style="text-align: center">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label"> وصف النصيحة</label>
                    <textarea class="form-control" name="description_result" rows="4" required>{{ $onlineExam->description_result }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label class="control-label">التعليمات بالعربية</label>
                    <div class="form-group itemItems1">
                        @if($onlineExam->instruction_ar != null)
                        @foreach($onlineExam->instruction_ar as $val1)
                            <input type="text" name="instruction_ar[]" class="form-control mt-3 InputItemExtra1"
                                   value="{{ $val1 }}">
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <button type="button" class=" mt-5 btn btn-primary MoreItem1">المزيد</button>
                    <button type="button" class=" mt-5 btn btn-danger delItem1">حذف</button>
                </div>
                <span class="badge Issue1 badge-danger"></span>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label class="control-label">التعليمات بالانجليزية</label>
                    <div class="form-group itemItems2">
                        @if($onlineExam->instruction_en != null)
                        @foreach($onlineExam->instruction_en as $val2)
                            <input type="text" name="instruction_en[]" class="form-control mt-3 InputItemExtra2" value="{{ $val2 }}">
                        @endforeach
                            @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <button type="button" class=" mt-5 btn btn-primary MoreItem2">المزيد</button>
                    <button type="button" class=" mt-5 btn btn-danger delItem2">حذف</button>
                </div>
                <span class="badge Issue2 badge-danger"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trans('admin.update') }}</button>
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
