<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST"
        action="{{ route('exam_schedules.update', $exam_schedule->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $exam_schedule->id }}" name="id">
        <div class="form-group">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">صورة :</label>
                    <input type="file" name="image" class="dropify"
                           data-default-file="{{ asset($exam_schedule->image) }}" />
                </div>
                <span class="form-text text-danger text-center">
                    Recomended : 2048 X 1200 to up Px <br>
                    Extension : png, gif, jpeg,jpg,webp
                </span>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="title_ar" class="form-control-label">العنوان بالعربية</label>
                    <input type="text" class="form-control" value="{{ $exam_schedule->title_ar }}" name="title_ar">
                </div>
                <div class="col-md-6">
                    <label for="title_en" class="form-control-label">العنوان بالانجليزية</label>
                    <input type="text" class="form-control" value="{{ $exam_schedule->title_en }}" name="title_en">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الصف</label>
                    <Select name="season_id" class="form-control">
                        @foreach ($data['seasons'] as $season)
                            <option value="{{ $season->id }}" {{ $exam_schedule->season_id == $season->id ? 'selected' : '' }} style="text-align: center">{{ $season->name_ar }}
                            </option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">الترم</label>
                    <Select name="term_id" class="form-control">
                        @foreach ($data['terms'] as $term)
                            <option value="{{ $term->id }}" {{ $exam_schedule->term_id == $term->id ? 'selected' : '' }} style="text-align: center">
                                {{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">التاريخ</label>
                    <input type="datetime-local" class="form-control" value="{{ $exam_schedule->date_time }}" name="date_time">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="title_ar" class="form-control-label">الوصف بالعربية</label>
                    <textarea rows="8" class="form-control" name="description_ar">{{ $exam_schedule->description_ar }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="title_en" class="form-control-label">الوصف بالانجليزية</label>
                    <textarea rows="8" class="form-control" name="description_en">{{ $exam_schedule->description_en }}</textarea>
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

    $(document).ready(function () {
        $('select[name="season_id"]').on('change', function () {
            var season_id = $(this).val();
            if (season_id) {
                $.ajax({
                    url: "{{ URL::to('terms/season/') }}/" + season_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('select[name="term_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="term_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });
</script>
