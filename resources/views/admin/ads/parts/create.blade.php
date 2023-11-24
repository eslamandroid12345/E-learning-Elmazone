<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">اللينك</label>
                    <input type="text" class="form-control" name="link">
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-control-label">النوع</label>
                    <select class="form-control" name="type" style="text-align: center">
                        <option style="text-align: center" value="0">صورة</option>
                        <option style="text-align: center" value="1">فيديو</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="name_en" class="form-control-label">الصورة</label>
                    <input type="file" class="form-control dropify" min="11" name="file">
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

<script>
    $('.dropify').dropify()
</script>
