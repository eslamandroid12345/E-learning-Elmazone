<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('termSubjectClassStore', $id) }}">
        @csrf
        <input type="hidden" name="season_id" value="{{ $id }}"/>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الاسم بالعربية</label>
                    <input type="text" class="form-control" name="name_ar">
                </div>
                <div class="col-md-6">
                    <label for="name_en" class="form-control-label">الاسم بالانجليزية</label>
                    <input type="text" class="form-control" name="name_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الترم</label>
                   <input type="text" value="{{ $data['terms']->name_ar }}" class="form-control"/>
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control">
                        <option selected disabled style="text-align: center">اختار الصف</option>
{{--                        @foreach($seasons as $season)--}}
{{--                            <option value="{{ $season->id }}"--}}
{{--                                    style="text-align: center">{{ $season->name_ar }}</option>--}}
{{--                        @endforeach--}}
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">الصورة :</label>
                        <input type="file" name="image" class="dropify"
                               data-default-file=""/>

                    </div>
                    <span class="form-text text-danger text-center"> Recomended : 2048 X 1200 to up Px <br> Extension : png, gif, jpeg,
                                        jpg,webp</span>

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
