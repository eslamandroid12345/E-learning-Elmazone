<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST"
          action="{{ route('papelSheetExam.update', $papelSheetExam->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $papelSheetExam->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="degree" class="form-control-label">درجه الامتحان الورقي</label>
                    <input type="number" class="form-control" name="degree" min="0"
                           value="{{ $papelSheetExam->degree }}">
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="date_exam" class="form-control-label">موعد الامتحان</label>
                    <input type="date" class="form-control" name="date_exam" value="{{ $papelSheetExam->date_exam }}">
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">اسم الامتحان باللغه العربيه</label>
                    <input type="text" class="form-control" name="name_ar" value="{{ $papelSheetExam->name_ar }}">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">اسم الامتحان باللغه الانجليزيه</label>
                    <input type="text" class="form-control" value="{{ $papelSheetExam->name_en }}" name="name_en">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الصف الدراسي</label>
                    <Select name="season_id" class="form-control">
                        @foreach ($seasons as $season)
                            <option
                                value="{{ $season->id }}" {{ $papelSheetExam->season_id == $season->id ? 'selected' : ''}}>{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">اختر التيرم التابع للصف الدراسي</label>
                    <Select name="term_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الترم</option>
                        @foreach ($terms as $term)
                            <option
                                value="{{ $term->id }}" {{ $papelSheetExam->term_id == $term->id ?  'selected' : ''}}>{{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">بدايه تاريخ التسجيل</label>
                    <input type="date" class="form-control" value="{{ $papelSheetExam->from }}" name="from">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">نهايه تاريخ التسجيل بالامتحان الورقي</label>
                    <input type="date" class="form-control" value="{{ $papelSheetExam->to }}" name="to">
                    </Select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="time" class="form-control-label">اوقات الامتحان</label>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-3 divFrom">
                            @foreach($times as $key => $time)
                                <div class="divinputFrom">
                                    <label for="time" class="form-control-label">من</label>
                                    <input type="time" class="form-control" name="times[from][]"
                                           value="{{ $time->from }}">
                                </div>
                            @endforeach

                        </div>
                        <div class="col-3 divTo">
                            @foreach($times as $key => $time)
                                <div class="divinputTo">
                                    <label for="time" class="form-control-label">الي</label>
                                    <input type="time" class="form-control" name="times[to][]" value="{{ $time->to }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="col-3">
                            <button style="margin-top: 28px;" type="button" class="form-control btn btn-sm btn-primary"
                                    id="addTime">اضافة وقت اخر
                            </button>
                        </div>
                        <div class="col-3">
                            <button style="margin-top: 28px; color: white !important; " type="button"
                                    class="form-control btn btn-sm btn-danger" id="removeTime">حذف وقت
                            </button>
                        </div>
                    </div>
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
        $('#addTime').on('click', function () {
            var divFrom = $('.divFrom');
            var divTo = $('.divTo');
            var appendFrom = '<div class="divinputFrom"><label for="time" class="form-control-label addlabel">من</label><input type="time" class="form-control addinput" name="times[from][]"></div>';
            var appendTo = '<div class="divinputTo"><label for="time" class="form-control-label addlabel">الي</label><input type="time" class="form-control addinput" name="times[to][]"></div>';

            divFrom.append(appendFrom);
            divTo.append(appendTo);
        })

        $('#removeTime').on('click', function () {
            var divinputFrom = $('.divinputFrom');
            var divinputTo = $('.divinputTo');

            divinputTo.last().remove();
            divinputFrom.last().remove();

        })
    })


</script>

