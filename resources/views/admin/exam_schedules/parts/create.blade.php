<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('exam_schedules.store') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">صورة :</label>
                    <input type="file" name="image" class="dropify" value=""
                           data-default-file=""/>
                </div>
                <span class="form-text text-danger text-center">
                    Recomended : 2048 X 1200 to up Px <br>
                    Extension : png, gif, jpeg,jpg,webp
                </span>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="title_ar" class="form-control-label">العنوان بالعربية</label>
                    <input type="text" class="form-control" value="" name="title_ar">
                </div>
                <div class="col-md-6">
                    <label for="title_en" class="form-control-label">العنوان بالانجليزية</label>
                    <input type="text" class="form-control" value="" name="title_en">
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control">
                        <option disabled selected>اختر</option>
                        @foreach ($data['seasons'] as $season)
                            <option value="{{ $season->id }}" style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الترم</label>
                    <Select name="term_id" class="form-control">
                        @foreach ($data['terms'] as $term)
                            <option value="{{ $term->id }}" style="text-align: center">
                                {{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">التاريخ</label>
                    <input type="datetime-local" class="form-control"  name="date_time">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="title_ar" class="form-control-label">الوصف بالعربية</label>
                    <textarea rows="8" class="form-control" name="description_ar"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="title_en" class="form-control-label">الوصف بالانجليزية</label>
                    <textarea rows="8" class="form-control" name="description_en"></textarea>
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

</script>

