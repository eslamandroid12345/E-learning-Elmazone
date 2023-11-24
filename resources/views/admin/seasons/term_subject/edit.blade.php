<div class="modal-body">
        <form id="updateForm" class="updateForm" method="POST" action="{{ route('termSubjectClassUpdate', $term->id) }}">
        @csrf
            @method('PUT')
{{--        <input type="hidden" value="{{ $term->id }}" name="id">--}}
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
