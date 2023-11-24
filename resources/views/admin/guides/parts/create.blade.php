<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('guide.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="section_name_ar" class="form-control-label">عنوان او اسم المرجع باللغه العربيه</label>
                    <input type="text" class="form-control" name="title_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="section_name_en" class="form-control-label">عنوان او اسم المرجع باللغه الانجليزيه</label>
                    <input type="text" class="form-control" name="title_en">
                </div>

            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الصف الدراسي</label>
                    <Select name="season_id" id="season_id" class="form-control season_id select2">
                        <option selected disabled>اختار الصف</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">اختر تيرم معين</label>
                    <select name="term_id" id="term_id" class="form-control term_id select2">
                        <option value="">الكل</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="head">لون خلفيه المرجع</label>
                    <input type="color" class="form-control" name="background_color"
                           value="">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="section_name_ar" class="form-control-label">سجل ملاحظاتك عن هذا المرجع باللغه العربيه*غير مطلوب</label>
                    <textarea class="form-control" name="description_ar" rows="8"></textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="section_name_en" class="form-control-label">سجل ملاحظاتك عن هذا المرجع باللغه الانجليزيه*غير مطلوب</label>
                    <textarea class="form-control" name="description_en" rows="8"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="section_name_ar" class="form-control-label">ارفق المرجع*</label>
                    <input type="file" name="file" class="form-control" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="section_name_ar" class="form-control-label">ايقونه المرجع*</label>
                    <input type="file" name="icon" class="dropify" />
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
    $(document).ready(function() {
        $('.select2').select2();
    });

    $('.dropify').dropify()

</script>
<script>

    $(document).ready(function () {
        $('.season_id').on('change', function () {
            let season = $(this).val();
            $.ajax({
                    url: '{{ route("subjectClassSort")}}',
                method: 'GET',
                data: {
                    'id': season,
                }, success: function (data) {
                    $('.term_id').html(data);
                    console.log(data);
                }
            })
        })
    });

</script>
