
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Kronos - Admin</title>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="css/morris.css" rel="stylesheet">
    <link href="css/jquery.toast.css" rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">
    <link href="css/dashboard1.css" rel="stylesheet">
    <link href="css/chartist.min.css" rel="stylesheet">
    <link href="css/chartist-init.css" rel="stylesheet">
    <link href="css/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="css/css-chart.css" rel="stylesheet">
    <link href="css/widget-page.css" rel="stylesheet">

    <style>
        .bg-info {
            background: #007a4b!important;
        }
        .bg-dark {
            background-color: #23272b!important;
        }
        .bg-danger {
            background-color: #e46a76 !important;
        }
        .ct-label {
            width:17px!important;
        }
    </style>

</head>

<body class="skin-default fixed-layout">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">Kronos - Admin</p>
    </div>
</div>
<div id="main-wrapper">
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <div class="navbar-header">
                <a class="navbar-brand" href="statistiques.php">
                    <!-- Logo icon --><b>
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <img src="images/logo-icon.png" alt="homepage" class="dark-logo" />
                        <!-- Light Logo icon -->
                        <img src="images/logo-light-icon.png" alt="homepage" class="light-logo" />
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text --><span>
                   <!-- dark Logo text -->
                   <img src="images/logo-text.png" alt="homepage" class="dark-logo" style="transform: scale(0.6);margin-left: -50px;" />
                        <!-- Light Logo text -->
                   <img src="images/logo-light-text.png" class="light-logo" alt="homepage" /></span> </a>
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
                        <a class="header-icon" href="{{ route('pretabc.index') }}"><img src="images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                        <a class="header-icon" ><img src="images/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected"><img src="images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected" ><img src="images/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected"><img src="images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected" ><img src="images/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected"><img src="images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>





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
                                            <div class="user-img"> <img src="images/users/1.jpg" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="javascript:void(0)">
                                            <div class="user-img"> <img src="images/users/2.jpg" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="javascript:void(0)">
                                            <div class="user-img"> <img src="images/users/3.jpg" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="javascript:void(0)">
                                            <div class="user-img"> <img src="images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
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
                                                <div class="container"> <img class="d-block img-fluid" src="images/big/img1.jpg" alt="First slide"></div>
                                            </div>
                                            <div class="carousel-item">
                                                <div class="container"><img class="d-block img-fluid" src="images/big/img2.jpg" alt="Second slide"></div>
                                            </div>
                                            <div class="carousel-item">
                                                <div class="container"><img class="d-block img-fluid" src="images/big/img3.jpg" alt="Third slide"></div>
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
</div>


    <aside class="left-sidebar">
        <div class="scroll-sidebar">

            <div class="user-profile">
                <div class="user-pro-body">
                    <div><img src="images/admin2.jpg" alt="user-img" class="img-circle"></div>
                    <div class="dropdown">
                        <a href=""class="u-dropdown link hide-menu" role="button" aria-expanded="false" style="color:#fff!important;">Maxime William Martin<br>Administrateur</a>
                        <div class="center-block" style="margin-left:34%;margin-top:5px;">
                            <a class="header-icon" style="width:16%;"><img src="images/icon_pretabc.png" style="width:100%;" alt="" class=""></a>
                            <a class="header-icon" style="width:16%;"><img src="images/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>


            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-small-cap">--- GESTION</li>
                    <li><a class="waves-effect waves-dark" href="{{ route('admin.index') }}" aria-expanded="false"><i class="fa fa-chart-pie"></i><span class="hide-menu">Statistiques</span></a></li>
                    <li><a class="waves-effect waves-dark" href="{{ route('demande.index') }}" aria-expanded="false"><i class="fa fa-edit"></i><span class="hide-menu">Demandes</span></a></li>
                    <li><a class="waves-effect waves-dark" href="{{ route('membre.index') }}" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Membres</span></a></li>
                    <li><a class="waves-effect waves-dark" href="{{ route('company.index')}}" aria-expanded="false"><i class="fa fa-building"></i><span class="hide-menu">Compagnies</span></a></li>
                    <li><a class="waves-effect waves-dark" href="employes.php" aria-expanded="false"><i class="fa fa-users"></i><span class="hide-menu">Employés</span></a></li>
                    <li><a class="waves-effect waves-dark" href="temps.php" aria-expanded="false"><i class="fa fa-clock"></i><span class="hide-menu">Temps</span></a></li>
                    <li><a class="waves-effect waves-dark" href="verifications.php" aria-expanded="false"><i class="fa fa-check"></i><span class="hide-menu">Vérifications</span></a></li>
                    <li><a class="waves-effect waves-dark" href="performances.php" aria-expanded="false"><i class="fa fa-trophy"></i><span class="hide-menu">Performances</span></a></li>
                    <li><a class="waves-effect waves-dark" href="activites.php" aria-expanded="false"><i class="fa fa-chart-line"></i><span class="hide-menu">Activités</span></a></li>
                    <li><a class="waves-effect waves-dark" href="alertes.php" aria-expanded="false"><i class="fa fa-exclamation-circle"></i><span class="hide-menu">Alertes</span></a></li>
                    <li class="nav-small-cap">--- CATÉGORIES</li>
                    <li><a class="waves-effect waves-dark" href="categories_edit.php" aria-expanded="false"><i class="fa fa-list"></i><span class="hide-menu">Statuts</span></a></li>




                </ul>
            </nav>
        </div>
            </div>
    </aside>
<div class="page-wrapper">
    <div class="container-fluid">


        @yield('content')


    </div>


</div>


    <footer class="footer">
        © 2020 Gestion Kronos. Tous droits réservés.
    </footer>


<script src="node_modules/jquery-3.2.1.min.js"></script>
<script src="node_modules/popper.min.js"></script>
<script src="node_modules/bootstrap.min.js"></script>

<script src="node_modules/perfect-scrollbar.jquery.min.js"></script>
<script src="node_modules/waves.js"></script>
<script src="node_modules/custom.min.js"></script>
<script src="node_modules/sidebarmenu.js"></script>

<script src="node_modules/raphael-min.js"></script>
<script src="node_modules/morris.min.js"></script>
<script src="node_modules/jquery.sparkline.min.js"></script>
<script src="node_modules/jquery.toast.js"></script>
<script src="node_modules/dashboard1.js"></script>
<script src="node_modules/jquery.toast.js"></script>

<script src="node_modules/chartist.min.js"></script>
<script src="node_modules/chartist-plugin-tooltip.min.js"></script>

<script src="node_modules/jquery.sparkline.min.js"></script>
<script src="node_modules/echarts-all.js"></script>
<script src="node_modules/excanvas.js"></script>
<script src="node_modules/jquery.flot.js"></script>
<script src="node_modules//jquery.flot.time.js"></script>
<script src="node_modules/jquery.flot.tooltip.min.js"></script>
<script src="node_modules/widget-charts.js"></script>

</body>
</html>