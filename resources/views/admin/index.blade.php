@extends('admin.layouts_admin.master')
@section('title')
    {{($setting->title) ?? 'الصفحة الرئيسية'}} | لوحة التحكم
@endsection
@section('page_name')
    الرئـيسية
@endsection
@section('content')

    @php

    @endphp
    <div class="row">

        <!--  city chart -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="card" style="height: 444px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div id="myChartPie"></div>
                        </div>
                        <div class="col">
                            <div id="myChartCol"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  city chart -->

        <!--  city chart -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h1 class="text-center font-weight-bold"> احصائيات عامة </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  city chart -->


        <!-- users Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-graduation-cap d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">كل الطلاب</h6>
                            <h3 class="mb-2 number-font">{{ $users }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                {{ progress($users) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('users.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- users in center Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-graduation-cap d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">الطلاب داخل السنتر</h6>
                            <h3 class="mb-2 number-font">{{ $usersIn }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                  {{ progress($usersIn) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('users.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- users out center Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-graduation-cap d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">الطلاب خارج السنتر</h6>
                            <h3 class="mb-2 number-font">{{ $usersOut }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                {{ progress($usersOut) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('users.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- paperExam Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-scroll d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder"> عدد الامتحانات الورقية</h6>
                            <h3 class="mb-2 number-font">{{ $paperExam }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                   {{ progress($paperExam) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('papelSheetExam.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- onlineExam Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-globe d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder"> عدد الامتحانات الاونلاين</h6>
                            <h3 class="mb-2 number-font">{{ $onlineExam }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                 {{ progress($onlineExam) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('onlineExam.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- liveExam Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-globe d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder"> عدد الامتحانات اللايف</h6>
                            <h3 class="mb-2 number-font">{{ $liveExam }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                               {{ progress($liveExam) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('lifeExam.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- videoParts Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-video d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder"> عدد الفيديوهات (الدروس)</h6>
                            <h3 class="mb-2 number-font">{{ $videoParts }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                 {{ progress($videoParts) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('videosParts.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- videoBasic Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-video d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">عدد الفيديوهات (الاساسيات)</h6>
                            <h3 class="mb-2 number-font">{{ $videoBasic }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                    {{ progress($videoBasic) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('videosParts.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- videoResource Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-video d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">عدد الفيديوهات (المراجعات)</h6>
                            <h3 class="mb-2 number-font">{{ $videoResource }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                  {{ progress($videoResource) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('videosParts.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- lesson Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-book-open d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">عدد الدروس</h6>
                            <h3 class="mb-2 number-font">{{ $lesson }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                  {{ progress($lesson) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('lessons.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!--  class Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-grip-lines d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">عدد الوحدات</h6>
                            <h3 class="mb-2 number-font">{{ $class }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                  {{ progress($class) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('subjectsClasses.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!--  suggest Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-comment d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">الاقتراحات</h6>
                            <h3 class="mb-2 number-font">{{ $suggest }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                   {{ progress($suggest) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('suggestions.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!--  suggest Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-question-circle d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">بنك الاسئلة</h6>
                            <h3 class="mb-2 number-font">{{ $question }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                 {{ progress($question) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('questions.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--  suggest Count -->

        <!--  section Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-question-circle d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">عدد القاعات</h6>
                            <h3 class="mb-2 number-font">{{ $section }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                    {{ progress($section) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('section.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--  section Count -->

        <!--  guide Count -->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <i style="font-size: xxx-large" class="fa fa-question-circle d-flex"></i>
                        <div class="col">
                            <h6 class="bold font-weight-bolder">عدد المصادر المراجع</h6>
                            <h3 class="mb-2 number-font">{{ $guide }}</h3>
                            <div class="progress h-2">
                                <div class="progress-bar bg-primary-gradient
                                   {{ progress($guide) }}
                                " role="progressbar"></div>
                            </div>
                        </div>
                        <a class="btn btn-sm btn-primary-light h-6 d-flex" href="{{ route('guide.index') }}">
                            المزيد
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--  guide Count -->


    </div>

@endsection
@section('js')

    <script src="{{ asset('assets/canvajs/jquery.canvasjs.min.js') }}"></script>
    <script src="{{ asset('assets/canvajs/canvasjs.min.js') }}"></script>


    <script>
        var country_name = @php echo json_encode(array_values($cities['country_name'])) @endphp;
        var country_total = @php echo json_encode(array_values($cities['country_total'])) @endphp;
        var city_total = @php echo json_encode(array_values($city_total)) @endphp;
        var city_name = @php echo json_encode(array_keys($city_total)) @endphp;
        console.log(city_total);
        console.log(city_name);
        var dataPoints = [];
        var dataPoints1 = [];

        for (var i = 0; i < country_name.length; i++) {
            dataPoints.push({
                label: country_name[i], // Set the label from your data
                y: country_total[i] // Set the y value from your data
            });
        }

        for (var i = 0; i < city_name.length; i++) {
            dataPoints1.push({
                label: city_name[i], // Set the label from your data
                y: city_total[i] // Set the y value from your data
            });
        }
        var options = {
            animationEnabled: true,
            title: {
                text: "احصائيات المدن"
            },
            data: [{
                type: "pie",
                yValueFormatString: "#,##0.0#",
                dataPoints: dataPoints // Assign the dataPoints array here
            }]
        };

        $("#myChartPie").CanvasJSChart(options);


        var options1 = {
            animationEnabled: true,
            title: {
                text: "احصائيات المحافظات"
            },
            data: [{
                type: "column",
                yValueFormatString: "#,##0.0#",
                dataPoints: dataPoints1 // Assign the dataPoints array here
            }]
        };

        $("#myChartCol").CanvasJSChart(options1);

    </script>

@endsection

