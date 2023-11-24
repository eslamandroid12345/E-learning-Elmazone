<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('motivational.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="percentage_from" class="form-control-label">النسبه من :</label>
                    <input type="number" class="form-control" name="percentage_from"
                           placeholder="مثال : 100%"
                           id="percentage">
                </div>
                <div class="col-md-12">
                    <label for="percentage_to" class="form-control-label">النسبه الي :</label>
                    <input type="number" class="form-control" name="percentage_to"
                           placeholder="مثال : 100%"
                           id="percentage">
                </div>
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">الجملة بالعربية</label>
                    <textarea rows="7" class="form-control" name="title_ar"></textarea>
                </div>
                <div class="col-md-12">
                    <label for="name_en" class="form-control-label">الجملة بالانجليزية</label>
                    <textarea rows="7" class="form-control" name="title_en"></textarea>
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
