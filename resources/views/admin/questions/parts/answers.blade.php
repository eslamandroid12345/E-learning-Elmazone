<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('addAnswer',$question->id) }}">
        @csrf
        <div class="form-group">
            <input type="hidden" name="question_id" value="{{ $question->id }}" class="form-control">
            <div class="row">
                <div class="col-lg-12">
                    <div id="inputFormRow">
                        <div class="input-group mb-3">
                            <input type="hidden" name="answer_number[]" class="form-control">
                            <textarea id="answer1" name="answer[أ]" class="form-control m-input" rows="1">{{ @$answers[0]['answer'] }}</textarea>
                            <input type="radio" name="answer_status" {{ @$answers[0]['answer_status'] == 'correct' ? 'checked' : '' }} value="أ" class="form-control check_class">
                        </div>
                        <div class="input-group mb-3">
                            <input type="hidden" name="answer_number[1]" class="form-control">
                            <textarea id="answer2" name="answer[ب]" class="form-control m-input" rows="1">{{ @$answers[1]['answer'] }}</textarea>
                            <input type="radio" name="answer_status" {{ @$answers[1]['answer_status'] == 'correct' ? 'checked' : '' }} value="ب" class="form-control check_class">
                        </div>
                        <div class="input-group mb-3">
                            <input type="hidden" name="answer_number[1]" class="form-control">
                            <textarea id="answer3" name="answer[ج]" class="form-control m-input" rows="1">{{ @$answers[2]['answer'] }}</textarea>
                            <input type="radio" name="answer_status" {{ @$answers[2]['answer_status'] == 'correct' ? 'checked' : '' }} value="ج" class="form-control check_class">
                        </div>
                        <div class="input-group mb-3">
                            <input type="hidden" name="answer_number[1]" class="form-control">
                            <textarea id="answer4" name="answer[د]" class="form-control m-input" rows="1">{{ @$answers[3]['answer'] }}</textarea>
                            <input type="radio" name="answer_status" {{ @$answers[3]['answer_status'] == 'correct' ? 'checked' : '' }} value="د" class="form-control check_class">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="buttonAnswer" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

<script src="{{asset('uploads/js/ckeditor1.js')}}"></script>
<script>
    ClassicEditor.create( document.querySelector( '#answer1' ) )
        .catch( error => {
            console.error( error );
        } );

    ClassicEditor.create( document.querySelector( '#answer2' ) )
        .catch( error => {
            console.error( error );
        } );

    ClassicEditor.create( document.querySelector( '#answer3' ) )
        .catch( error => {
            console.error( error );
        } );


    ClassicEditor.create( document.querySelector( '#answer4' ) )
        .catch( error => {
            console.error( error );
        } );
</script>


{{--<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>--}}
<script type="text/javascript">
// $(document).ready(function () {
//         $('.ckeditor').ckeditor();
//     });


// var i = 1;
// // add row
// $("#addRow").click(function () {
//     ++i;
//     var html = '';
//     html += '<div id="inputFormRow">';
//     html += '<div class="input-group mb-3">';
//     html += '<input type="hidden" name="answer_number[' + i + ']" class="form-control">';
//     html += '<textarea name="answer[' + i + ']" data-num="" class="ckeditor form-control m-input answerInput" autocomplete="off"></textarea>'; // Changed input to textarea
//     html += '<div class="input-group-append">';
//     html += '<button id="removeRow" type="button" class="btn btn-danger">ازالة</button>';
//     html += '</div>';
//     html += '<input type="radio" name="answer_status" value="' + i + '" class="form-control check_class"></span>';
//     html += '</div>';

//     // var answerLength = $('.answerInput').length();
//     var answer = $('.answerInput');

//     if (answer.last().val() !== '' && answer.length < 3) {
//         $('#newRow').append(html);
//         var numQuestion = answer.length;
//         $('')
//     }
// });

// // remove row
// $(document).on('click', '#removeRow', function () {
//     $(this).closest('#inputFormRow').remove();
// });



</script>
