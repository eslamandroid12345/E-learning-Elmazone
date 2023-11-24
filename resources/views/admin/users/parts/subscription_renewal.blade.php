<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="modal-body">
    <form id="update_renwal" class="update_renwal" method="POST" action="{{ route('subscr_renew',$user->id) }}">
        @csrf
        <input type="hidden" class="userId" name="id" value="{{ $user->id }}">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="date_end_code" class="form-control-label">الشهر</label>

                    <select name="month[]" class="selectMonth form-control select2" multiple="multiple">
                        <option value="" disabled selected>أختر الشهر</option>
                        @foreach($months as $month)
                            <option class="form-control" value="{{ $month->id }}">
                                {{ ' شهر '. $month->month }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date_end_code" class="form-control-label">سعر الشهر</label>
                    <select name="price" class="form-control priceMonth">
                        <option value="" disabled selected>أختر السعر</option>

                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date_end_code" class="form-control-label">السنة الدراسية</label>
                    <select name="year" class="form-control">
                        <option value="" disabled selected>أختر السنة</option>
                        @for($i = 2022; $i <= 2050; $i++)
                            <option class="form-control" value="{{ $i }}">
                                {{ ' سنة ' . $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary">تحديث</button>
        </div>
    </form>
</div>

<script>

    $(document).on('change','.selectMonth',function (){

        let id = $('.userId').val();
        let month = $(this).val();

        $.ajax({
            url: '{{ route('priceMonth') }}',
            method: 'GET',
            data : {
                'id': id,
                'month': month,
            }, success: function(data) {
                $('.priceMonth').html(data);
            }
        })
    })



    $(document).ready(function() {
        $('.select2').select2();
    });

</script>

