<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('questions.update', $question->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $question->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label class="form-check-label" for="degree">درجه السؤال</label>
                    <input class="form-control" name="degree" type="number" value="{{ $question->degree }}"/>
                </div>
                <div class="col-md-12 mt-3">
                    <label class="form-check-label" for="degree">درجه الصعوبه لهذا السؤال*</label>
                    <select class="form-control" name="difficulty">
                        <option value="low" class="form-control" {{ $question->difficulty == 'low' ? 'selected' : '' }}>سهل</option>
                        <option value="mid" class="form-control" {{ $question->difficulty == 'mid' ? 'selected' : '' }}>متوسط</option>
                        <option value="high" class="form-control" {{ $question->difficulty == 'high' ? 'selected' : '' }}>صعب</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-4">
                    <label for="name_ar" class="form-control-label">اكتب سؤالك هنا</label>
                    <textarea id="question" class="form-control" rows="5" name="question">{{ $question->question }}</textarea>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12 mt-4">
                    <label for="">ارفاق صوره معينه لرسم بياني او معادله فيزيائيه*اختياري</label>
                    <input type="file" name="image" class="dropify"
                           value="{{ $question->image }}"
                           data-default-file="{{ $question->image }}"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="season" class="form-control-label">الصف الدراسي</label>
                    <Select name="season_id" class="form-control seasonChoose">
                        <option disabled>اختار الصف</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}"
                                    {{ $question->season_id == $season->id ? 'selected' : '' }}
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="term" class="form-control-label">اختر تيرم للفصل الدراسي</label>
                    <Select name="term_id" class="form-control user_choose"
                            required="required">
                        <option selected value="{{ $question->term_id }}">{{ $question->term->name_ar }}</option>
                    </Select>
                </div>
            </div>


            <div class="row choseExamp">
                <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label ">اختر قسم للسؤال</label>
                    <Select name="type" id="type" class="form-control type_choose"
                            required="required">
                        <option disabled style="text-align: center">اختر قسم للسؤال</option>
                        <option value="lesson"
                                {{ $question->type == 'lesson' ? 'selected' : '' }}
                                style="text-align: center">درس</option>
                        <option value="subject_class"
                                {{ $question->type == 'subject_class' ? 'selected' : '' }}
                                style="text-align: center">فصل</option>
                        <option value="video"
                                {{ $question->type == 'video' ? 'selected' : '' }}
                                style="text-align: center">واجب</option>
                        <option value="all_exam"
                                {{ $question->type == 'all_exam' ? 'selected' : '' }}
                                style="text-align: center">امتحان شامل</option>
                        <option value="life_exam"
                                {{ $question->type == 'life_exam' ? 'selected' : '' }}
                                style="text-align: center">امتحان لايف</option>
                    </Select>
                </div>

            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>

<script src="{{asset('uploads/js/ckeditor1.js')}}"></script>
<script>
    ClassicEditor.create( document.querySelector( '#question' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
<script>

    $('.dropify').dropify();

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
        $('select[name="season_id"]').on('click', function () {
            $('.choseExamp').removeClass('d-none');
        })
    })


    $(document).ready(function () {
        $('select[name="examable_type"]').on('change', function () {
            var season = $('select[name="season_id"]').val();
            var term = $('select[name="term_id"]').val();
            var type = $(this).val();
            var typeText = $(this).find(":selected").text();
            $('.typeName').html(typeText);
            if (type) {
                $.ajax({
                    url: "{{ route('examble_type_question') }}",
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

</script>
