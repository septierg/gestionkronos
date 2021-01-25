
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
                        <a class="header-icon"><img src="images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
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
    </header>        <aside class="left-sidebar">
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
            </div>

            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-small-cap">--- GESTION</li>
                    <li><a class="waves-effect waves-dark" href="{{ route('admin.index') }}" aria-expanded="false"><i class="fa fa-chart-pie"></i><span class="hide-menu">Statistiques</span></a></li>
                    <li><a class="waves-effect waves-dark" href="{{ route('pretabc.index') }}" aria-expanded="false"><i class="fa fa-edit"></i><span class="hide-menu">Demandes</span></a></li>
                    <li><a class="waves-effect waves-dark" href="{{ route('membre.index') }}" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Membres</span></a></li>
                    <li><a class="waves-effect waves-dark" href="compagnies.php" aria-expanded="false"><i class="fa fa-building"></i><span class="hide-menu">Compagnies</span></a></li>
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
    </aside>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-white">Statistiques</h4>

                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                            <li class="breadcrumb-item active">Statistiques</li>
                        </ol>
                        <button onclick="location.href='demandes.php';" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Voir toutes les demandes</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card" style="background-color: rgba(0,0,0,0.08);">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Filtrer les statistiques par mois</label>
                                            <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                <option value="">Tous les mois</option>
                                                <option value="">Janvier</option>
                                                <option value="">Février</option>
                                                <option value="">Mars</option>
                                                <option value="">Avril</option>
                                                <option value="">Mai</option>
                                                <option value="">Juin</option>
                                                <option value="">Juillet</option>
                                                <option value="" selected>Août</option>
                                                <option value="">Septembre</option>
                                                <option value="">Octobre</option>
                                                <option value="">Novembre</option>
                                                <option value="">Décembre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Filtrer les statistiques par année</label>
                                            <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1">
                                                <option value="Category 4">Année en cours</option>
                                                <option value="Category 4">2019</option>
                                                <option value="Category 4">2018</option>
                                                <option value="Category 4">2017</option>
                                                <option value="Category 4">2016</option>
                                                <option value="Category 4">2015</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-dark d-none d-lg-block m-l-15" style="width:100%;margin-left:4px;"><i class="fa fa-plus-circle"></i> Filtrer les résultats</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card-group">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-chart-pie header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">MONTANT DES NOUVEAUX PRETS</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-primary">299 200$</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">Montant des prêts - Total : <b>12 288 292$</b></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-check header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">NOUVELLES DEMANDES DE PRETS ACCEPTÉES</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-cyan">521</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">Demandes de prêts acceptées - Total : <b>18 291</b></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-edit header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">NOUVELLES DEMANDES DE RENOUVELLEMENTS</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-cyan" style="color:#03a9f3!important;">128</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="background-color: #03a9f3 !important;width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">Demandes de renouvellements de prêts - Total : <b>2 649</b></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-times-circle header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">NOUVEAUX RECOUVREMENTS DE PRETS</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-danger">25</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">Demandes de recouvrements de prêts - Total : <b>364</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h3>Montant des prêts mensuels</h3>
                                    <h6 class="card-subtitle">Août 2020</h6>
                                </div>
                                <div class="ml-auto">
                                    <ul class="list-inline">
                                        <li>
                                            <h6 class="text-muted"><i class="fa fa-circle m-r-5 text-success"></i>Prêt ABC</h6> </li>
                                        <li>
                                            <h6 class="text-muted"><i class="fa fa-circle m-r-5 text-info"></i>Krédit Prêt</h6> </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="total-revenue4" style="height: 350px;"></div>
                                </div>

                                <div class="col-lg-2 col-md-6 m-b-30 m-t-20 text-center">
                                    <h1 class="m-b-0 font-light">259 120$</h1>
                                    <h6 class="text-muted">Nouveaux Prêts (Août 2020)</h6>
                                </div>
                                <div class="col-lg-2 col-md-6 m-b-30 m-t-20 text-center">
                                    <h1 class="m-b-0 font-light">40 180$</h1>
                                    <h6 class="text-muted">Renouvellements (Août 2020)</h6>
                                </div>
                                <div class="col-lg-2 col-md-6 m-b-30 m-t-20 text-center">
                                    <h1 class="m-b-0 font-light">299 200$</h1>
                                    <h6 class="text-muted">Total (Août 2020)</h6>
                                </div>
                                <div class="col-lg-2 col-md-6 m-b-30 m-t-20 text-center">
                                    <h1 class="m-b-0 font-light">10 188 082$</h1>
                                    <h6 class="text-muted">Nouveaux Prêts (Total)</h6>
                                </div>
                                <div class="col-lg-2 col-md-6 m-b-30 m-t-20 text-center">
                                    <h1 class="m-b-0 font-light">2 100 210$</h1>
                                    <h6 class="text-muted">Renouvellements (Total)</h6>
                                </div>
                                <div class="col-lg-2 col-md-6 m-b-30 m-t-20 text-center">
                                    <h1 class="m-b-0 font-light">12 288 292$</h1>
                                    <h6 class="text-muted">Total (2020)</h6>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 col-md-6">
                    <div class="card card-body">
                        <h4 class="card-title">Nouvelles alertes</h4>
                        <div class="message-box">
                            <div class="message-widget">
                                <a href="javascript:void(0)">
                                    <div class="user-img">
                                    <span class="round bg-primary">
                                        <i class="icon-user"></i>
                                    </span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Nouvelle demande de prêt</h5>
                                        <span class="mail-desc">Nancy Duchesneau - 514-123-4567</span>
                                        <span class="time">9:30 - 16 août 2020</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)">
                                    <div class="user-img">
                                    <span class="round bg-dark">
                                        <i class="icon-credit-card"></i>
                                    </span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Nouvelle demande de renouvellement de prêt</h5>
                                        <span class="mail-desc">Maxime Ouellette - 514-123-4567</span>
                                        <span class="time">9:30 - 16 août 2020</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)">
                                    <div class="user-img">
                                    <span class="round bg-success">
                                        <i class="icon-wallet"></i>
                                    </span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Prêt approuvé par - Maxime William Martin</h5>
                                        <span class="mail-desc">Pour membre - Jean Desjardins</span>
                                        <span class="time">9:30 - 16 août 2020</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)">
                                    <div class="user-img">
                                    <span class="round bg-danger">
                                        <i class="icon-close"></i>
                                    </span>
                                    </div>
                                    <div class="mail-contnet">
                                        <h5>Prêt refusé par - Nicolas Nadeau</h5>
                                        <span class="mail-desc">Pour membre - Karine Blackburn</span>
                                        <span class="time">9:30 - 16 août 2020</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Nombre de demandes par compagnie</h3>
                                <div id="m-piechart" style="width:100%; height:278px"></div>
                                <center>
                                    <ul class="list-inline m-t-20">
                                        <li>
                                            <h6 class="text-muted">
                                                <i class="fa fa-circle m-r-5 text-success" style="color:#007a4b!important;"></i>Prêt ABC
                                            </h6>
                                        </li>
                                        <li>
                                            <h6 class="text-muted">
                                                <i class="fa fa-circle m-r-5 text-primary" style="color:#999c9f!important;"></i>Krédit Prêt
                                            </h6>
                                        </li>
                                        <li>
                                            <h6 class="text-muted">
                                                <i class="fa fa-circle m-r-5 text-danger" style="color:#343a40!important;"></i>Prêt 911
                                            </h6>
                                        </li>
                                        <li>
                                            <h6 class="text-muted">
                                                <i class="fa fa-circle m-r-5 text-muted" style="color:#e46a76!important;"></i>Prêt MWM
                                            </h6>
                                        </li>
                                        <li>
                                            <h6 class="text-muted">
                                                <i class="fa fa-circle m-r-5 text-muted" style="color:#343a40!important;"></i>Prêt 123
                                            </h6>
                                        </li>
                                        <li>
                                            <h6 class="text-muted">
                                                <i class="fa fa-circle m-r-5 text-muted" style="color:#fec107!important;"></i>Prêt CBA
                                            </h6>
                                        </li>
                                        <br><br>
                                    </ul>
                                </center>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div>
                                    <h5 class="card-title">Appercu des traitements des demandes de prêts</h5>
                                    <h6 class="card-subtitle">Suivi des performances des employés</h6>
                                </div>
                                <div class="ml-auto">
                                    <a href="performances.php"><button type="button" class="btn btn-info d-none d-lg-block m-l-15" style="float:right;"><i class="fa fa-plus-circle"></i> Consulter la liste complète des suivis de performance</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-6">
                                    <h3>Août 2020</h3>
                                    <h5 class="font-light m-t-0">Rapport pour le mois en cours</h5></div>
                                <div class="col-6 align-self-center display-6 text-right">
                                    <h2 class="text-success">4562 demandes en cours</h2></div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover no-wrap">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Nom de l'employé</th>
                                    <th>Dem. non-traitées</th>
                                    <th>Dem. commencées</th>
                                    <th>Dem. traitées</th>
                                    <th>Dem. refusées</th>
                                    <th>Dem. acceptées</th>
                                    <th>Dem. totales</th>
                                    <th>Ratio horaire</th>
                                    <th>Ratio demandes</th>
                                    <th>Heures totales</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="txt-oflo">Alicia Da Silva</td>
                                    <td class="txt-oflo">50</td>
                                    <td class="txt-oflo">100</td>
                                    <td class="txt-oflo">50</td>
                                    <td class="txt-oflo">30</td>
                                    <td class="txt-oflo">40</td>
                                    <td class="txt-oflo">270</td>
                                    <td><span class="badge badge-success badge-pill">4</span></td>
                                    <td><span class="badge badge-success badge-pill">85%</span></td>
                                    <td class="txt-oflo">182h</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td class="txt-oflo">François Gaudreault</td>
                                    <td class="txt-oflo">40</td>
                                    <td class="txt-oflo">90</td>
                                    <td class="txt-oflo">60</td>
                                    <td class="txt-oflo">20</td>
                                    <td class="txt-oflo">50</td>
                                    <td class="txt-oflo">240</td>
                                    <td><span class="badge badge-success badge-pill">8</span></td>
                                    <td><span class="badge badge-success badge-pill">80%</span></td>
                                    <td class="txt-oflo">180h</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td class="txt-oflo">Maxime William Martin</td>
                                    <td class="txt-oflo">50</td>
                                    <td class="txt-oflo">80</td>
                                    <td class="txt-oflo">50</td>
                                    <td class="txt-oflo">20</td>
                                    <td class="txt-oflo">50</td>
                                    <td class="txt-oflo">230</td>
                                    <td><span class="badge badge-success badge-pill">8</span></td>
                                    <td><span class="badge badge-success badge-pill">78%</span></td>
                                    <td class="txt-oflo">170h</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td class="txt-oflo">Félixe Léger</td>
                                    <td class="txt-oflo">40</td>
                                    <td class="txt-oflo">70</td>
                                    <td class="txt-oflo">40</td>
                                    <td class="txt-oflo">20</td>
                                    <td class="txt-oflo">40</td>
                                    <td class="txt-oflo">200</td>
                                    <td><span class="badge badge-warning badge-pill">4</span></td>
                                    <td><span class="badge badge-warning badge-pill">60%</span></td>
                                    <td class="txt-oflo">142h</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td class="txt-oflo">Khalida Lamothe</td>
                                    <td class="txt-oflo">30</td>
                                    <td class="txt-oflo">60</td>
                                    <td class="txt-oflo">30</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">30</td>
                                    <td class="txt-oflo">150</td>
                                    <td><span class="badge badge-warning badge-pill">4</span></td>
                                    <td><span class="badge badge-warning badge-pill">60%</span></td>
                                    <td class="txt-oflo">138h</td>
                                </tr>
                                <tr>
                                    <td class="text-center">6</td>
                                    <td class="txt-oflo">Marjolaine Roy</td>
                                    <td class="txt-oflo">20</td>
                                    <td class="txt-oflo">50</td>
                                    <td class="txt-oflo">30</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">20</td>
                                    <td class="txt-oflo">120</td>
                                    <td><span class="badge badge-warning badge-pill">4</span></td>
                                    <td><span class="badge badge-warning badge-pill">60%</span></td>
                                    <td class="txt-oflo">108h</td>
                                </tr>
                                <tr>
                                    <td class="text-center">7</td>
                                    <td class="txt-oflo">Mathieu Dugré</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">30</td>
                                    <td class="txt-oflo">20</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">90</td>
                                    <td><span class="badge badge-danger badge-pill">1</span></td>
                                    <td><span class="badge badge-danger badge-pill">32%</span></td>
                                    <td class="txt-oflo">88h</td>
                                </tr>
                                <tr>
                                    <td class="text-center">8</td>
                                    <td class="txt-oflo">Eric Kent</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">10</td>
                                    <td class="txt-oflo">20</td>
                                    <td class="txt-oflo">0</td>
                                    <td class="txt-oflo">50</td>
                                    <td><span class="badge badge-danger badge-pill">1</span></td>
                                    <td><span class="badge badge-danger badge-pill">10%</span></td>
                                    <td class="txt-oflo">84h</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5 text-left">
                                    <h4 class="card-title">Liste des dernières demandes de prêt de la dernière heure</h4>
                                </div>
                                <div class="col-md-7 text-right">
                                    <a href="demandes.php"><button type="button" class="btn btn-info d-none d-lg-block m-l-15" style="float:right;"><i class="fa fa-plus-circle"></i> Consulter la liste complète des demandes de prêts</button></a>
                                </div>
                            </div>

                            <div class="table-responsive m-t-40">
                                <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center!important;">Photo</th>
                                        <th>Demande</th>
                                        <th>Montant</th>
                                        <th>Fréquence</th>
                                        <th>Responsable</th>
                                        <th>État</th>
                                        <th>Statut</th>
                                        <th>CB</th>
                                        <th>Documents</th>
                                        <th>Contrats</th>
                                        <th>Note</th>
                                        <th>Ratio</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th style="text-align: center!important;">Photo</th>
                                        <th>Demande</th>
                                        <th>Montant</th>
                                        <th>Fréquence</th>
                                        <th>Responsable</th>
                                        <th>État</th>
                                        <th>Statut</th>
                                        <th>CB</th>
                                        <th>Documents</th>
                                        <th>Contrats</th>
                                        <th>Note</th>
                                        <th>Ratio</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    <tr>
                                        <td style="text-align: center!important;"><button type="button" class="button-icon-notification  btn btn-danger btn-circle"><i class="fa fa-exclamation-circle"></i> </button>
                                            <img src="images/pretabcsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
                                        <td>Valerie Lavoie<br><span class="info-date">2020-08-16 10:00:00</span></td>
                                        <td style="text-align: center!important;">2500$<br><button type="button" class="btn waves-effect waves-light btn-sm btn-dark" style="width:100%;margin-top:2px;font-size:10px;opacity:0.4;">Nouveau</button></td>
                                        <td>Aux 2 semaines</td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Aucun</option>
                                                <option value="">Maxime</option>
                                                <option selected value="">Alexandre</option>
                                                <option value="">Alicia</option>
                                                <option value="">Marjolaine</option>
                                                <option value="">Khalida</option>
                                                <option value="">Felixe</option>
                                                <option value="">Nancy</option>
                                                <option value="">Francois</option>
                                                <option value="">Roxanne</option>
                                                <option value="">Nicolas</option>
                                                <option value="">Mathieu</option>
                                                <option value="">Emilie</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Non-traité</option>
                                                <option value="">Accepté</option>
                                                <option value="">Refusé</option>
                                                <option value="">Commencé</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Manque d'informations</option>
                                                <option value="">IBV manquante</option>
                                                <option value="">Renouvellement</option>
                                                <option value="">Appeler l'employeur</option>
                                                <option value="">À regarder</option>
                                                <option value="">Offre</option>
                                                <option value="">Contrat à préparer</option>
                                            </select>
                                        </td>
                                        <td><input type="checkbox" id="" name="" value=""></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-1"><i class="fa fa-id-card"></i> <span class="text-under-icon text-under-icon-notification">PC</span></button>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-2"><i class="fa fa-id-card"></i> <span class="text-under-icon">TC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3"><i class="fa fa-id-card"></i> <span class="text-under-icon">SC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-4"><i class="fa fa-id-card"></i> <span class="text-under-icon">RB</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-5"><i class="fa fa-id-card"></i> <span class="text-under-icon">PR</span></button>
                                        </td>
                                        <td>
                                            <a href=""><button type="button" class="btn btn-success btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">PA</span></button></a>
                                            <a href=""><button type="button" class="btn btn-success btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">FC</span></button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">ED</span></button></a>
                                        </td>
                                        <td><a href=""><button type="button" class="btn btn-info btn-circle"><i class="fa fa-sticky-note"></i></button></a></td>
                                        <td>42.00%</td>
                                        <td>
                                            <a href="membres_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-download"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href="demandes_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center!important;"><img src="images/pretabcsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
                                        <td>Martin Boudreau<br><span class="info-date">2020-08-16 10:00:00</span></td></td>
                                        <td style="text-align: center!important;">1000$<br><button type="button" class="btn waves-effect waves-light btn-sm btn-dark" style="width:100%;margin-top:2px;font-size:10px;opacity:0.4;">Nouveau</button></td>
                                        <td>Aux 2 semaines</td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Aucun</option>
                                                <option value="">Maxime</option>
                                                <option value="">Alexandre</option>
                                                <option value="">Alicia</option>
                                                <option value="">Marjolaine</option>
                                                <option value="">Khalida</option>
                                                <option value="">Felixe</option>
                                                <option value="">Nancy</option>
                                                <option value="">Francois</option>
                                                <option selected value="">Roxanne</option>
                                                <option value="">Nicolas</option>
                                                <option value="">Mathieu</option>
                                                <option value="">Emilie</option>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Non-traité</option>
                                                <option value="">Accepté</option>
                                                <option value="">Refusé</option>
                                                <option value="">Commencé</option>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Manque d'informations</option>
                                                <option value="">IBV manquante</option>
                                                <option value="">Renouvellement</option>
                                                <option value="">Appeler l'employeur</option>
                                                <option value="">À regarder</option>
                                                <option value="">Offre</option>
                                                <option value="">Contrat à préparer</option>
                                        </td>
                                        <td><input type="checkbox" id="" name="" value=""></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-1"><i class="fa fa-id-card"></i> <span class="text-under-icon">PC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-2"><i class="fa fa-id-card"></i> <span class="text-under-icon">TC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3"><i class="fa fa-id-card"></i> <span class="text-under-icon">SC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-4"><i class="fa fa-id-card"></i> <span class="text-under-icon">RB</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-5"><i class="fa fa-id-card"></i> <span class="text-under-icon">PR</span></button>
                                        </td>
                                        <td>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">PA</span></button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">FC</span></button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">ED</span></button></a>
                                        </td>
                                        <td><a href=""><button type="button" class="btn btn-info btn-circle"><i class="fa fa-sticky-note"></i></button></a></td>
                                        <td>38.00%</td>
                                        <td>
                                            <a href="membres_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-download"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href="demandes_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center!important;"><img src="images/pretabcsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
                                        <td>Francesca Jacques<br><span class="info-date">2020-08-16 10:00:00</span></td></td>
                                        <td style="text-align: center!important;">1500$<br><button type="button" class="btn waves-effect waves-light btn-sm btn-success" style="width:100%;margin-top:2px;font-size:10px;opacity:0.6;">Renouvellement</button></td>
                                        <td>Aux 2 semaines</td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Aucun</option>
                                                <option value="">Maxime</option>
                                                <option value="">Alexandre</option>
                                                <option value="">Alicia</option>
                                                <option value="">Marjolaine</option>
                                                <option value="">Khalida</option>
                                                <option value="">Felixe</option>
                                                <option value="">Nancy</option>
                                                <option value="">Francois</option>
                                                <option value="">Roxanne</option>
                                                <option value="">Nicolas</option>
                                                <option selected value="">Mathieu</option>
                                                <option value="">Emilie</option>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Non-traité</option>
                                                <option value="">Accepté</option>
                                                <option value="">Refusé</option>
                                                <option value="">Commencé</option>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Manque d'informations</option>
                                                <option value="">IBV manquante</option>
                                                <option value="">Renouvellement</option>
                                                <option value="">Appeler l'employeur</option>
                                                <option value="">À regarder</option>
                                                <option value="">Offre</option>
                                                <option value="">Contrat à préparer</option>
                                        </td>
                                        <td><input type="checkbox" id="" name="" value=""></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-1"><i class="fa fa-id-card"></i> <span class="text-under-icon">PC</span></button>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-2"><i class="fa fa-id-card"></i> <span class="text-under-icon">TC</span></button>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-3"><i class="fa fa-id-card"></i> <span class="text-under-icon">SC</span></button>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-4"><i class="fa fa-id-card"></i> <span class="text-under-icon">RB</span></button>
                                            <button type="button" class="btn btn-success btn-circle" id="sa-documents-5"><i class="fa fa-id-card"></i> <span class="text-under-icon">PR</span></button>
                                        </td>
                                        <td>
                                            <a href=""><button type="button" class="btn btn-success btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">PA</span></button></a>
                                            <a href=""><button type="button" class="btn btn-success btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">FC</span></button></a>
                                            <a href=""><button type="button" class="btn btn-success btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">ED</span></button></a>
                                        </td>
                                        <td><a href=""><button type="button" class="btn btn-info btn-circle"><i class="fa fa-sticky-note"></i></button></a></td>
                                        <td>39.00%</td>
                                        <td>
                                            <a href="membres_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-download"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href="demandes_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center!important;"><img src="images/kreditpretsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
                                        <td>Melissa Rogers<br><span class="info-date">2020-08-16 10:00:00</span></td></td>
                                        <td style="text-align: center!important;">1750$<br><button type="button" class="btn waves-effect waves-light btn-sm btn-dark" style="width:100%;margin-top:2px;font-size:10px;opacity:0.4;">Nouveau</button></td>
                                        <td>Aux 2 semaines</td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Aucun</option>
                                                <option value="">Maxime</option>
                                                <option value="">Alexandre</option>
                                                <option value="">Alicia</option>
                                                <option value="">Marjolaine</option>
                                                <option value="">Khalida</option>
                                                <option value="">Felixe</option>
                                                <option value="">Nancy</option>
                                                <option value="">Francois</option>
                                                <option value="">Roxanne</option>
                                                <option selected value="">Nicolas</option>
                                                <option value="">Mathieu</option>
                                                <option value="">Emilie</option>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Non-traité</option>
                                                <option value="">Accepté</option>
                                                <option value="">Refusé</option>
                                                <option value="">Commencé</option>
                                        </td>
                                        <td>
                                            <select class="form-control custom-select" data-placeholder="" tabindex="1">
                                                <option value="">Manque d'informations</option>
                                                <option value="">IBV manquante</option>
                                                <option value="">Renouvellement</option>
                                                <option value="">Appeler l'employeur</option>
                                                <option value="">À regarder</option>
                                                <option value="">Offre</option>
                                                <option value="">Contrat à préparer</option>
                                        </td>
                                        <td><input type="checkbox" id="" name="" value=""></td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-1"><i class="fa fa-id-card"></i> <span class="text-under-icon">PC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-2"><i class="fa fa-id-card"></i> <span class="text-under-icon">TC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3"><i class="fa fa-id-card"></i> <span class="text-under-icon">SC</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-4"><i class="fa fa-id-card"></i> <span class="text-under-icon">RB</span></button>
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-5"><i class="fa fa-id-card"></i> <span class="text-under-icon">PR</span></button>
                                        </td>
                                        <td>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">PA</span></button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">FC</span></button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-file-alt"></i> <span class="text-under-icon">ED</span></button></a>
                                        </td>
                                        <td><a href=""><button type="button" class="btn btn-info btn-circle"><i class="fa fa-sticky-note"></i></button></a></td>
                                        <td>22.00%</td>
                                        <td>
                                            <a href="membres_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-download"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                            <a href="demandes_edit.php"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                            <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card-group">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-credit-card header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">PRET MOYEN PAR MEMBRE</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-primary">1500.00$</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">Prêt moyen par membre - Total : <b>1250.00$</b></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-chart-bar header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">% DE PRETS APPROUVÉS</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-cyan">32%</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-cyan" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">% de prêts approuvés - Total : <b>41%</b></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-chart-pie header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">% DE RENOUVELLEMENT</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-cyan" style="color:#03a9f3!important;">52%</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" style="background-color: #03a9f3 !important;width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">% de renouvellement - Total : <b>48%</b></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h3><i class="fa fa-times-circle header-icon-card-opacity"></i></h3>
                                        <p class="text-muted">% DE PRETS REFUSÉS</p>
                                    </div>
                                    <div class="ml-auto">
                                        <h2 class="counter text-danger">452</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="text-muted" style="margin-top:10px;">% de prêts refusés - Total : <b>24 869</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- .right-sidebar -->
            <div class="right-sidebar">
                <div class="slimscrollright">
                    <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                    <div class="r-panel-body">
                        <ul id="themecolors" class="m-t-20">
                            <li><b>With Light sidebar</b></li>
                            <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme working">1</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                            <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                            <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                            <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                        </ul>
                        <ul class="m-t-20 chatonline">
                            <li><b>Chat option</b></li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div></div>
    </div>

    <footer class="footer">
        © 2020 Gestion Kronos. Tous droits réservés.
    </footer>
</div>

<script src="node_modules/jquery-3.2.1.min.js"></script>
<script src="node_modules/popper.min.js"></script>
<script src="node_modules/bootstrap.min.js"></script>

<script src="node_modules/perfect-scrollbar.jquery.min.js"></script>
<script src="node_modules/waves.js"></script>
<script src="node_modules/sidebarmenu.js"></script>
<script src="node_modules/custom.min.js"></script>
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