<!doctype html>
<html lang="en" dir="ltr">

<head>

    {{--start style css-- rtl--}}
    <link href="{{asset('assets/admin/css-rtl/style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css-rtl/skin-modes.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css-rtl/dark-style.css')}}" rel="stylesheet"/>

    <style>

        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            width: 100%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table th {
            text-align: right;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: #009879;
        }

        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

</head>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body" id="cardPrint">
                            <div class="invoice-title">
                                <h3 class="float-end font-size-16">{{ ' اسم الطالب : ' . $user->name }}</h3>
                                <h6 class="float-end font-size-16">{{ ' كود الطالب : ' . $user->code }} </h6>
                                <h6 class="float-end font-size-16">
                                    {{ ' الصف الدراسي : ' . $user->season->name_ar . ' - ' . $term->name_ar }}
                                </h6>
                            </div>
                            <hr>
                            <div class="py-2 mt-3">
                                <h3 class="font-size-15 col-6 fw-bold">معلومات الاشتراك</h3>
                            </div>
                            <div class="styled-table">
                                <table class="styled-table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 70px;">#</th>
                                        <th>الشهر</th>
                                        <th class="text-end">المبلغ</th>
                                        <th class="text-end">تاريخ الدفع</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subscriptions as $subscription)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subscription->month }}</td>
                                            <td class="text-end">{{ $subscription->price }}</td>
                                            <td>{{ $subscription->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="py-2 mt-3">
                                <h3 class="font-size-15 col-6 fw-bold">معلومات المشاهدة</h3>
                            </div>

                            <div class="row">
                                <div class="styled-table col-md-12">
                                    <table class="styled-table">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 70px;">#</th>
                                            <th>اسم الفيديو</th>
                                            <th class="text-end">وقت المشاهدة</th>
                                            <th class="text-end">مدة الفيديو</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($videos as $video)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $video->video->name_ar ?? '' }}</td>
                                                <td class="text-end">{{ $video->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $video->minutes ?? '' . ' دقيقة ' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <td></td>
                                        <td>المجموع :
                                            {{ $videos->count() . '  فيديو  ' }}
                                        </td>
                                        <td></td>
                                        <td>مجموع دقائق المشاهده :
                                            {{ $totalTimeFormatted }}
                                        </td>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12">
                                <h3 class="">معلومات الانجازات</h3>
                                <div class="row">
                                    <div class="col-6">
                                        <div id="chartpie" style="height: 370px; "></div>
                                    </div>
                                    <div class="col-6">
                                        <div id="chartCoulmn" style="height: 370px;"></div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="py-2 mt-3">
                                <h3 class="font-size-15 col-6 fw-bold">معلومات الامتحانات</h3>
                            </div>
                            <div class="styled-table">
                                <table class="styled-table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 70px;">#</th>
                                        <th>اسم الامتحان</th>
                                        <th>نوع الامتحان</th>
                                        <th>درجة الامتحان</th>
                                        <th>تاريخ الامتحان</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($exams as $exam)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $exam->online_exam->name_ar ?? $exam->all_exam->name_ar ?? $exam->life_exam->name_ar }}</td>
                                            @if($exam->online_exam_id)
                                                <td>اونلاين</td>
                                            @elseif($exam->all_exam_id)
                                                <td>شامل</td>
                                            @else
                                                <td>لايف</td>
                                            @endif
                                            <td>{{ ($exam->online_exam->degree ?? $exam->all_exam->degree ?? $exam->life_exam->degree) }}
                                                / {{ $exam->full_degree }}</td>
                                            <td>{{ $exam->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                    @foreach($paperExams as $paper)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $paper->papel_sheet_exam->name_ar }}</td>
                                            <td>ورقي</td>
                                            <td>{{ $paper->papel_sheet_exam->degree }}
                                                / {{ $paper->degree }}</td>
                                            <td>{{ $paper->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td> مجموع الامتحانات :</td>
                                    <td>{{ $exams->count() + $paperExams->count() }}</td>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
</div>
@include('admin.layouts_admin.scripts')
<script src="{{ asset('assets/canvajs/jquery.canvasjs.min.js') }}"></script>
<script src="{{ asset('assets/canvajs/canvasjs.min.js') }}"></script>

<script>
    $(document).ready(function (){
        var lessonCount = {{ $lessonCount }};
        var classCount = {{ $classCount }};
        var options = {
            animationEnabled: true,
            title: {
                text: "معلومات انجازات الطالب"
            },
            axisY: {
                title: "عدد الدروس والوحدات ",
                suffix: ""
            },
            axisX: {
                title: "الدروس والوحدات"
            },
            data: [{
                type: "column",
                yValueFormatString: "#,##0.0#",
                dataPoints: [
                    {label: "الدروس", y: lessonCount},
                    {label: "الوحدات", y: classCount},
                ]
            }]
        };
        $("#chartCoulmn").CanvasJSChart(options);
    })


</script>

<script>
    $(document).ready(function () {
        var lessonCount = {{ $lessonCount }};
        var classCount = {{ $classCount }};
        var options = {
            animationEnabled: true,
            title: {
                text: "معلومات انجازات الطالب"
            },
            axisY: {
                title: "عدد الدروس والوحدات ",
                suffix: ""
            },
            axisX: {
                title: "الدروس والوحدات"
            },
            data: [{
                type: "pie",
                yValueFormatString: "#,##0.0#",
                dataPoints: [
                    {label: "الدروس", y: lessonCount},
                    {label: "الوحدات", y: classCount},
                ]
            }]
        };
        $("#chartpie").CanvasJSChart(options);
    })
</script>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<script>
    $(document).ready(function () {
        function CreatePDFfromHTML() {
            var HTML_Width = $('#cardPrint').width();
            var HTML_Height = $('#cardPrint').height();
            var top_left_margin = 15;
            var PDF_Width = HTML_Width + (top_left_margin * 2);
            var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
            var canvas_image_width = HTML_Width;
            var canvas_image_height = HTML_Height;

            var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

            html2canvas($('#cardPrint')[0]).then(function (canvas) {
                var imgData = canvas.toDataURL("image/jpeg", 1.0);
                var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
                for (var i = 1; i <= totalPDFPages; i++) {
                    pdf.addPage(PDF_Width, PDF_Height);
                    pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                }
                pdf.save("ReportStudent.pdf");
            });
        }
        setTimeout(function (){
            CreatePDFfromHTML();
        },3000)

    });



</script>

</html>
