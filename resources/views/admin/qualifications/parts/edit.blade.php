<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{route('qualification.update',$qualification->id)}}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{$qualification->id}}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <label for="type" class="form-label">النوع</label>
                    <select name="type" class="form-control" id="type" required="required">
                        <option class="form-control"  {{ $qualification->type == 'qualifications' ? 'selected' : '' }}  value="qualifications">المؤهلات</option>
                        <option class="form-control"  {{ $qualification->type == 'experience' ? 'selected' : '' }} value="experience">الخبرات</option>
                        <option class="form-control" {{ $qualification->type == 'skills' ? 'selected' : '' }}  value="skills">المهارات</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="title_ar" class="form-label">التاريخ</label>
                    <input type="date" class="form-control" name="year" required="required" value="{{ $qualification->year }}">
                </div>
                <div class="col-6">
                    <label for="title_ar" class="form-label">الاسم بالعربي</label>
                    <input type="text" class="form-control" name="title_ar" required="required" value="{{ $qualification->title_ar }}">
                </div>
                <div class="col-6">
                    <label for="title_en" class="form-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" name="title_en" required="required" value="{{ $qualification->title_en }}">
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="description_ar" class="form-label">التفاصيل بالعربي</label>
                    <textarea type="text" rows="5" class="form-control"
                              name="description_ar" required="required">{{ $qualification->description_ar }}</textarea>
                </div>
                <div class="col-6">
                    <label for="description_en" class="form-label">التفاصيل بالانجليزية</label>
                    <textarea type="text" rows="5" class="form-control"
                              name="description_en" required="required">{{ $qualification->description_en }}</textarea>
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
