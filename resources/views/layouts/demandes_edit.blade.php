<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="../../image/png" sizes="16x16" href="images/favicon.png">
    <title>Kronos - Admin</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="../../css/morris.css" rel="stylesheet">
    <!--<link href="css/jquery.toast.css" rel="stylesheet"> -->
    <link href="../../css/style.min.css" rel="stylesheet">
    <link href="../../css/dashboard1.css" rel="stylesheet">
    <link href="../../css/chartist.min.css" rel="stylesheet">
    <link href="../../css/chartist-init.css" rel="stylesheet">
    <link href="../../css/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="../../css/css-chart.css" rel="stylesheet">
    <link href="../../css/widget-page.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #edf1f5;
        }
        .left-sidebar {
            position: absolute;
            width: 220px;
            height: 100%;
            top: 56px;
            z-index: 2;
            background: rgb(50,50,50);
            background: linear-gradient(180deg, rgba(50,50,50,1) 10%, rgba(0,0,0,0.9) 100%);
            /* background: #323232; */
            box-shadow: 1px 0px 20px rgba(0, 0, 0, 0.08);
        }
        .user-profile {
            margin-top: 20px;
        }
        .dropup, .dropright, .dropdown, .dropleft {
            position: relative;
        }
        .user-profile .u-dropdown {
            display: block;
            text-align: center;
        }
        .sidebar-nav {
            padding: 15px 0 0 0px;
        }
        .sidebar-nav ul {
            margin: 0px;
            padding: 0px;
        }
        .scroll-sidebar {
            height: calc(100% - 67px);
            position: relative;
        }
        /* Style the sidenav links and the dropdown button */
        .sidenav a, .dropdown-btn {
            color: #8d97ad;
            padding: 10px 35px 10px 15px;
            display: block;
            align-items: center;
            font-size: 14px;
            font-weight: 400;
        }
        /* On mouse-over */
        .sidenav a:hover, .dropdown-btn:hover {
            color:black;
            background-color: lightgrey;

        }
        .sidenav  a {
            border-left: 3px solid transparent;
        }

        .fixed-layout .topbar {
            width: 100%;
        }
        .fixed-layout .left-sidebar, .fixed-layout .topbar {
            position: fixed;
        }
        .skin-default .topbar {
            background: #323232;
        }
        .topbar .top-navbar .navbar-header {
            background: #323232;
            line-height: 15px;
            padding-left: 10px;
            min-width: 70px;
        }
        .topbar .top-navbar {
            min-height: 50px;
            padding: 0px;
        }
        /* Main content */
        .main {
            margin-left: 200px; /* Same as the width of the sidenav */
            font-size: 20px; /* Increased text to enable scrolling */
            padding: 0px 10px;
        }
        /* Add an active class to the active dropdown button */
        .active {

            color: white;
        }
        mr-auto, .mx-auto {
            margin-right: auto !important;
        }
        .topbar .navbar-collapse {
            padding: 0px;
        }
        .navbar-collapse {
            flex-basis: 100%;
            flex-grow: 1;
            align-items: center;
        }
        /* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
        .dropdown-container {
            display: none;
            background-color: #262626;
        }
        /* Optional: Style the caret down icon */
        .fa-caret-down {
            float: right;
            padding-right: 8px;
        }
        /* Some media queries for responsiveness */
        .navbar {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem;
        }
        .navbar-nav {
            display: flex;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }
        .header-icon {
            float: left;
            width: 45px;
            margin-right: 16px;
            cursor: pointer;
        }
        a {
            color: #80b8c3;
            text-decoration: none;
            background-color: transparent;
        }

        .sidebar-nav ul li.nav-small-cap {
            font-size: 12px;
            margin-bottom: 0px;
            padding: 30px 14px 14px 0px;
            color: #6c757d;
            font-weight: 500;
        }
        .sidebar-nav > ul > li {
            margin-bottom: 8px;
            margin-top: 8px;
        }
        .sidebar-nav ul li {
            list-style: none;
        }
        .sidebar-nav > ul > li > a i {
            /* width: 25px; */
            font-size: 16px;
            display: inline-block;
            vertical-align: middle;
            color: #323232;
            background-color: #8cb7c1;
            padding: 10px;
            border-radius: 50px;
            margin-right: 10px;
        }
        .sidebar-nav ul li a {
            color: #8d97ad;
            padding: 10px 35px 10px 15px;
            display: block;
            align-items: center;
            font-size: 14px;
            font-weight: 400;
        }
        .topbar .top-navbar .navbar-header .navbar-brand b {
            line-height: 50px;
            display: inline-block;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.5);
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .btn-circle {
            border-radius: 100%;
            width: 40px;
            height: 40px;
            padding: 10px;
        }
        .btn-info {
            color: #fff;
            background-color: #80b8c3;
            border-color: #80b8c3;
        }
        .btn-danger {
            color: #fff;
            background-color: #e46a76;
            border-color: #e46a76;
        }
        @media (min-width: 992px){
            .mb-lg-0, .my-lg-0 {
                margin-bottom: 0 !important;
            } }
        @media (min-width: 768px){
            .navbar-header .navbar-brand {
                padding-top: 0px;
            }
        }

        @media (min-width: 768px){
            .navbar-expand-md .navbar-collapse {
                display: flex !important;
                flex-basis: auto;
            }
        }
        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
            .sidenav a {font-size: 18px;}
        }

        @media (min-width: 768px){
            .navbar-header {
                width: 220px;
                flex-shrink: 0;
            }
        }
        @media (min-width: 768px){
            .navbar-expand-md .navbar-nav {
                flex-direction: row;
            }
        }
        @media (min-width: 768px){
            .navbar-expand-md {
                flex-flow: row nowrap;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body >


<div id="main-wrapper">
<!-- Top container -->
<header class="topbar" style="background: #323232;">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="statistiques.php">
                <!-- Logo icon --><b>
                    <img src="../../images/logo-icon.png" alt="homepage" class="dark-logo" />
                    <img src="../../images/logo-light-icon.png" alt="homepage" class="light-logo" />
                    <!-- Light Logo icon -->

                 </b>
                 <!--End Logo icon -->
                <!-- Logo text --><span>

                <img src="../../images/logo-text.png" alt="homepage" class="dark-logo" style="transform: scale(0.6);margin-left: -50px;" />
                    <!-- Light Logo text -->
                 </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto">
                <!-- This is  -->
                <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                <!-- ============================================================== -->
                <!-- Search -->
                <!-- ============================================================== -->
                <li class="nav-item">
                    <form class="app-search d-none d-md-block d-lg-block">
                        <input type="text" class="form-control" placeholder="Rechercher" style="border-radius: 5px!important;background-color: #fff;">&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href=""><button type="button" class="btn btn-info btn-circle"><i class="fa fa-play"></i> </button></a>
                        <a href="">&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-stop"></i> </button></a>
                        <a href="" style="margin-top: 2px;margin-left:16px;position: absolute;"><i class="fa fa-clock"></i></a>
                        <a><span style="font-size:21px;color: #fff;margin-top: 2px;margin-left:36px;position: absolute;">00:00:00</span></a>
                    </form>
                </li>
            </ul>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="header-icon" href="{{ route('pretabc.index') }}"><img src="../../images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                    <a class="header-icon" href="{{ route('kreditpret.index') }}"><img src="../../images/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                    <!--<a class="header-icon header-icon-unselected"><img src="images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>-->
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-users"></i>  {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif





                        <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                            <ul style="display:none;">
                                <li>
                                    <div class="drop-title">Notifications</div>
                                </li>
                                <li>
                                    <div class="message-center">
                                        <!-- Message -->
                                        <a href="javascript:void(0)">
                                            <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                            <div class="mail-contnet">
                                                <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="javascript:void(0)">
                                            <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                            <div class="mail-contnet">
                                                <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="javascript:void(0)">
                                            <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                            <div class="mail-contnet">
                                                <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="javascript:void(0)">
                                            <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                            <div class="mail-contnet">
                                                <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <a class="nav-link text-center link" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                </li>
                            </ul>
                        </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown" style="display:none;">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-note"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img">  <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img">  <span class="profile-status busy pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span> </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- mega menu -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown mega-dropdown" style="display:none;"> <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti-layout-width-default"></i></a>
                            <div class="dropdown-menu animated bounceInDown">
                                <ul class="mega-dropdown-menu row">
                                    <li class="col-lg-3 col-xlg-2 m-b-30">
                                        <h4 class="m-b-20">CAROUSEL</h4>
                                        <!-- CAROUSEL -->
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner" role="listbox">
                                                <div class="carousel-item active">
                                                    <div class="container"> <img class="d-block img-fluid" src="../../images/big/img1.jpg" alt="First slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="../../images/big/img2.jpg" alt="Second slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="../../images/big/img3.jpg" alt="Third slide"></div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
                                        </div>
                                        <!-- End CAROUSEL -->
                                    </li>
                                    <li class="col-lg-3 m-b-30">
                                        <h4 class="m-b-20">ACCORDION</h4>
                                        <!-- Accordian -->
                                        <div class="accordion" id="accordionExample">
                                            <div class="card m-b-0">
                                                <div class="card-header bg-white p-0" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                            Collapsible Group Item #1
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        Anim pariatur cliche reprehenderit, enim eiusmod high.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card m-b-0">
                                                <div class="card-header bg-white p-0" id="headingTwo">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                                                aria-controls="collapseTwo">
                                                            Collapsible Group Item #2
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        Anim pariatur cliche reprehenderit, enim eiusmod high.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card m-b-0">
                                                <div class="card-header bg-white p-0" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                                                aria-controls="collapseThree">
                                                            Collapsible Group Item #3
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        Anim pariatur cliche reprehenderit, enim eiusmod high.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Name"> </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email"> </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-4 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> You can give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Forth link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another fifth link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End mega menu -->
                        <!-- ============================================================== -->
                        <li class="nav-item right-side-toggle" style="display:none;"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    </ul>
        </div>
    </nav>
</header>
<aside class="left-sidebar" >
    <div class="scroll-sidebar">

        <div class="user-profile">
            <div class="user-pro-body">
                <div><img src="../../images/admin2.jpg" alt="user-img" class="img-circle" style="width: 50px;display: block;margin: 0 auto 10px;border-radius:100%"></div>
                <div class="dropdown">
                    <a href=""class="u-dropdown link hide-menu" role="button" aria-expanded="false" style="color:#fff!important;"> {{ Auth::user()->name }}<br></a>


                    @if((Auth::user()->role_id == 2))
                        <a href="" style="color:#fff!important; text-align: center;display:block;">Employe</a>
                    @else
                        <a href="" style="color:#fff!important; text-align: center;display:block;">Administrateur</a>
                    @endif
                    <div class="center-block" style="margin-left:34%;margin-top:5px;">
                        <a class="header-icon" style="width:16%;"><img src="../../images/icon_pretabc.png" style="width:100%;" alt="" class=""></a>
                        <a class="header-icon" style="width:16%;"><img src="../../images/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap">--- GESTION</li>
                @if((Auth::user()->role_id == 2))
                @else
                    <li><a class="waves-effect waves-dark" href="{{ route('admin.index') }}" aria-expanded="false"><i class="fa fa-list"></i><span class="hide-menu">Statistiques</span></a> </li>
                @endif


                <li><a class="waves-effect waves-dark" href="{{ route('demande.index') }}" aria-expanded="false"><i class="fa fa-edit"></i><span class="hide-menu">Demandes</span></a></li>
                <li><a class="waves-effect waves-dark" href="{{ route('membres.index') }}" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Membres</span></a></li>

                @if((Auth::user()->role_id == 2))
                @else
                    <li><a class="waves-effect waves-dark" href="{{ route('company.index')}}" aria-expanded="false"><i class="fa fa-building"></i><span class="hide-menu">Compagnies</span></a></li>
                    <li><a class="waves-effect waves-dark" href="{{ route('employes.index')}}" aria-expanded="false"><i class="fa fa-users"></i><span class="hide-menu">Employés</span></a></li>
                @endif



            </ul>
        </nav>
    </div>
</aside>
    <div class="page-wrapper">
        <div class="container-fluid" style="padding:0px;margin-left:5px;margin-top:43px;">


            <div class="row">
                @yield('content')

            </div>


        </div>

    </div>

    <footer class="footer">
        © 2021 Gestion Kronos. Tous droits réservés.
    </footer>

</div>


<script src="../../node_modules/jquery-3.2.1.min.js"></script>
<script src="../../node_modules/popper.min.js"></script>
<script src="../../node_modules/bootstrap.min.js"></script>

<script src="../../node_modules/perfect-scrollbar.jquery.min.js"></script>
<script src="../../node_modules/waves.js"></script>
<script src="../../node_modules/custom.min.js"></script>
<script src="../../node_modules/sidebarmenu.js"></script>

<script src="../../node_modules/raphael-min.js"></script>
<script src="../../node_modules/morris.min.js"></script>
<script src="../../node_modules/jquery.sparkline.min.js"></script>

<script src="../../node_modules/dashboard1.js"></script>
<script src="../../node_modules/jquery.toast.js"></script>



<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="../../assets/node_modules/chartist-plugin-tooltip.min.js"></script>

<script src="../../assets/node_modules/jquery.sparkline.min.js"></script>
<script src="../../assets/node_modules/echarts-all.js"></script>
<script src="../../assets/node_modules/excanvas.js"></script>
<script src="../../assets/node_modules/jquery.flot.js"></script>
<script src="../../assets/node_modules//jquery.flot.time.js"></script>
<script src="../../assets/node_modules/jquery.flot.tooltip.min.js"></script>
<script src="../../assets/node_modules/widget-charts.js"></script>

<script>

    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");

    // Get the DIV with overlay effect
    var overlayBg = document.getElementById("myOverlay");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }
    // accordeon style
    function myAccFunc() {
        var x = document.getElementById("demoAcc");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
            x.previousElementSibling.className += " w3-green";
        } else {
            x.className = x.className.replace(" w3-show", "");
            x.previousElementSibling.className =
                x.previousElementSibling.className.replace(" w3-green", "");
        }
    }

    function myDropFunc() {
        var x = document.getElementById("demoDrop");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
            x.previousElementSibling.className += " w3-green";
        } else {
            x.className = x.className.replace(" w3-show", "");
            x.previousElementSibling.className =
                x.previousElementSibling.className.replace(" w3-green", "");
        }
    }
    function myFunction() {
        var x = document.getElementById("demo");
        if (x.className.indexOf("w3-show") == -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>

{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
