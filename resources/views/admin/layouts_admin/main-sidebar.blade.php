<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="#">
            <img src="{{ asset('assets/admin/images/logo-1.jpg') }}" class="header-brand-img desktop-logo"
                 alt="logo">
            <img src="{{ asset('assets/admin/images/logo-1.jpg') }}" class="header-brand-img toggle-logo"
                 alt="logo">
            <img src="{{ asset('assets/admin/images/logo-1.jpg') }}" class="header-brand-img light-logo"
                 alt="logo">
            <img src="{{ asset('assets/admin/images/logo-1.jpg') }}" class="header-brand-img light-logo1"
                 alt="logo">
        </a>
        <!-- LOGO -->
    </div>
    <ul class="side-menu">
        @can('الاعدادات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('adminHome') }}">
                    <i class="fa fa-home side-menu__icon"></i>
                    <span class="side-menu__label">الرئيسية</span>
                </a>
            </li>
        @endcan

            @can('الاعدادات')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('setting.index') }}">
                        <i class="fa fa-wrench side-menu__icon"></i>
                        <span class="side-menu__label">
                        الاعدادات
                    </span>
                    </a>
                </li>
            @endcan

            @can('المدن')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('city.index') }}">
                        <i class="fa fa-globe side-menu__icon"></i>
                        <span class="side-menu__label">
                       المحافظات
                    </span>
                    </a>
                </li>
            @endcan


            @can('ملفات ورقية')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('countries.index') }}">
                        <i class="fa fa-globe side-menu__icon"></i>
                        <span class="side-menu__label">
                        المدن
                    </span>
                    </a>
                </li>
            @endcan

        @can('الطلاب')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('users.index') }}">
                    <i class="fe fe-users side-menu__icon"></i>
                    <span class="side-menu__label">
                    الطلاب
                </span>
                </a>
            </li>
        @endcan


            @can('الادوار و الصلاحيات')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('roles.index') }}">
                        <i class="fa fa-user-secret side-menu__icon"></i>
                        <span class="side-menu__label">
                        الادوار و الصلاحيات
                    </span>
                    </a>
                </li>
            @endcan


            @can('الادمن')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('adminLog') }}">
                        <i class="fa fa-user-secret side-menu__icon"></i>
                        <span class="side-menu__label">
                         حركات الموظفين باللوحه
                    </span>
                    </a>
                </li>
            @endcan

            @can('الادمن')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('admins.index') }}">
                        <i class="fa fa-user-secret side-menu__icon"></i>
                        <span class="side-menu__label">
                        موظفين لوحه التحكم
                    </span>
                    </a>
                </li>
            @endcan

        @can('الاعدادات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('motivational.index') }}">
                    <i class="fa fa-heart side-menu__icon"></i>
                    <span class="side-menu__label">الجمل التحفيزية</span>
                </a>
            </li>
        @endcan

        @can('الوحدات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('exam_schedules.index') }}">
                    <i class="fa fa-clipboard-list side-menu__icon"></i>
                    <span class="side-menu__label">العد التنازلي</span>
                </a>
            </li>
        @endcan

        @can('الصفوف الدراسيه')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('seasons.index') }}">
                    <i class="fa fa-save side-menu__icon"></i>
                    <span class="side-menu__label">الصفوف الدراسيه</span>
                </a>
            </li>
        @endcan

        @can('الترم')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('terms.index') }}">
                    <i class="fa fa-list-ol side-menu__icon"></i>
                    <span class="side-menu__label">التيرمات</span>
                </a>
            </li>
        @endcan

        @can('الوحدات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('subjectsClasses.index') }}">
                    <i class="fa fa-book-reader side-menu__icon"></i>
                    <span class="side-menu__label">الفصول</span>
                </a>
            </li>
        @endcan

        @can('الدروس')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('lessons.index') }}">
                    <i class="fe fe-book side-menu__icon"></i>
                    <span class="side-menu__label">
                    الدروس
                </span>
                </a>
            </li>
        @endcan

        @can('اقسام الفيديوهات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('videosParts.index') }}">
                    <i class="icon icon-control-play side-menu__icon"></i>
                    <span class="side-menu__label">
                   فيديوهات الشرح
                </span>
                </a>
            </li>
        @endcan

            @can('الفيديوهات الاساسية')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('videoBasic.index') }}">
                        <i class="fa fa-file-video side-menu__icon"></i>
                        <span class="side-menu__label">
                        فيديوهات الاساسيات
                    </span>
                    </a>
                </li>
            @endcan
            @can('مصادر الفيديوهات')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('videoResource.index') }}">
                        <i class="fa fa-photo-video side-menu__icon"></i>
                        <span class="side-menu__label">
                        فيديوهات المراجعة
                    </span>
                    </a>
                </li>
            @endcan


            @can('الخطة الشهرية')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('monthlyPlans.index') }}">
                        <i class="icon icon-calendar side-menu__icon"></i>
                        <span class="side-menu__label">
                        الخطة الشهرية
                    </span>
                    </a>
                </li>
            @endcan
            @can('الاقتراحات')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('suggestions.index') }}">
                        <i class="fe fe-message-circle side-menu__icon"></i>
                        <span class="side-menu__label">
                        الاقتراحات
                    </span>
                    </a>
                </li>
            @endcan





        @can('الاشعارات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('notifications.index') }}">
                    <i class="icon icon-bell side-menu__icon"></i>
                    <span class="side-menu__label">
                        الاشعارات
                    </span>
                </a>
            </li>
        @endcan


{{--        @can('الفيديوهات الاساسية ملفات ورقية')--}}
{{--            <li class="slide">--}}
{{--                <a class="side-menu__item" href="{{ route('videoBasicPdf.index') }}">--}}
{{--                    <i class="icon icon-control-play side-menu__icon"></i>--}}
{{--                    <span class="side-menu__label">--}}
{{--                        الملفات الورقيه لفيديوهات الاساسيات--}}
{{--                    </span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        @endcan--}}



        @can('امتحانات الاونلاين')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('onlineExam.index') }}">
                    <i class="fa fa-scroll side-menu__icon"></i>
                    <span class="side-menu__label">
                       الامتحانات الاونلاين
                    </span>
                </a>
            </li>
        @endcan
        @can('امتحانات اللايف')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('lifeExam.index') }}">
                    <i class="fa fa-headset side-menu__icon"></i>
                    <span class="side-menu__label">
                       الامتحانات الايف
                    </span>
                </a>
            </li>
        @endcan

        @can('القاعات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('section.index') }}">
                    <i class="fa fa-place-of-worship side-menu__icon"></i>
                    <span class="side-menu__label">
                    القاعات
                </span>
                </a>
            </li>
        @endcan

        @can('امتحانات الورقية')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('papelSheetExam.index') }}">
                    <i class="fa fa-paper-plane side-menu__icon"></i>
                    <span class="side-menu__label">
                        الامتحانات الورقيه بالقاعات
                    </span>
                </a>
            </li>
        @endcan
        @can('كل الامتحانات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('allExam.index') }}">
                    <i class="fa fa-scroll side-menu__icon"></i>
                    <span class="side-menu__label">
                        الامتحانات الشاملة
                    </span>
                </a>
            </li>
        @endcan



        @can('الاتصالات الهاتفية')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('phoneCommunications.index') }}">
                    <i class="icon icon-phone side-menu__icon"></i>
                    <span class="side-menu__label">
                       هواتف السنتر والسكيرتاريه
                    </span>
                </a>
            </li>
        @endcan


        @can('الشاشات الافتتاحيه')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('onBoarding.index') }}">
                    <i class="fa fa-images side-menu__icon"></i>
                    <span class="side-menu__label">
                       الشاشه الافتتاحيه
                    </span>
                </a>
            </li>
        @endcan
        @can('سلايدر')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('slider.index') }}">
                    <i class="fa fa-images side-menu__icon"></i>
                    <span class="side-menu__label">
                       البانر المتحرك
                    </span>
                </a>
            </li>
        @endcan


            @can('الاعلانات')
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('ads.index') }}">
                        <i class="fa fa-ad side-menu__icon"></i>
                        <span class="side-menu__label">
                        الاعلانات
                    </span>
                    </a>
                </li>
            @endcan

        @can('الاعدادات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('qualification.index') }}">
                    <i class="fa fa-person-booth side-menu__icon"></i>
                    <span class="side-menu__label">
                       مؤهلات الاستاذ
                    </span>
                </a>
            </li>
        @endcan

        @can('الدليل')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('guide.index') }}">
                    <i class="icon icon-note side-menu__icon"></i>
                    <span class="side-menu__label">
                        المصادر والمراجعات
                    </span>
                </a>
            </li>
        @endcan


        @can('بنك الأسئلة')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('questions.index') }}">
                    <i class="icon icon-question side-menu__icon"></i>
                    <span class="side-menu__label">
                        بنك الأسئلة
                    </span>
                </a>
            </li>
        @endcan


            {{--exam questions--}}
            @can('اسئله الامتحانات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('online_exam_questions.index') }}">
                    <i class="icon icon-question side-menu__icon"></i>
                    <span class="side-menu__label">

                        اسئله الامتحانات
                    </span>
                </a>
            </li>
            @endcan

        @can('الكوبونات')
            <!-- DISCOUNT -->
            <li class="slide">
                <a class="side-menu__item" href="{{ route('discount_coupons.index') }}">
                    <i class="fa fa-book-reader side-menu__icon"></i>
                    <span class="side-menu__label">كوبونات الخصم</span>
                </a>
            </li>
        @endcan

        @can('الباقات')

            <li class="slide">
                <a class="side-menu__item" href="{{ route('subscribe.index') }}">
                    <i class="fa fa-box side-menu__icon"></i>
                    <span class="side-menu__label">الباقات</span>
                </a>
            </li>
        @endcan





        @can('التعليقات')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('comment.index') }}">
                    <i class="fa fa-comments side-menu__icon"></i>
                    <span class="side-menu__label">
                       التعليقات
                    </span>
                </a>
            </li>
        @endcan



        {{--        <li class="slide">--}}
        {{--            <a class="side-menu__item" href="{{ route('contactUs.index') }}">--}}
        {{--                <i class="fa fa-globe side-menu__icon"></i>--}}
        {{--                <span class="side-menu__label">تواصل معنا</span>--}}
        {{--            </a>--}}
        {{--        </li>--}}


    </ul>
</aside>
