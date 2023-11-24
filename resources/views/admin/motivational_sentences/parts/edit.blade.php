<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('motivational.update', $motivational->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $motivational->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="percentage_from" class="form-control-label">النسبه من :</label>
                    <input type="text" class="form-control" name="percentage_from"
                           placeholder="مثال : 50%"
                           value="{{ $motivational->percentage_from }}"
                           id="percentage_from">
                </div>

                <div class="col-md-12">
                    <label for="percentage_to" class="form-control-label">النسبه الي :</label>
                    <input type="text" class="form-control" name="percentage_to"
                           placeholder="مثال : 60%"
                           value="{{ $motivational->percentage_to }}"
                           id="percentage_to">
                </div>
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">الجملة بالعربية</label>
                    <textarea rows="7" class="form-control" name="title_ar" required>{{ $motivational->title_ar }}</textarea>
                </div>
                <div class="col-md-12">
                    <label for="name_en" class="form-control-label">الجملة بالانجليزية</label>
                    <textarea rows="7" class="form-control" name="title_en" required>{{ $motivational->title_en }}</textarea>
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
