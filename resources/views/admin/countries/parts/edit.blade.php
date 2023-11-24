<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('countries.update', $country->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $country->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name_ar" class="form-control-label">{{ trans('admin.name_ar') }}</label>
                    <input type="text" class="form-control" value="{{ $country->name_ar }}" name="name_ar">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name_en" class="form-control-label">{{ trans('admin.name_en') }}</label>
                    <input type="text" class="form-control" value="{{ $country->name_en }}" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="city_id">المحافظة</label>
                    <Select name="city_id" class="form-control">
                        <option selected disabled style="text-align: center">اختر المحافظة</option>
                        @foreach ($data['cities'] as $city)
                            <option value="{{ $city->id }}"
                                    {{ $country->city_id == $city->id ? 'selected' : '' }} style="text-align: center">
                                {{ $city->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trans('admin.update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
