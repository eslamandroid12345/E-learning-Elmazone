<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('discount_coupons.store') }}">
        @csrf
        <div class="form-group">
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="coupon" class="form-control-label">كوبون</label>
                    <input type="text" class="form-control" name="coupon">
                </div>
                <div class="col-md-6">
                    <label for="discount_type" class="form-control-label">نوع الخصم</label>
                    <select name="discount_type" class="form-control">
                        <option value="per">بالقيمة المئوية</option>
                        <option value="value">بالجنيه المصري</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="discount_amount" class="form-control-label">كمية الخصم</label>
                    <input type="number" class="form-control" name="discount_amount">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="valid_from" class="form-control-label">تاريخ بدايه صلاحيه الخصم</label>
                    <input type="date" class="form-control" name="valid_from">
                </div>
                <div class="col-md-6">
                    <label for="valid_to" class="form-control-label">تاريخ نهايه صلاحيه الخصم</label>
                    <input type="date" class="form-control" name="valid_to">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="total_usage" class="form-control-label">مجموع الاستخدام</label>
                    <input type="number" class="form-control" name="total_usage">
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
    $('.dropify').dropify()
</script>
