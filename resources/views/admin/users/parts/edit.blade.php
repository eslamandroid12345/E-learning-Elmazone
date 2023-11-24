<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Image :</label>
                        <input type="file" name="image" class="dropify"
                            value="{{ $user->image !== null ? asset($user->image) : asset('users/default/avatar3.jpg') }}"
                            data-default-file="{{ $user->image !== null ? asset($user->image) : asset('users/default/avatar3.jpg') }}" />
                    </div>
                    <span class="form-text text-danger text-center">
                        Recomended : 2048 X 1200 to up Px <br>
                        Extension : png, gif, jpeg,jpg,webp
                    </span>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="name" class="form-control-label">اسم الطالب</label>
                    <input type="text" class="form-control" placeholder="اسم الطالب" name="name"
                        value="{{ $user->name }}">
                    <div class="row">

                        <div class="col-12 mt-3">
                            <label for="birth_date" class="form-control-label">تاريخ الميلاد</label>
                            <input type="date" class="form-control" value="{{ $user->birth_date }}"
                                placeholder="تاريخ الميلاد" name="birth_date" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="phone" class="form-control-label">رقم الهاتف</label>
                    <input type="text" class="form-control phoneInput" value="{{ $user->phone }}" name="phone"
                        placeholder="201XXXXXXXXX">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="father_phone" class="form-control-label">رقم هاتف ولي الامر</label>
                    <input type="text" class="form-control" value="{{ $user->father_phone }}" name="father_phone"
                        placeholder="201XXXXXXXXX">

                </div>

                <input type="hidden" value="{{ $user->code }}" name="code">

            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <label for="name" class="form-control-label">الصف الدراسي</label>
                    <select class="form-control SeasonSelect select2" name="season_id">
                        <option value="" data-name="" disabled>اختار الصف</option>
                        @foreach ($seasons as $season)
                            <option {{ $user->season_id == $season->id ? 'selected' : '' }}
                                value="{{ $season->id }}">
                                {{ $season->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="country_id" class="form-control-label">اختار المدينة</label>
                    <select class="form-control select2" name="country_id">
                        <option disabled>اختار المدينة</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ $user->country_id == $country->id ? 'selected' : '' }}>
                                {{ $country->name_ar }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="login_status" class="form-control-label">حاله الحساب</label>
                    <select class="form-control select2" name="login_status">
                        @foreach ($login_status as $status)
                            <option value="{{ $status }}"
                                {{ $user->login_status == $status ? 'selected' : '' }}>
                                {{ $status == 1 ? 'نشط' : 'غير نشط' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="login_status" class="form-control-label">تسجيل الطالب معنا</label>
                    <select class="form-control select2" name="center">
                        @foreach ($user_status as $center)
                            <option value="{{ $center }}" {{ $user->center == $center ? 'selected' : '' }}>
                                {{ $center == 'in' ? 'داخل السنتر' : 'خارج السنتر' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="login_status" class="form-control-label">حالة الطالب</label>
                    <select class="form-control select2" name="user_status" id="user_status">
                        @foreach ($user_active as $active)
                            <option value="{{ $active }}" {{ $user->user_status == $active ? 'selected' : '' }}>
                                {{ $active == 'active' ? 'الحساب مفعل' : 'الحساب غير مفعل' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="name" class="form-control-label">اكتب ملاحظه في حاله الغاء التفعيل لطالب معين*</label>
                    <input type="text" class="form-control"  name="user_status_note" value="{{ $user->user_status_note }}">

                </div>
            </div>
            <div class="row">
{{--                <div class="col-md-6 mt-3">--}}
{{--                    <label for="date_start_code" class="form-control-label">تاريخ بداية الاشتراك</label>--}}
{{--                    <input type="date" class="form-control" value="{{ $user->date_start_code }}"--}}
{{--                        name="date_start_code" placeholder="تاريخ بداية الاشتراك">--}}
{{--                </div>--}}
{{--                <div class="col-md-6 mt-3">--}}
{{--                    <label for="date_end_code" class="form-control-label">تاريخ نهاية الاشتراك</label>--}}
{{--                    <input type="date" class="form-control" value="{{ $user->date_end_code }}"--}}
{{--                        name="date_end_code" placeholder="تاريخ نهاية الاشتراك">--}}
{{--                </div>--}}


                <div class="col-md-12 mt-3">
                    <label for="name" class="form-control-label">شهور الاشتراك</label>
                    <select class="form-control select2" name="subscription_months_groups[]" id="subscription_months_groups[]" multiple>
                        @foreach($groupOfMonths as $key=>$value)
                            <option value="{{ $key < 10 ? "0".$key : $key}}" {{$user->subscription_months_groups != null ? in_array($key,json_decode($user->subscription_months_groups)) ? 'selected' : '' : ''}}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">تحديث</button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $('.dropify').dropify()

    // this function when use
    $(document).ready(function() {
        $('#user_status').on('change', function() {
            var selectedOption = $(this).val();
            if (selectedOption === 'not_active') {
                $('#textareaDiv').show();
            } else {
                $('#textareaDiv').hide();
            }
        });
    });
</script>
