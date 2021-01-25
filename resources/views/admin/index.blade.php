<?php
//include("fusioncharts/fusioncharts.php");

?>

@extends('layouts.admin')

@section('content')
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
        .btn-bluebutton{
            background-color: blue;
            border-color: blue;
        }
        .btn-light_blue{
            background-color: lightblue;
            border-color: lightblue;
        }
    </style>
    <script type="text/javascript" src="{{ asset('js/fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/fusioncharts.charts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/fusioncharts.theme.fint.js') }}"></script>
    <!-- FusionCharts Library -->

    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>

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
                    <a href="{{ route('demande.index') }}"> <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>Voir toutes les demandes</button></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card" style="background-color: rgba(0,0,0,0.08);">
                    <div class="card-body">
                        <div class="table-responsive">

                            {!! Form::open(['action' => 'AdminController@index','method' => 'GET', 'class'=> 'search']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Filtrer les statistiques par mois et année</label>

                                        {!! Form::select('mois_annee', ['' => 'Choisissez le mois','tout' => 'Tout les mois','Janvier 2021' => 'Janvier 2021','Décembre 2020' => 'Décembre 2020', 'Novembre 2020' => 'Novembre 2020', 'Octobre 2020' => 'Octobre 2020', 'Septembre 2020' => 'Septembre 2020', 'Août 2020' => 'Août 2020','Juillet 2020' => 'Juillet 2020', 'Juin 2020' => 'Juin 2020','Mai 2020' => 'Mai 2020','Avril 2020' => 'Avril 2020','Mars 2020' => 'Mars 2020','Fevrier 2020' => 'Fevrier 2020','Janvier 2020' => 'Janvier 2020'], null, array('class' => 'form-control'));!!}<br>


                                    </div>
                                </div>

                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Filtrer les statistiques par année</label>


                                        {!! Form::select('annee', ['' => 'Choisissez l\'année', 2019 => 2019,2020 => 2020, 2021 => 2021], null, array('class' => 'form-control'));!!}<br>
                                    </div>
                                </div>
                            </div>
                            <button  type="submit"  class="btn btn-dark d-none d-lg-block m-l-15" style="width:100%;margin-left:4px;"><i class="fa fa-plus-circle"></i> Filtrer les résultats</button>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
        @if($montant_nouveaux_prets)
        <?php
            $array = json_decode(json_encode($montant_nouveaux_prets), true);
                    $nombre_pret= $array[0]['nombre_pret'];
                    $montant= ($array[0]['montant']);

                    $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                    $montant_kreditpret = ($array[1]['montant']);

                    $total= $montant + $montant_kreditpret;
                    //var_dump($montant.$montant_kreditpret);




            /*echo 'Montant des nouveaux prets : </br>';
            echo 'nbr pret :' .$nombre_pret;  echo '</br>';
            echo 'montant :' .$montant;  echo '</br>';
            echo 'statut :'.$statut;  echo '</br>';
            echo '</br>';*/

            ?>

        <div class="card-group">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h3><i class="fa fa-pie-chart header-icon-card-opacity" aria-hidden="true"></i></h3>
                                    <p class="text-muted">MONTANT DES NOUVEAUX PRETS</p>
                                </div>
                                <div class="ml-auto">
                                    <h2 class="counter text-primary"><?php  if($total == null)
                                        echo '0'; else echo $total; ?>$</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted" style="margin-top:10px;">Montant des prêts - Total : <b><?php  if($total == null)
                                        echo '0'; else echo $total; ?>$</b></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($nouvelles_demandes_prets)

                <?php


                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);


                //var_dump($array2[0]['nombre_pret']);
                $nombre_pret2= $array2[0]['nombre_pret'];
                $montant2= ($array2[0]['montant']);

                if(count($array2) == 1){
                    $nombre_pret_kreditpret2 = 0;
                    $montant_kreditpret2 = 0;
                    $total_pret= $nombre_pret2 + $nombre_pret_kreditpret2;
                    $total2= $montant2 + $montant_kreditpret2;
                }
                else{
                $nombre_pret_kreditpret2 = ($array2[1]['nombre_pret']);
                $montant_kreditpret2 = ($array2[1]['montant']);

                $total_pret= $nombre_pret2 + $nombre_pret_kreditpret2;
                $total2= $montant2 + $montant_kreditpret2;
                }
                //var_dump($nombre_pret2);

               /* echo 'Montant des nouveaux prets : </br>';
                echo 'nbr pret :' .$nombre_pret2;  echo '</br>';
                echo 'montant :' .$montant2;  echo '</br>';
                echo 'statut :'.$statut2;  echo '</br>';
                echo '</br>';*/

                ?>
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
                                    <h2 class="counter text-cyan"><?php  if($total_pret == 0)
                                            echo '0'; else echo $total_pret; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-cyan" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted" style="margin-top:10px;">Demandes de prêts acceptées - Total : <b><?php  if($total2 == null)
                                        echo '0'; else echo $total2; ?>$</b></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

                <?php
                /*   $array4 = json_decode(json_encode($nouveaux_recouvrements_prets), true);

                  var_dump($nouveaux_recouvrements_prets);
                  $nombre_renouvellement= $array4[0]['seq_nbr_pret'];

                  if($array4[0]['seq_nbr_pret'] != '1') {
                      echo '<span class="label label-info">Renouvellement de prêt</span>';
                  } else {
                      echo '<span class="label btn-black">Nouveau prêt</span>';
                  }




                 $nombre_pret4= $array4[0]['nombre_pret'];
                  $montant4= ($array4[0]['montant']);
                  $statut4= $array4[0]['statut'];

                  $nombre_pret_kreditpret4= $array4[1]['nombre_pret'];
                  $montant_keditpret3= ($array4[1]['montant']);


                  $total_pret3= $nombre_pret4 + $nombre_pret_kreditpret4;
                  $total3= $montant4 + $montant_keditpret3;*/
                //var_dump($nombre_pret2);

                /* echo 'Montant des nouveaux prets : </br>';
                 echo 'nbr pret :' .$nombre_pret2;  echo '</br>';
                 echo 'montant :' .$montant2;  echo '</br>';
                 echo 'statut :'.$statut2;  echo '</br>';
                 echo '</br>';*/

                ?>
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
                                    <h2 class="counter text-cyan" style="color:#03a9f3!important;"><?php
                                            echo '0';   ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="background-color: #03a9f3 !important;width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted" style="margin-top:10px;">Demandes de renouvellements de prêts - Total : <b><?php
                                        echo '0'; ?>$</b></p>
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
                                    <h2 class="counter text-danger">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 85%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted" style="margin-top:10px;">Demandes de recouvrements de prêts - Total : <b>0</b></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        </div>


    </div>

        <div class="row">

                <div class="col-12">

                <?php
                       if ($totalpretabc)
                        {
                            //echo $totalpretabc;

                        }
                        else
                        {
                            //echo "Key does not exist!";
                        }
                        if ($totalkreditpret)
                        {
                            //echo $totalkreditpret;

                        }
                        else
                        {
                            //echo "Key does not exist!";
                        }


                // Chart Configuration stored in Associative Array
                $arrChartConfig = array(
                "chart" => array(
                "caption" => "Montant total des prêts[accepté]",
                "subCaption" => "Montant total des prêts accepté",
                "xAxisName" => "Companie",
                "yAxisName" => "Montant cumulatif",
                "numberSuffix" => "",
                "theme" => "fusion"
                )
                );
                // An array of hash objects which stores data
                $arrChartData = array(
                ["PretABC", $totalpretabc],
                ["Kreditpret", $totalkreditpret]
                );
                $arrLabelValueData = array();

                // Pushing labels and values
                for($i = 0; $i < count($arrChartData); $i++) {
                array_push($arrLabelValueData, array(
                "label" => $arrChartData[$i][0], "value" => $arrChartData[$i][1]
                ));
                }
                $arrChartConfig["data"] = $arrLabelValueData;

                // JSON Encode the data to retrieve the string containing the JSON representation of the data in the array.
                $jsonEncodedData = json_encode($arrChartConfig);

                // chart object
                $Chart = new FusionCharts("column2d", "MyFirstChart" , "700", "400", "chart-container", "json", $jsonEncodedData);

                // Render the chart
                $Chart->render();
                ?>

                    <div id="chart-container" style="padding-bottom: 20px;">Chart will render here!</div>

            </div>

        </div>
<div class="container-fluid"  style="padding-left:0px;padding-right:0px;">
        <div class="row" style="margin-left:0px;margin-right:0px;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div>
                                <h5 class="card-title">Appercu des traitements des demandes de prêts</h5>
                                <h6 class="card-subtitle">Suivi des performances des employés</h6>
                            </div>
                            <div class="ml-auto">
                                <a href=""><button type="button" class="btn btn-info d-none d-lg-block m-l-15" style="float:right;"><i class="fa fa-plus-circle"></i> Consulter la liste complète des suivis de performance</button></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="font-light m-t-0">Rapport pour le mois en cours</h3></div>
                            <div class="col-6 align-self-center display-6 text-right">
                                <h2 class="text-success">  <?php echo $nombre_pret ;?> demandes</h2></div>
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
                                @if($alex_stats)
                                <td class="text-center">1</td>
                                <td class="txt-oflo">Alexandre bonneville</td>
                                <td class="txt-oflo">{{ $alex_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $alex_stats['commencé'] }}</td>
                                <td class="txt-oflo">{{ $alex_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $alex_stats['refuser']}}</td>
                                <td class="txt-oflo">{{ $alex_stats['accepté'] }}</td>
                                <td class="txt-oflo">{{ $alex_stats['accepté'] + $alex_stats['non-traité'] + $alex_stats['commencé'] +$alex_stats['refuser'] }}</td>
                                <td><span class="badge badge-success badge-pill">0</span></td>
                                <td><span class="badge badge-success badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                    @endif
                            </tr>
                            <tr>
                                @if($marc_antoine_stats)
                                <td class="text-center">2</td>
                                <td class="txt-oflo">Marc-Antoine</td>
                                <td class="txt-oflo">{{ $marc_antoine_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $marc_antoine_stats['commencé'] }}</td>
                                <td class="txt-oflo">{{ $marc_antoine_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $marc_antoine_stats['refuser'] }}</td>
                                <td class="txt-oflo">{{ $marc_antoine_stats['accepté'] }}</td>
                                <td class="txt-oflo">{{ $marc_antoine_stats['non-traité'] + $marc_antoine_stats['accepté'] + $marc_antoine_stats['refuser'] + $marc_antoine_stats['commencé']}}</td>
                                <td><span class="badge badge-success badge-pill">0</span></td>
                                <td><span class="badge badge-success badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr> @if($roxanne_stats)
                                <td class="text-center">3</td>
                                <td class="txt-oflo">Roxanne Lépine</td>
                                    <td class="txt-oflo">{{ $roxanne_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $roxanne_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $roxanne_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $roxanne_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $roxanne_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $roxanne_stats['non-traité'] + $roxanne_stats['commencé'] +  $roxanne_stats['refuser'] + $roxanne_stats['accepté']}}</td>
                                <td><span class="badge badge-success badge-pill">0</span></td>
                                <td><span class="badge badge-success badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>
                                @if($felixe_stats)
                                <td class="text-center">4</td>
                                <td class="txt-oflo">Félixe Léger</td>
                                <td class="txt-oflo">{{ $felixe_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $felixe_stats['commencé'] }}</td>
                                <td class="txt-oflo">{{ $felixe_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $felixe_stats['refuser'] }}</td>
                                <td class="txt-oflo">{{ $felixe_stats['accepté'] }}</td>
                                <td class="txt-oflo">{{ $felixe_stats['non-traité'] + $felixe_stats['commencé'] +  $felixe_stats['refuser'] + $felixe_stats['accepté']}}</td>
                                <td><span class="badge badge-warning badge-pill">0</span></td>
                                <td><span class="badge badge-warning badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>    @if($emily_stats)
                                <td class="text-center">5</td>
                                <td class="txt-oflo">Emily S</td>
                                <td class="txt-oflo">{{ $emily_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $emily_stats['commencé'] }}</td>
                                <td class="txt-oflo">{{ $emily_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $emily_stats['refuser'] }}</td>
                                <td class="txt-oflo">{{ $emily_stats['accepté'] }}</td>
                                <td class="txt-oflo">{{ $emily_stats['accepté'] + $emily_stats['refuser']+ $emily_stats['non-traité'] + $emily_stats['commencé']}}</td>
                                <td><span class="badge badge-warning badge-pill">0</span></td>
                                <td><span class="badge badge-warning badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                        @endif
                            </tr>
                            <tr>
                                @if($math_l_stats)
                                <td class="text-center">6</td>
                                <td class="txt-oflo">Math L</td>
                                <td class="txt-oflo">{{ $math_l_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $math_l_stats['commencé'] }}</td>
                                <td class="txt-oflo">{{ $math_l_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $math_l_stats['refuser'] }}</td>
                                <td class="txt-oflo">{{ $math_l_stats['accepté'] }}</td>
                                <td class="txt-oflo">{{ $math_l_stats['accepté'] + $math_l_stats['refuser']+ $math_l_stats['non-traité'] + $math_l_stats['commencé']}}</td>
                                <td><span class="badge badge-warning badge-pill">0</span></td>
                                <td><span class="badge badge-warning badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                    @endif
                            </tr>
                            <tr>
                                @if($emilie_stats)
                                <td class="text-center">7</td>
                                <td class="txt-oflo">Emilie</td>
                                <td class="txt-oflo">{{ $emilie_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $emilie_stats['commencé'] }}</td>
                                <td class="txt-oflo">{{ $emilie_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $emilie_stats['refuser'] }}</td>
                                <td class="txt-oflo">{{ $emilie_stats['accepté'] }}</td>
                                <td class="txt-oflo">{{ $emilie_stats['accepté'] + $emilie_stats['refuser']+ $emilie_stats['non-traité'] + $emilie_stats['commencé']}}</td>
                                <td><span class="badge badge-danger badge-pill">0</span></td>
                                <td><span class="badge badge-danger badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>
                                @if($mathieu_stats)
                                <td class="text-center">8</td>
                                <td class="txt-oflo">Mathieu Dugré</td>
                                <td class="txt-oflo">{{ $mathieu_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $mathieu_stats['commencé'] }}</td>
                                <td class="txt-oflo">{{ $mathieu_stats['non-traité'] }}</td>
                                <td class="txt-oflo">{{ $mathieu_stats['refuser'] }}</td>
                                <td class="txt-oflo">{{ $mathieu_stats['accepté'] }}</td>
                                <td class="txt-oflo">{{ $mathieu_stats['accepté'] + $mathieu_stats['refuser']+ $mathieu_stats['non-traité'] + $mathieu_stats['commencé']}}</td>
                                <td><span class="badge badge-danger badge-pill">0</span></td>
                                <td><span class="badge badge-danger badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>
                                @if($francois_stats)
                                    <td class="text-center">9</td>
                                    <td class="txt-oflo">Francois</td>
                                    <td class="txt-oflo">{{ $francois_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $francois_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $francois_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $francois_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $francois_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $francois_stats['accepté'] + $francois_stats['refuser']+ $francois_stats['non-traité'] + $francois_stats['commencé']}}</td>
                                    <td><span class="badge badge-danger badge-pill">0</span></td>
                                    <td><span class="badge badge-danger badge-pill">0</span></td>
                                    <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>
                                @if($patrick_stats)
                                <td class="text-center">10</td>
                                <td class="txt-oflo">Patrick</td>
                                    <td class="txt-oflo">{{ $patrick_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $patrick_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $patrick_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $patrick_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $patrick_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $patrick_stats['accepté'] + $patrick_stats['refuser']+ $patrick_stats['non-traité'] + $patrick_stats['commencé']}}</td>
                                    <td><span class="badge badge-danger badge-pill">0</span></td>
                                <td><span class="badge badge-danger badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>
                                @if($khalida_stats)
                                    <td class="text-center">11</td>
                                    <td class="txt-oflo">Khalida</td>
                                    <td class="txt-oflo">{{ $khalida_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $khalida_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $khalida_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $khalida_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $khalida_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $khalida_stats['accepté'] + $khalida_stats['refuser']+ $khalida_stats['non-traité'] + $khalida_stats['commencé']}}</td>
                                    <td><span class="badge badge-danger badge-pill">0</span></td>
                                    <td><span class="badge badge-danger badge-pill">0%</span></td>
                                    <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>   @if($nancy_stats)
                                <td class="text-center">12</td>
                                <td class="txt-oflo">Nancy</td>
                                    <td class="txt-oflo">{{ $nancy_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $nancy_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $nancy_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $nancy_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $nancy_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $nancy_stats['accepté'] + $nancy_stats['refuser']+ $nancy_stats['non-traité'] + $nancy_stats['commencé']}}</td>
                                    <td><span class="badge badge-danger badge-pill">0</span></td>
                                <td><span class="badge badge-danger badge-pill">0%</span></td>
                                <td class="txt-oflo"></td>
                                @endif
                            </tr>
                            <tr>@if($roseline_stats)
                                <td class="text-center">13</td>
                                <td class="txt-oflo">Roseline</td>
                                    <td class="txt-oflo">{{ $roseline_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $roseline_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $roseline_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $roseline_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $roseline_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $roseline_stats['accepté'] + $roseline_stats['refuser']+ $roseline_stats['non-traité'] + $roseline_stats['commencé']}}</td>
                                    <td><span class="badge badge-danger badge-pill">1</span></td>
                                <td><span class="badge badge-danger badge-pill">0%</span></td>
                                <td class="txt-oflo"></td> @endif
                            </tr>
                            <tr>@if($maxime_stats)
                                <td class="text-center">14</td>
                                <td class="txt-oflo">Maxime</td>
                                    <td class="txt-oflo">{{ $maxime_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $maxime_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $maxime_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $maxime_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $maxime_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $maxime_stats['accepté'] + $maxime_stats['refuser']+ $maxime_stats['non-traité'] + $maxime_stats['commencé']}}</td>
                                    <td><span class="badge badge-danger badge-pill">1</span></td>
                                <td><span class="badge badge-danger badge-pill">0%</span></td>
                                <td class="txt-oflo"></td> @endif
                            </tr>
                            <tr>@if($alicia_stats)
                                    <td class="text-center">15</td>
                                    <td class="txt-oflo">Alicia</td>
                                    <td class="txt-oflo">{{ $alicia_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $alicia_stats['commencé'] }}</td>
                                    <td class="txt-oflo">{{ $alicia_stats['non-traité'] }}</td>
                                    <td class="txt-oflo">{{ $alicia_stats['refuser'] }}</td>
                                    <td class="txt-oflo">{{ $alicia_stats['accepté'] }}</td>
                                    <td class="txt-oflo">{{ $alicia_stats['accepté'] + $alicia_stats['refuser']+ $alicia_stats['non-traité'] + $alicia_stats['commencé']}}</td>
                                    <td><span class="badge badge-danger badge-pill">0</span></td>
                                    <td><span class="badge badge-danger badge-pill">0%</span></td>
                                    <td class="txt-oflo"></td> @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
        <!-- <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 text-left">
                                <h4 class="card-title">Liste des dernières demandes de prêt de la dernière heure</h4>
                            </div>
                            <div class="col-md-7 text-right">
                                <a href="{{ route('demande.index') }}"><button type="button" class="btn btn-info d-none d-lg-block m-l-15" style="float:right;"><i class="fa fa-plus-circle"></i> Consulter la liste complète des demandes de prêts</button></a>
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
                                        <img src="dist/images/users/pretabcsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
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
                                    <td style="text-align: center!important;"><img src="dist/images/users/pretabcsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
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
                                    <td style="text-align: center!important;"><img src="dist/images/users/pretabcsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
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
                                    <td style="text-align: center!important;"><img src="dist/images/users/kreditpretsmall.jpg" alt="user" class="img-circle img-responsive" style="max-height: 40px;"></td>
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
        </div>-->
            <!--
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
-->


                </div>

@endsection
