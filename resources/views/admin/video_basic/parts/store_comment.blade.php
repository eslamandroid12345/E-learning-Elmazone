<div class="modal-body">
    <form id="addFormReply" class="addFormReply" method="POST" action="{{ route('storeReply') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}"/>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="comment" class="form-control-label">الرد</label>
                    <textarea rows="8" required class="form-control" name="comment"></textarea>
                </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addReply">اضافة</button>
        </div>
        </div>
    </form>
</div>
<!-- fix -->

