<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('lessons.store') }}">
        @csrf
        <div class="form-group">
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="title_ar" class="form-control-label">العنوان باللغة العربية(مثال الدرس الاول)</label>
                    <input type="text" class="form-control" name="title_ar">
                </div>
                <div class="col-md-6">
                    <label for="title_en" class="form-control-label">العنوان باللغة الانجليزية</label>
                    <input type="text" class="form-control" name="title_en">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الاسم باللغة العربية</label>
                    <input type="text" class="form-control" name="name_ar">
                </div>
                <div class="col-md-6">
                    <label for="name_en" class="form-control-label">الاسم باللغة الانجليزية</label>
                    <input type="text" class="form-control" name="name_en">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="" id="season_choose" class="form-control season">
                        <option selected disabled style="text-align: center">اختار الصف</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}"
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الوحدة</label>
                    <Select name="subject_class_id" class="form-control type_ajax_choose">

                    </Select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="head">لون الخلفية</label>
                    <input type="color" class="form-control" name="background_color"
                           value="">
                </div>
            </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label for="note" class="form-control-label">ملاحظة</label>
                        <textarea class="form-control" name="note" rows="10"></textarea>
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

    $(".season").on('change', function() {
        var element = document.getElementById("season_choose");
        var value = $(element).find('option:selected').val();

        $.ajax({
            url: '{{ route('showUnit') }}',
            data: {
                'id': value,
            },
            success: function (data) {
                $('.type_ajax_choose').html(data);
            }
        })
    })

</script>

