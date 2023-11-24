<div class="modal-body-unvilable">
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">#</th>
                        <th class="min-w-50px">الاسم</th>
                        <th class="min-w-50px">الكود</th>
                        <th class="min-w-50px">الهاتف</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unavailableUsers as $user)
                        <tr>
                            <td>{{ $user->user->id }}</td>
                            <td>{{ $user->user->name }}</td>
                            <td>{{ $user->user->code }}</td>
                            <td>{{ $user->user->phone }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

<script></script>
