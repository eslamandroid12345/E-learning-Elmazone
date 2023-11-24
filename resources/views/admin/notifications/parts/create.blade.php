<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('notifications.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-12 mt-3">
                <label for="title" class="form-control-label">عنوان الرساله</label>
                <input type="text" class="form-control" name="title" value="اشعار جديد">
            </div>

            <div class="col-md-12 mt-3">
                <label for="body" class="form-control-label">الرسالة</label>
                <textarea class="form-control" name="body" rows="10"></textarea>
            </div>

            <div class="col-md-12 mt-3">
                <label for="season_id" class="form-control-label">الصفوف الدراسيه</label>
                <Select name="season_id" class="form-control select2">
                    <option selected>جميع الصفوف الدراسيه</option>
                    @foreach($seasons as $season)
                        <option value="{{ $season->id }}">{{ $season->name_ar }}</option>
                    @endforeach
                </Select>
            </div>

        </div>

            <div class="row">
                <div class="col-md-12">
                <div class="mt-3">
                    <label for="type" class="form-control-label">تريد ارسال هذا الاشعار لمن*</label>
                    <select id="selectUserType" name="type" class="form-control select2">
                        <option  value="all_students">كل طلبه هذا الصف</option>
                        <option  value="group_of_students">مجموعه طلبه</option>
                        <option  value="student">طالب معين</option>
                    </select>
                </div>

                <div id="student" class="mt-3 student-select hide-select">
                    <label for="user_id" class="form-control-label">اختر طالب معين*(في حاله اختيار طالب معين)</label>
                    <select name="user_id" class="form-control select2">

                    </select>
                </div>


                <div id="group_of_students" class="mt-3 group-select hide-select">
                    <label for="group_ids" class="form-control-label">اختر مجموعه طلبه*(في حاله اختيار مجموعه من الطلبه)</label>
                    <select name="group_ids[]" class="form-control select2" multiple>
                    </select>
                </div>


            </div>


            </div>

        <div class="row">
            <div class="col-md-12 mt-3">
                <label for="phone" class="form-control-label">ارفاق صوره او ملف مع الاشعار*</label>
                <input type="file" class="form-control dropify" min="11" name="image">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function(){

        $('.select2').select2();

        $('#selectUserType').on('change', function(){
            $('.hide-select').hide();
            $("#" + $(this).val()).fadeIn();
        }).change();
    });
</script>


<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $('.dropify').dropify()
    $(document).ready(function () {
        $('select[name="season_id"]').on('change', function () {
            var season_id = $(this).val();
            if (season_id) {
                $.ajax({
                    url: "{{ route('getAllStudentsBySeasonId') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        "season_id": season_id
                    },
                    success: function (data) {
                        $('select[name="user_id"]').empty();
                        $('select[name="group_ids[]"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="user_id"]').append('<option value="' + key + '">' + value + '</option>');
                            $('select[name="group_ids[]"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });




</script>




