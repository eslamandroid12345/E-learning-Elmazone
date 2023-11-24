<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('phoneCommunications.update', $phoneCommunication->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $phoneCommunication->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="phone" class="form-control-label">ألهاتف</label>
                    <input type="tel" class="form-control" value="{{ $phoneCommunication->phone }}" name="phone">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="name_en" class="form-control-label">ملاحظة</label>
                    <textarea class="form-control" rows="10" name="note">{{ $phoneCommunication->note }}</textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trans('admin.update') }}</button>
        </div>
    </form>
</div>
