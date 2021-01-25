<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Kronos - Admin</title>
    <link href="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="../css/style.min.css" rel="stylesheet">
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
            <div class="navbar-header">
                <a class="navbar-brand" href="statistiques.php">
                    <b>
                        <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                        <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                    </b>
                    <span>
             <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo" style="transform: scale(0.6);margin-left: -50px;" />
             <img src="../assets/images/logo-light-text.png" class="light-logo" alt="homepage" /></span> </a>
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
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
                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item dropdown">
                        <a class="header-icon"><img src="../assets/images/icon/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                        <a class="header-icon" ><img src="../assets/images/icon/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected"><img src="../assets/images/icon/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected" ><img src="../assets/images/icon/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected"><img src="../assets/images/icon/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected" ><img src="../assets/images/icon/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        <a class="header-icon header-icon-unselected"><img src="../assets/images/icon/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="left-sidebar">
        <div class="scroll-sidebar">
            <div class="user-profile">
                <div class="user-pro-body">
                    <div><img src="../assets/images/users/admin2.jpg" alt="user-img" class="img-circle"></div>
                    <div class="dropdown">
                        <a href=""class="u-dropdown link hide-menu" role="button" aria-expanded="false" style="color:#fff!important;">Maxime William Martin<br>Administrateur</a>
                        <div class="center-block" style="margin-left:34%;margin-top:5px;">
                            <a class="header-icon" style="width:16%;"><img src="../assets/images/icon/icon_pretabc.png" style="width:100%;" alt="" class=""></a>
                            <a class="header-icon" style="width:16%;"><img src="../assets/images/icon/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-small-cap">--- GESTION</li>
                    <li><a class="waves-effect waves-dark" href="statistiques.php" aria-expanded="false"><i class="fa fa-chart-pie"></i><span class="hide-menu">Statistiques</span></a></li>
                    <li><a class="waves-effect waves-dark" href="demandes.php" aria-expanded="false"><i class="fa fa-edit"></i><span class="hide-menu">Demandes</span></a></li>
                    <li><a class="waves-effect waves-dark" href="membres.php" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Membres</span></a></li>
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
                    <h4 class="text-white">Modifier une demande de prêt</h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                            <li class="breadcrumb-item active">Ajouter ou modifier un membre</li>
                        </ol>
                        <button onclick="location.href='membres_edit.php';" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Voir le membre lié à ce prêt</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-user header-icon-card"></i>Informations de la demande</h4>
                            <br>
                            <form class="form-material form-horizontal">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">No d'entente</label>
                                        <div class="col-md-12">
                                            <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="7723">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">Date d'inscription au prêt</label>
                                        <div class="col-md-12">
                                            <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="2020-08-25 12:39:26">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">Message</label>
                                        <div class="col-md-12">
                                            <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12" for="dept">État du prêt</label>
                                        <div class="col-sm-12">
                                            <select class="form-control text-muted" id="">
                                                <option>Veuillez sélectionner</option>
                                                <option value="">Non-traité</option>
                                                <option value="">Accepté</option>
                                                <option value="">Refusé</option>
                                                <option value="">Commencé</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12" for="dept">Responsable du prêt</label>
                                        <div class="col-sm-12">
                                            <select class="form-control text-muted" id="">
                                                <option>Veuillez sélectionner</option>
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
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12" for="dept">Statut du prêt</label>
                                        <div class="col-sm-12">
                                            <select class="form-control text-muted" id="">
                                                <option>Veuillez sélectionner</option>
                                                <option value="">Manque d'informations</option>
                                                <option value="">IBV manquante</option>
                                                <option value="">Renouvellement</option>
                                                <option value="">Appeler l'employeur</option>
                                                <option value="">À regarder</option>
                                                <option value="">Offre</option>
                                                <option value="">Contrat à préparer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">Montant du prêt</label>
                                        <div class="col-md-12">
                                            <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="2500.00$">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12" for="dept">Fréquence des remboursements</label>
                                        <div class="col-sm-12">
                                            <select class="form-control text-muted" id="">
                                                <option>Veuillez sélectionner</option>
                                                <option value="Chaque semaine">Chaque semaine</option>
                                                <option value="Aux 2 semaines" selected="">Aux 2 semaines</option>
                                                <option value="Mensuel">Mensuel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">Prédiction de faillaite au cours des 3 prochains mois</label>
                                        <div class="col-sm-12">
                                            <select class="form-control text-muted" id="">
                                                <option>Veuillez sélectionner</option>
                                                <option selected value="">Non</option>
                                                <option value="">Oui</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">Date de la prochaine paie</label>
                                        <div class="col-md-12">
                                            <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="2020-08-25 12:39:26">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12">Note</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control text-muted" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-university header-icon-card"></i>Informations sur les contrats</h4>
                            <br>
                            <form class="form-material form-horizontal">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-sm-12">Créations des contrats - Importation des données de Margill (fichier Excel)</label>
                                        <div class="col-sm-12">
                                            <img class="img-responsive" src="../assets/images/users/admin1.jpg" alt="" style="max-width:120px;">
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control text-muted" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div> <span class="input-group-addon btn btn-default btn-file"> <span class="fileinput-new">Veuillez sélectionner une image</span> <span class="fileinput-exists"></span>
                                                    <input type="file" name="..."> </span> <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Effacer</a> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">Date d'importation du contrat</label>
                                        <div class="col-md-12">
                                            <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="2020-08-25 12:39:26">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fa fa-pencil-alt header-icon-card"></i>Acceptation du contrat</h4>
                            <br>
                            <form class="form-material form-horizontal">
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-12" for="">Date d'acceptation du contrat</label>
                                        <div class="col-md-12">
                                            <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="2020-08-25 12:39:26">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card" style="background-color: #000;">
                        <div class="card-body">
                            <form class="form-material form-horizontal">
                                <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Sauvegarder</button>
                                <button type="submit" class="btn btn-dark waves-effect waves-light">Annuler</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


        <footer class="footer">
            © 2020 Gestion Kronos. Tous droits réservés.
        </footer>
</div>

<script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script src="../assets/node_modules/popper/popper.min.js"></script>
<script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
<script src="../assets/node_modules/switchery/dist/switchery.min.js"></script>
<script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script src="../assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
<script src="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
<script src="../assets/node_modules/dff/dff.js" type="text/javascript"></script>
<script type="text/javascript" src="../assets/node_modules/multiselect/js/jquery.multi-select.js"></script>
<script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
<script src="dist/js/custom.min.js"></script>
<script src="dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>

</body>
</html>