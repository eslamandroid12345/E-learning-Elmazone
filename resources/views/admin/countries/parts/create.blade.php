<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('countries.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">الاسم بالعربي</label>
                    <input type="text" class="form-control" name="name_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزي</label>
                    <input type="text" class="form-control" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="city_id" class="form-control-label">اختر المحافظة</label>
                    <Select name="city_id" class="form-control select2">
                        @foreach ($data['cities'] as $city)
                            <option value="{{ $city->id }}" style="text-align: center" style="text-align: center">{{ $city->name_ar }}</option>
                        @endforeach
                    </Select>
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

    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
