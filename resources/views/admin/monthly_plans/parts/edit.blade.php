<div class="modal-body">
    <form id="updateForm" class="updateForm" method="POST" action="{{ route('monthlyPlans.update', $monthlyPlan->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $monthlyPlan->id }}" name="id">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">العنوان بالعربي</label>
                    <input type="text" class="form-control" value="{{ $monthlyPlan->title_ar }}" name="title_ar" style="text-align: center">
                </div>
                <div class="col-md-6">
                    <label for="name_ar" class="form-control-label">العنوان بالانجليزي</label>
                    <input type="text" class="form-control" value="{{ $monthlyPlan->title_en }}" name="title_en" style="text-align: center">
                </div>
                <div class="col-md-12">
                    <label for="name_ar" class="form-control-label">اللون</label>
                    <input type="color" class="form-control" value="{{ $monthlyPlan->background_color }}" name="background_color" style="text-align: center">
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">الفصل</label>
                    <select name="season_id" class="form-control">
                        @foreach ($data['seasons'] as $season)
                            <option value="{{ $season->id }}" {{ $monthlyPlan->season_id ==  $season->id ? 'selected' : ''}}>{{ $season->name_ar }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">الترم</label>
                    <Select name="term_id" class="form-control" required>
                        <option selected disabled style="text-align: center">اختار الترم</option>
                        @foreach ($data['terms'] as $term)
                            <option value="{{ $term->id }}" {{ $monthlyPlan->term_id ==  $term->id ? 'selected' : ''}}style="text-align: center">
                                {{ $term->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">من</label>
                    <input type="date" class="form-control" value="{{ $monthlyPlan->start }}" name="start">
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">الى</label>
                    <input type="date" value="{{ $monthlyPlan->end }}" class="form-control" name="end">
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">الوصف بالعربي</label>
                    <textarea name="description_ar" class="form-control" rows="8">{{ $monthlyPlan->description_ar }}</textarea>
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-control-label">الوصف بالانجليزي</label>
                    <textarea name="description_en" class="form-control" rows="8">{{ $monthlyPlan->description_en }}</textarea>
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
