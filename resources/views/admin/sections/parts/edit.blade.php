<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('section.update', $section->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $section->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="section_name_ar" class="form-control-label">الاسم بالعربية</label>
                    <input type="text" class="form-control" value="{{ $section->section_name_ar }}"
                        name="section_name_ar" required>
                </div>
                <div class="col-md-6">
                    <label for="section_name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" value="{{ $section->section_name_en }}"
                        name="section_name_en" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="section_name_ar" class="form-control-label">العنوان</label>
                    <input type="text" class="form-control" value="{{ $section->address }}" name="address" required>
                </div>
                <div class="col-md-6">
                    <label for="section_name_en" class="form-control-label">السعة</label>
                    <input type="number" class="form-control" value="{{ $section->capacity }}" name="capacity" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trans('admin.update') }}</button>
        </div>
    </form>
</div>
