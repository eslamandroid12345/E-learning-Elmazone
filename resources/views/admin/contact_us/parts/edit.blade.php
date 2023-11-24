<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('contactUs.update', $contactU->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $contactU->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="type" class="form-control-label">اسم</label>
                    <input class="form-control" name="name" value="{{ $contactU->name }}" />
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-control-label">الموضوع</label>
                    <input class="form-control" name="subject" value="{{ $contactU->subject }}" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="message" class="form-control-label">رسالة</label>
                    <textarea class="form-control" name="message" rows="10">{{ $contactU->message }}</textarea>
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
