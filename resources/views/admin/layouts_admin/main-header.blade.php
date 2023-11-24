<!--APP-MAIN-HEADER-->
<div class="app-header header">
    <div class="container-fluid">
        <div class="d-flex">
            <a class="header-brand d-md-none" href="#">
                <img src="{{ asset('assets/admin/images/logo-1.jpg') }}" class="header-brand-img mobile-icon" alt="logo">
                <img src="{{ asset('assets/admin/images/logo-1.jpg') }}" class="header-brand-img desktop-logo mobile-logo" alt="logo">
            </a>
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#">
                <svg xmlns="http:/www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M21 11.01L3 11v2h18zM3 16h12v2H3zM21 6H3v2.01L21 8z" /></svg>
            </a><!-- sidebar-toggle-->
            <div class="header-search d-none d-md-flex">
                <form class="form-inline">
                    <div class="search-element">
{{--                        <input type="search" class="form-control header-search" placeholder="Search…" aria-label="Search" tabindex="1">--}}
{{--                        <button class="btn btn-primary-color" type="submit">--}}
{{--                            <svg xmlns="http:/www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">--}}
{{--                                <path d="M0 0h24v24H0V0z" fill="none" />--}}
{{--                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>--}}
{{--                        </button>--}}
                    </div>
                </form>
            </div>
            <div class="d-flex mr-auto header-right-icons header-search-icon">
{{--                <button class="navbar-toggler navresponsive-toggler d-md-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--                    <svg xmlns="http:/www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" class="navbar-toggler-icon">--}}
{{--                        <path d="M0 0h24v24H0V0z" fill="none" />--}}
{{--                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" /></svg>--}}
{{--                </button>--}}
                <div class="dropdown d-none d-lg-flex">
                    <a class="nav-link icon full-screen-link nav-link-bg">
                        <i class="fullscreen-button fe fe-maximize-2" id="fullscreen-button3"></i>
                    </a>
                </div>
                <!-- FULL-SCREEN -->
{{--                <div class="dropdown d-md-flex notifications">--}}
{{--                    <a class="nav-link icon" data-toggle="dropdown">--}}
{{--                        <i class="fe fe-bell"></i>--}}
{{--                        <span class="nav-unread badge badge-success badge-pill">2</span>--}}
{{--                    </a>--}}
{{--                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">--}}
{{--                        <a href="#" class="dropdown-item mt-2 d-flex pb-3">--}}
{{--                            <div class="notifyimg bg-success">--}}
{{--                                <i class="fa fa-thumbs-o-up"></i>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <strong>Someone likes our posts.</strong>--}}
{{--                                <div class="small text-muted">3 hours ago</div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                        <a href="#" class="dropdown-item d-flex pb-3">--}}
{{--                            <div class="notifyimg bg-warning">--}}
{{--                                <i class="fa fa-commenting-o"></i>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <strong> 3 New Comments</strong>--}}
{{--                                <div class="small text-muted">5  hour ago</div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                        <a href="#" class="dropdown-item d-flex pb-3">--}}
{{--                            <div class="notifyimg bg-danger">--}}
{{--                                <i class="fa fa-cogs"></i>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <strong> Server Rebooted.</strong>--}}
{{--                                <div class="small text-muted">45 mintues ago</div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                        <div class="dropdown-divider"></div>--}}
{{--                        <a href="#" class="dropdown-item text-center">View all Notification</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <!-- NOTIFICATIONS -->
                <div class="dropdown d-md-flex message mr-2">
                    <a class="nav-link icon text-center" data-toggle="dropdown">
                        <i class="fe fe-bell"></i>
                        <span class="nav-unread badge  badge-success badgetext">{{ $logsCount }}</span>
                    </a>
                    <div style="width: 25rem !important" class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
                        @foreach($logs as $log)
                        <a href="{{ route('adminLog') }}" class="dropdown-item d-flex mt-2 pb-3">
                            <div>
                                <strong class="text-muted">{{ $log->admin->name }}</strong>
                                <p class="mb-0 fs-13 text-muted">{{ $log->action }}</p>
                                <div class="small text-muted">{{ $log->created_at->format('Y-m-d') }}</div>
                            </div>
                        </a>
                        @endforeach
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('adminLog') }}" class="dropdown-item text-center">See all Messages</a>
                    </div>
                </div>
                <!-- MESSAGE-BOX -->
                <div class="dropdown profile-1">
{{--                    @php--}}
{{--                        $name  = loggedAdmin('name');--}}
{{--                        $photo = get_user_photo(loggedAdmin('photo'));--}}
{{--                    @endphp--}}
                    <a href="#" data-toggle="dropdown" class="nav-link pl-2 pr-2  leading-none d-flex">
									<span>
										<img src="{{ asset(auth('admin')->user()->image) ?? asset('assets/admin/images/logo-1.jpg')  }}" alt="{{ auth('admin')->user()->name }}" class="avatar  mr-xl-3 profile-user brround cover-image">
                                    </span>
{{--                        <div class="text-center mt-1 d-none d-xl-block">--}}
{{--                            <h6 class="text-dark mb-0 fs-13 font-weight-semibold text-capitalize">--}}
{{--                                {{ auth('admin')->user()->name }}</h6>--}}
{{--                        </div>--}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow"> {{--style--}}
                        <h3 class="dropdown-item m-0 text-primary d-flex justify-content-center">
                            {{ auth('admin')->user()->name }}
                        </h3>
                        <hr style="margin-top: 0rem;margin-bottom: 0rem;">
                        <a class="dropdown-item" href="{{ route('myProfile') }}">
                            <i class="dropdown-icon mdi mdi-account-outline"></i> ملفك الشخصي
                        </a>
                        <a class="dropdown-item" href="https://topbusiness.io/index.php/contact/">
                            <i class="dropdown-icon mdi mdi-compass-outline"></i>تحتاج مساعدة ؟
                        </a>
                        <a class="dropdown-item logoutAdmin" href="#">
                            <i class="dropdown-icon mdi  mdi-logout-variant"></i> تسجيل الخروج
                        </a>
                    </div>
                </div>
                <!-- SIDE-MENU -->
            </div>
        </div>
    </div>
</div>
<!--/APP-MAIN-HEADER-->



