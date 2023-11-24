<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('discount_coupons.update', $discount_coupon->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $discount_coupon->id }}" name="id">
        <div class="form-group">
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="coupon" class="form-control-label">كوبون</label>
                    <input type="text" class="form-control" value="{{  $discount_coupon->coupon }}" name="coupon">
                </div>
                <div class="col-md-6">
                    <label for="discount_type" class="form-control-label">نوع الخصم</label>
                    <select name="discount_type" class="form-control">
                        <option value="per" @if($discount_coupon->discount_type == 'per') selected @endif>بالقيمة المئوية</option>
                        <option value="value" @if($discount_coupon->discount_type == 'value') selected @endif>بالجنيه المصري</option>>بالجنيه المصري</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="discount_amount" class="form-control-label">كمية الخصم</label>
                    <input type="number" class="form-control" value="{{  $discount_coupon->discount_amount }}" name="discount_amount">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="valid_from" class="form-control-label">تاريخ بدايه صلاحيه الخصم</label>
                    <input type="date" class="form-control" value="{{  $discount_coupon->valid_from }}" name="valid_from">
                </div>
                <div class="col-md-6">
                    <label for="valid_to" class="form-control-label">تاريخ نهايه صلاحيه الخصم</label>
                    <input type="date" class="form-control" value="{{  $discount_coupon->valid_to }}" name="valid_to">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="is_enabled" class="form-control-label">متاح</label>
                    <select name="is_enabled" class="form-control">
                        <option value="1" @if($discount_coupon->is_enabled == '1') selected @endif>نعم</option>
                        <option value="0" @if($discount_coupon->is_enabled == '0') selected @endif>لا</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="total_usage" class="form-control-label">مجموع الاستخدام</label>
                    <input type="number" class="form-control" value="{{  $discount_coupon->total_usage }}" name="total_usage">
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
