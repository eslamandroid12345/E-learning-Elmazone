<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('section.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="section_name_ar" class="form-control-label">الاسم بالعربية</label>
                    <input type="text" class="form-control" name="section_name_ar" required>
                </div>
                <div class="col-md-6">
                    <label for="section_name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" name="section_name_en" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="section_name_ar" class="form-control-label">العنوان</label>
                    <input type="text" class="form-control" name="address" required>
                </div>
                <div class="col-md-6">
                    <label for="section_name_en" class="form-control-label">السعة</label>
                    <input type="number" class="form-control" name="capacity" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trans('admin.add') }}</button>
        </div>
    </form>
</div>

<script>
    $('.dropify').dropify()
</script>
