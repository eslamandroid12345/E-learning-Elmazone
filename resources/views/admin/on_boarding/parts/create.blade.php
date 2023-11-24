<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('onBoarding.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label"> العنوان باللغة العربية</label>
                    <input class="form-control" name="title_ar" />
                </div>
                <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label"> العنوان باللغة الانجليزية</label>
                    <input class="form-control" name="title_en" />
                </div>
                <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label">الوصف باللغة العربية</label>
                   <textarea class="form-control" name="description_ar"></textarea>
                </div>
                 <div class="col-md-12 mt-3">
                    <label for="type" class="form-control-label">الوصف باللغة الانجليزية</label>
                     <textarea class="form-control" name="description_en" ></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="phone" class="form-control-label">الصورة</label>
                    <input type="file" class="form-control dropify" min="11" name="image">
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
