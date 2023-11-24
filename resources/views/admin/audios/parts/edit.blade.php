<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('audio.update', $audio->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $audio->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الاسم</label>
                    <input type="file" class="form-control"value="{{ $audio->audio }}" name="audio">
                </div>
                <div class="col-md-6">
                    <label for="lesson" class="form-control-label">الدرس</label>
                    <Select name="lesson_id" class="form-control user_choose">
                        <option selected disabled style="text-align: center">اختار درس</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}"
                                    {{ $audio->lesson_id == $lesson->id? 'selected' : '' }}
                                    style="text-align: center">{{ $lesson->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trans('admin.update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
