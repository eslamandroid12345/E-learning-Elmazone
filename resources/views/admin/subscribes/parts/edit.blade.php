<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST"
          action="{{ route('subscribe.update', $subscribe->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $subscribe->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الترم</label>
                    <Select name="term_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الترم</option>
                        @foreach ($data['terms'] as $term)
                            <option value="{{ $term->id }}"
                                    {{ $subscribe->term_id == $term->id ? 'selected' : '' }} style="text-align: center">
                                {{ $term->name_en }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الصف</option>
                        @foreach ($data['seasons'] as $season)
                            <option value="{{ $season->id }}"
                                    {{ $subscribe->season_id == $season->id ? 'selected' : '' }}
                                    style="text-align: center">{{ $season->name_en }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="">السعر داخل السنتر</label>
                    <input type="number" class="form-control" value="{{ $subscribe->price_in_center }}" name="price_in_center"/>
                </div>
                <div class="col-md-6">
                    <label for="">السعر خارج السنتر</label>
                    <input type="number" class="form-control" value="{{ $subscribe->price_out_center }}" name="price_out_center"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="month">الشهر</label>
                    <select class="form-control" style="text-align: center" name="month">
                        <option style="text-align: center" value="1" {{ $subscribe->month == 1 ? 'selected' : '' }}>الشهر الاول</option>
                        <option style="text-align: center" value="2" {{ $subscribe->month == 2 ? 'selected' : '' }}>الشهر التاني</option>
                        <option style="text-align: center" value="3" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر الثالث</option>
                        <option style="text-align: center" value="4" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر الرابع</option>
                        <option style="text-align: center" value="5" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر الخامس</option>
                        <option style="text-align: center" value="6" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر السادس</option>
                        <option style="text-align: center" value="7" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر السابع</option>
                        <option style="text-align: center" value="8" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر الثامن</option>
                        <option style="text-align: center" value="9" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر التاسع</option>
                        <option style="text-align: center" value="10" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر العاشر</option>
                        <option style="text-align: center" value="11" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر الحادي عشر</option>
                        <option style="text-align: center" value="12" {{ $subscribe->month == 3 ? 'selected' : '' }}>الشهر الثاني عشر</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="free">مجانا</label> :<br>
                    <select class="form-control" style="text-align: center" name="free">
                        <option style="text-align: center" value="1" {{ $subscribe->free == 'yes' ? 'selected' : '' }}>نعم</option>
                        <option style="text-align: center" value="0" {{ $subscribe->free == 'no' ? 'selected' : '' }}>لا</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
