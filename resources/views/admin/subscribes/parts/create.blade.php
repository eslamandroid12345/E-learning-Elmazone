<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('subscribe.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">الصف الدراسي</label>
                    <Select name="season_id" class="form-control selectSeason select2">
                        <option selected disabled >اختر الصف الدراسي</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->name_ar }}</option>
                        @endforeach
                    </Select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="note" class="form-control-label">اختر تيرم معين لهذا الصف*</label>
                    <Select name="term_id" class="form-control selectTerm select2">
                        <option selected disabled>اختر تيرم معين لهذا الصف*</option>
                    </Select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="">السعر داخل السنتر</label>
                    <input type="number" class="form-control" name="price_in_center"/>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">السعر خارج السنتر</label>
                    <input type="number" class="form-control" name="price_out_center"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="month">الشهر</label>
                    <select class="form-control select2" name="month">

                        @for($i = 1 ; $i <= 12 ; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor

                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="free">مجانا</label> :<br>
                    <select class="form-control select2" name="free">
                        <option value="1" >نعم</option>
                        <option value="0" >لا</option>
                    </select>
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
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<script>
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
