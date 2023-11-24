<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('online_exam_questions.store')}}">
        @csrf

        <div class="row">
            <div class="col-md-12 mt-3">
                <label for="name_ar" class="form-control-label">الصف</label>
                <select name="season_id" id="season_id" class="form-control">
                    <option disabled selected>اختر</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->id }}" style="text-align: center">{{ $season->name_ar }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <label for="name_ar" class="form-control-label">التيرم</label>
                <select name="term_id" id="term_id" class="form-control">
                    <option disabled selected>اختر</option>
                    @foreach ($terms as $term)
                        <option value="{{ $term->id }}" style="text-align: center">
                            {{ $term->name_ar }}</option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-12 mt-3">
                <label for="name_ar" class="form-control-label">نوع الامتحان</label>
                <select name="exam_type" id="exam_type" class="form-control">
                    <option disabled selected>اختر</option>
                    @foreach ($types as $key=>$value)
                        <option value="{{ $key }}" style="text-align: center">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12 mt-3">
                <label for="name_ar" class="form-control-label">جميع الامتحانات</label>
                <select name="exam" id="exam" class="form-control">
                    <option disabled selected>اختر</option>
                </select>
            </div>

            <div id="questionsAll">

            </div>


{{--        @foreach($questions as $question)--}}
{{--            <div class="col-12 mt-4">--}}
{{--                <label class="switch">--}}
{{--                    <input type="checkbox" role="switch" name="questionIds[]" value="{{ $question->id }}"--}}
{{--                           id="{{ $question->id }}">--}}
{{--                    <span class="slider round"></span>--}}
{{--                </label>--}}
{{--                <label class="form-control-label" for="{{ $question->id }}">{{ $question->question }}</label>--}}
{{--            </div>--}}
{{--        @endforeach--}}


            {{--


             <div class="col-12 mt-4">
                <label class="switch">
                    <input type="checkbox" role="switch" name="questionIds[]" value="{{ $question->id }}"
                           id="{{ $question->id }}">
                    <span class="slider round"></span>
                </label>
                <label class="form-control-label" for="{{ $question->id }}">{{ $question->question }}</label>
            </div>


            --}}

        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
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
        $('select[name="exam_type"]').on('change', function () {

            let season_id =   $("#season_id").val();
            let term_id =     $("#term_id").val();
            let exam_type =   $("#exam_type").val();

            if (exam_type) {
                $.ajax({
                    url: "{{ route('getAllExamsByType')}}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "season_id": season_id,
                        "term_id":   term_id,
                        "exam_type": exam_type,
                    },

                    success: function (data) {
                        $('select[name="exam"]').empty();

                        $.each(data, function (key, value) {
                            $('#exam').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });


    //get all questions by exam
    $(document).ready(function () {
        $('select[name="exam"]').on('click', function () {

            let season_id =   $("#season_id").val();
            let term_id =     $("#term_id").val();
            let exam_type =   $("#exam_type").val();

            if (exam_type) {
                $.ajax({
                    url: "{{ route('getAllQuestionsByExamType')}}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "season_id": season_id,
                        "term_id":   term_id,
                        "exam_type": exam_type,
                    },

                    success: function (data) {

                        $('#questionsAll').empty();
                        $.each(data, function (key, value) {
                            $("#questionsAll").append(' <div class="col-12 mt-4"> <label class="switch"> <input type="checkbox" role="switch" name="questionIds[]" value="' + key + '" id="' + key + '"> <span class="slider round"></span> </label> <label class="form-control-label" for=" '+ key + '">' + value + '</label> </div>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

</script>
