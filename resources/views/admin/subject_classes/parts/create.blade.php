<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('subjectsClasses.store') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="title_ar" class="form-control-label">العنوان بالعربية مثال(الفصل الاول)</label>
                    <input type="text" class="form-control" value="" name="title_ar">
                </div>
                <div class="col-md-6">
                    <label for="title_en" class="form-control-label">العنوان بالانجليزية</label>
                    <input type="text" class="form-control" value="" name="title_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الاسم بالعربية</label>
                    <input type="text" class="form-control" value="" name="name_ar">
                </div>
                <div class="col-md-6">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" value="" name="name_en">
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الصف الدراسي</label>
                    <Select name="season_id" class="form-control">
                        <option disabled selected>اختر</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}" style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>


                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">اختر تيرم</label>
                    <Select name="term_id" class="form-control">
                        <option disabled>اختر</option>
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}" style="text-align: center">
                                {{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="head">لون الخلفية</label>
                    <input type="color" class="form-control" name="background_color"
                           value="">
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">ملاحظة</label>
                    <input type="text" class="form-control" value="" name="note">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="">الصورة :</label>
                    <input type="file" name="image" class="dropify"
                           data-default-file=""/>

                    <span class="form-text text-danger text-center"> Recomended : 2048 X 1200 to up Px <br> Extension :
                        png, gif, jpeg,
                        jpg,webp</span>

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

