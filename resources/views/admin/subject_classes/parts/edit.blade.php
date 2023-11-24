<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST"
          action="{{ route('subjectsClasses.update', $subjectsClass->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $subjectsClass->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="title_ar" class="form-control-label">العنوان بالعربية مثال(الفصل الاول)</label>
                    <input type="text" class="form-control" value="{{ $subjectsClass->title_ar }}" name="title_ar" required>
                </div>
                <div class="col-md-6">
                    <label for="title_en" class="form-control-label">العنوان بالانجليزية</label>
                    <input type="text" class="form-control" value="{{ $subjectsClass->title_en }}" name="title_en" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الاسم بالعربية</label>
                    <input type="text" class="form-control" value="{{ $subjectsClass->name_ar }}" name="name_ar">
                </div>
                <div class="col-md-6">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" value="{{ $subjectsClass->name_en }}" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الترم</label>
                    <Select name="term_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الترم</option>
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}"
                                    {{ $subjectsClass->term_id == $term->id ? 'selected' : '' }} style="text-align: center">
                                {{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الصف</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}"
                                    {{ $subjectsClass->season_id == $season->id ? 'selected' : '' }}
                                    style="text-align: center">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="head">لون الخلفية</label>
                    <input type="color" class="form-control"  name="background_color"
                           value="{{ $subjectsClass->background_color }}" required>
                </div>
                <div class="col-md-6">
                    <label for="note_ar" class="form-control-label">ملاحظة بالعربية</label>
                    <input type="text" class="form-control" value="{{ $subjectsClass->note }}" name="note">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="">الصورة :</label>
                    <input type="file" name="image" class="dropify"
                           data-default-file="{{ asset('classes/'.$subjectsClass->image) }}"/>

                    <span class="form-text text-danger text-center"> Recomended : 2048 X 1200 to up Px <br> Extension :
                        png, gif, jpeg,
                        jpg,webp</span>

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
