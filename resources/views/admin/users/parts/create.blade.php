<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">صورة :</label>
                        <input type="file" name="image" class="dropify" value="{{ asset('users/default/avatar.jpg') }}"
                               data-default-file="{{ asset('users/default/avatar.jpg') }}"/>
                    </div>
                    <span class="form-text text-danger text-center">
                        Recomended : 2048 X 1200 to up Px <br>
                        Extension : png, gif, jpeg,jpg,webp
                    </span>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name" class="form-control-label">اسم الطالب</label>
                    <input type="text" class="form-control" placeholder="اسم الطالب" name="name"
                           >
                    <div class="row">
                        <div class="col-7">
                            <label for="code" class="form-control-label">كود الطالب</label>
                            <input type="text" class="form-control CodeStudent" placeholder="كود الطالب" name="code"
                                 disabled  required>
                            <input type="hidden" class="form-control CodeStudent" placeholder="كود الطالب" name="code">
                        </div>
                        <div class="col-5">
                            <button type="button"
                                    class="btn btn-sm btn-primary form-control mt-5 GeneCode">
                                generate code
                            </button>
                        </div>
                        <div class="col-12">
                            <label for="birth_date" class="form-control-label">تاريخ الميلاد</label>
                            <input type="date" class="form-control" placeholder="تاريخ الميلاد" name="birth_date"
                                  >
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <input type="text" class="form-control phoneInput" name="phone" placeholder="201XXXXXXXXX"
                           >
                </div>
                <div class="col-md-12">
                    <label for="father_phone" class="form-control-label">رقم هاتف ولي الامر</label>
                    <input type="text" class="form-control" name="father_phone" placeholder="201XXXXXXXXX"
                           >
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="name" class="form-control-label">الصف الدراسي</label>
                    <select class="form-control SeasonSelect select2" name="season_id">
                        <option value="" data-name="" selected disabled>اختار الصف</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id}}">{{ $season->name_ar }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label for="country_id" class="form-control-label">اختر المحافظه</label>
                    <select class="form-control select2" name="city_id">
                        <option value="" selected disabled>اختار المحافظه</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id}}">{{ $city->name_ar }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label for="country_id" class="form-control-label">المدينة</label>
                    <select class="form-control select2" name="country_id">
{{--                        <option value="" selected disabled>اختار المدينة</option>--}}
{{--                        @foreach($countries as $country)--}}
{{--                            <option value="{{ $country->id}}">{{ $country->name_ar }}</option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>
            </div>
            <div class="row">
{{--                <div class="col-md-6">--}}
{{--                    <label for="date_start_code" class="form-control-label">تاريخ بداية الاشتراك</label>--}}
{{--                    <input type="date" class="form-control" name="date_start_code" placeholder="تاريخ بداية الاشتراك">--}}
{{--                </div>--}}
{{--                <div class="col-md-6">--}}
{{--                    <label for="date_end_code" class="form-control-label">تاريخ نهاية الاشتراك</label>--}}
{{--                    <input type="date" class="form-control" name="date_end_code" placeholder="تاريخ نهاية الاشتراك">--}}
{{--                </div>--}}

                <div class="col-md-12 mt-3">
                    <label for="name" class="form-control-label">شهور الاشتراك</label>
                    <select class="form-control select2" name="subscription_months_groups[]" id="subscription_months_groups[]" multiple>
                        @foreach($groupOfMonths as $key=>$value)
                            <option value="{{ $key < 10 ? "0".$key : $key}}">{{ $value }}</option>
                        @endforeach
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

    $('.dropify').dropify()

</script>


<script>

    $('.GeneCode').on('click', function () {
        // var data = $(this).val();
        // var phone = $('.phoneInput').val();
        min = Math.ceil(50000);
        max = Math.floor(100000000000);
        var code = Math.floor(Math.random() * (max - min + 1)) + min;
        var userCode = ''
        $('.CodeStudent').val(code);
    })

    $(document).ready(function() {
        $('.select2').select2();
    });



    $(document).ready(function () {
        $('select[name="city_id"]').on('change', function () {
            let city_id = $(this).val();
            if (city_id) {
                $.ajax({
                    url: "{{route('getAllCountriesOfCity')}}",
                    type: "GET",
                    data: {
                        "city_id": city_id
                    },
                    dataType: "json",
                    success: function (data) {
                        $('select[name="country_id"]').empty();
                        $.each(data, function (key, value) {
                            $('select[name="country_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                });
            } else {
                console.log('AJAX load did not work');
            }
        });
    });

</script>
