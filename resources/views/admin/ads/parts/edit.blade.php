<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('ads.update', $ad->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $ad->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">اللينك</label>
                    <input type="text" class="form-control" value="{{ $ad->link }}" name="link">
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-control-label">النوع</label>
                    <select class="form-control" name="type" style="text-align: center">
                            <option style="text-align: center"
                                    {{ $ad->type == 'image' ? 'selected' : '' }}
                                    value="image">صورة</option>
                        <option style="text-align: center"
                                {{ $ad->type == 'video' ? 'selected' : '' }}
                                value="video">فيديو</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="name_en" class="form-control-label">الصورة</label>
                    <input type="file" class="form-control dropify" data-default-file="{{ asset($ad->file) }}" min="11"
                           name="file">
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
