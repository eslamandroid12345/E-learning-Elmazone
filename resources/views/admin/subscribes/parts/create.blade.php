<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('subscribe.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="note" class="form-control-label">تيرم</label>
                    <Select name="term_id" class="form-control">
                        <option selected disabled style="text-align: center">اختر تيرم</option>
                        @foreach($data['terms'] as $term)
                            <option value="{{ $term->id }}"
                                    style="text-align: center">{{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">فصل</label>
                    <Select name="season_id" class="form-control">
                        <option selected disabled style="text-align: center">اختر فصل</option>
                        @foreach($data['seasons'] as $season)
                            <option value="{{ $season->id }}"
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="">السعر داخل السنتر</label>
                    <input type="number" class="form-control" name="price_in_center"/>
                </div>
                <div class="col-md-6">
                    <label for="">السعر خارج السنتر</label>
                    <input type="number" class="form-control" name="price_out_center"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="month">الشهر</label>
                    <select class="form-control" style="text-align: center"name="month">
                        <option style="text-align: center" value="1">الشهر الاول</option>
                        <option style="text-align: center" value="2">الشهر التاني</option>
                        <option style="text-align: center" value="3">الشهر الثالث</option>
                        <option style="text-align: center" value="4">الشهر الرابع</option>
                        <option style="text-align: center" value="5">الشهر الخامس</option>
                        <option style="text-align: center" value="6">الشهر السادس</option>
                        <option style="text-align: center" value="7">الشهر السابع</option>
                        <option style="text-align: center" value="8">الشهر الثامن</option>
                        <option style="text-align: center" value="9">الشهر التاسع</option>
                        <option style="text-align: center" value="10">الشهر العاشر</option>
                        <option style="text-align: center" value="11">الشهر الحادي عشر</option>
                        <option style="text-align: center" value="12">الشهر الثاني عشر</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="free">مجانا</label> :<br>
                    <select class="form-control" style="text-align: center" name="free">
                        <option style="text-align: center" value="1" >نعم</option>
                        <option style="text-align: center" value="0" >لا</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

