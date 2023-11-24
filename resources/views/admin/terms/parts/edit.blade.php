<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('terms.update', $term->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $term->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الاسم بالعربية</label>
                    <input type="text" class="form-control" value="{{ $term->name_ar }}" name="name_ar">
                </div>
                <div class="col-md-6">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" value="{{ $term->name_en }}" name="name_en">
                </div>
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الصف</option>
                        @foreach ($data['seasons'] as $season)
                            <option value="{{ $season->id }}"
                                    {{ $term->season_id == $season->id ? 'selected' : '' }}
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12">
                    <label for="note" class="form-control-label">ملاحظة</label>
                    <textarea class="form-control" rows="8" name="note">{{ $term->note }}</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
