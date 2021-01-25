<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('mois_annee')) {

            $mois_annee = $request->input('mois_annee');

            //dd($mois_annee);

            //TOUT
            if($mois_annee == 'tout'){
                //TOUT

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur)
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur)
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394)
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394)
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394 )
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394)
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456) 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456)
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456)
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456)
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535)
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535)
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535)
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535)");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131)
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131)
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131)");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131)
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389)");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389)");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 )
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389)
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389)");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633)
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633)
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633)");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304)
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 )");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));




            }

            //JANVIER 2021
            if($mois_annee == 'Janvier 2021'){
                //JANVIER 2021

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription between '2021-01-01' and '2021-01-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2021-01-01' and '2021-01-31')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2021-01-01' and '2021-01-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2021-01-01' and '2021-01-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2021-01-01' and '2021-01-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2021-01-01' and '2021-01-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2021-01-01' and '2021-01-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2021-01-01' and '2021-01-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2021-01-01' and '2021-01-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2021-01-01' and '2021-01-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2021-01-01' and '2021-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2021-01-01' and '2021-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));




                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'total', 'alex_stats'));
            }

            //Décembre 2020
            if($mois_annee == 'Décembre 2020'){
                //MONTANT NOUVEAUX PRETS


                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE  date_inscription between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-12-01' and '2020-12-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);
                //var_dump($montant_nouveaux_prets);

                /*var_dump($array[0]);
                var_dump($array[0]['montant']);*/


                //var_dump($array[0]);
                $nombre_pret= $array[0]['nombre_pret'];
                $montant= ($array[0]['montant']);

                $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                $montant_kreditpret = ($array[1]['montant']);

                $total= $montant + $montant_kreditpret;
                //dd($total)
;

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-12-01' and '2020-12-31')
");

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
               //var_dump($montant_nouveaux_prets);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-12-01' and '2020-12-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //dd($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-12-01' and '2020-12-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //dd($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);


                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-12-01' and '2020-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES JANVIER 2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-12-01' and '2020-12-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-12-01' and '2020-12-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-12-01' and '2020-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-12-01' and '2020-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-12-01' and '2020-12-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-12-01' and '2020-12-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-12-01' and '2020-12-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-12-01' and '2020-12-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-12-01' and '2020-12-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-12-01' and '2020-12-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-12-01' and '2020-12-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-12-01' and '2020-12-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-12-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-12-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //Novembre 2020
            if($mois_annee == 'Novembre 2020'){
                //MONTANT NOUVEAUX PRETS
                //MONTANT NOUVEAUX PRETS


                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-11-01' and '2020-11-30')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-11-01' and '2020-11-30')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-11-01' and '2020-11-30'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-11-01' and '2020-11-30'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-11-01' and '2020-11-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES JANVIER 2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-11-01' and '2020-11-30') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-11-01' and '2020-11-30')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-11-01' and '2020-11-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-11-01' and '2020-11-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-11-01' and '2020-11-30')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-11-01' and '2020-11-30')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-11-01' and '2020-11-30')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-11-01' and '2020-11-30')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-11-01' and '2020-11-30')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-11-01' and '2020-11-30')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-11-01' and '2020-11-30')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4633 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-11-01' and '2020-11-30')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-11-01' and '2020-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-11-01' and '2020-11-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES
//dd($montant_nouveaux_prets);


                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //septembre 2020
            if($mois_annee == 'Septembre 2020'){
                //MONTANT NOUVEAUX PRETS


                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE  date_inscription between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-09-01' and '2020-09-30')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);
                //var_dump($montant_nouveaux_prets);

                /*var_dump($array[0]);
                var_dump($array[0]['montant']);*/


                //var_dump($array[0]);
                $nombre_pret= $array[0]['nombre_pret'];
                $montant= ($array[0]['montant']);

                $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                $montant_kreditpret = ($array[1]['montant']);

                $total= $montant + $montant_kreditpret;
                //dd($total)
                ;

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-09-01' and '2020-09-30')
");

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
                //var_dump($montant_nouveaux_prets);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-09-01' and '2020-09-30'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //dd($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-09-01' and '2020-09-30'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //dd($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);


                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-09-01' and '2020-09-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES  2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-09-01' and '2020-09-30') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-09-01' and '2020-09-30')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-09-01' and '2020-09-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-09-01' and '2020-09-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-09-01' and '2020-09-30')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-09-01' and '2020-09-30')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-09-01' and '2020-09-30')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-09-01' and '2020-09-30')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-09-01' and '2020-09-30')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-09-01' and '2020-09-30')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-09-01' and '2020-09-30')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-09-01' and '2020-09-30')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-09-01' and '2020-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-09-01' and '2020-09-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //Septembre 2020
            if($mois_annee == 'Octobre 2020'){
                //MONTANT NOUVEAUX PRETS
                //MONTANT NOUVEAUX PRETS


                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE  date_inscription between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-10-01' and '2020-10-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);
                //var_dump($montant_nouveaux_prets);

                /*var_dump($array[0]);
                var_dump($array[0]['montant']);*/


                //var_dump($array[0]);
                $nombre_pret= $array[0]['nombre_pret'];
                $montant= ($array[0]['montant']);

                $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                $montant_kreditpret = ($array[1]['montant']);

                $total= $montant + $montant_kreditpret;
                //dd($total)
                ;

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-10-01' and '2020-10-31')
");

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
                //var_dump($montant_nouveaux_prets);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-10-01' and '2020-10-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //dd($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-10-01' and '2020-10-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //dd($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);


                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-10-01' and '2020-10-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES JANVIER 2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-10-01' and '2020-10-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-10-01' and '2020-10-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-10-01' and '2020-10-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-10-01' and '2020-10-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-10-01' and '2020-10-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-10-01' and '2020-10-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-10-01' and '2020-10-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-10-01' and '2020-10-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-10-01' and '2020-10-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-10-01' and '2020-10-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-10-01' and '2020-10-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4633 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-10-01' and '2020-10-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-10-01' and '2020-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-10-01' and '2020-10-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //Août 2020
            if($mois_annee == 'Août 2020'){
                //MONTANT NOUVEAUX PRETS

                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE  date_inscription between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-08-01' and '2020-08-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);
                //var_dump($montant_nouveaux_prets);

                /*var_dump($array[0]);
                var_dump($array[0]['montant']);*/


                //var_dump($array[0]);
                $nombre_pret= $array[0]['nombre_pret'];
                $montant= ($array[0]['montant']);

                $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                $montant_kreditpret = ($array[1]['montant']);

                $total= $montant + $montant_kreditpret;
                //dd($total)
                ;

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-08-01' and '2020-08-31')
");

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
                //var_dump($montant_nouveaux_prets);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-08-01' and '2020-08-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //dd($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-08-01' and '2020-08-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //dd($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);


                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-08-01' and '2020-08-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES  2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-08-01' and '2020-08-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-08-01' and '2020-08-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-08-01' and '2020-08-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-08-01' and '2020-08-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-08-01' and '2020-08-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-08-01' and '2020-08-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-08-01' and '2020-08-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-08-01' and '2020-08-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-08-01' and '2020-08-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-08-01' and '2020-08-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-08-01' and '2020-08-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4633 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-08-01' and '2020-08-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-08-01' and '2020-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-08-01' and '2020-08-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));



            }

            //Juillet 2020
            if($mois_annee == 'Juillet 2020'){
                //MONTANT NOUVEAUX PRETS

                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE  date_inscription between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-07-01' and '2020-07-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);
                //var_dump($montant_nouveaux_prets);

                /*var_dump($array[0]);
                var_dump($array[0]['montant']);*/


                //var_dump($array[0]);
                $nombre_pret= $array[0]['nombre_pret'];
                $montant= ($array[0]['montant']);

                $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                $montant_kreditpret = ($array[1]['montant']);

                $total= $montant + $montant_kreditpret;
                //dd($total)
                ;

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-07-01' and '2020-07-31')
");

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
                //var_dump($montant_nouveaux_prets);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-07-01' and '2020-07-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //dd($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-07-01' and '2020-07-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //dd($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);


                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-07-01' and '2020-07-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES  2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-07-01' and '2020-07-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-07-01' and '2020-07-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-07-01' and '2020-07-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-07-01' and '2020-07-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-07-01' and '2020-07-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-07-01' and '2020-07-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-07-01' and '2020-07-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-07-01' and '2020-07-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-07-01' and '2020-07-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-07-01' and '2020-07-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-07-01' and '2020-07-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4633 and prets.date_creation between'2020-07-01' and '2020-07-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-07-01' and '2020-07-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-07-01' and '2020-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-07-01' and '2020-07-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //Juin 2020
            if($mois_annee == 'Juin 2020'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE  date_inscription between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-06-01' and '2020-06-30')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);
                //var_dump($montant_nouveaux_prets);

                /*var_dump($array[0]);
                var_dump($array[0]['montant']);*/


                //var_dump($array[0]);
                $nombre_pret= $array[0]['nombre_pret'];
                $montant= ($array[0]['montant']);

                $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                $montant_kreditpret = ($array[1]['montant']);

                $total= $montant + $montant_kreditpret;
                //dd($total)
                ;

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-06-01' and '2020-06-30')
");

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
                //var_dump($montant_nouveaux_prets);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-06-01' and '2020-06-30'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //dd($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-06-01' and '2020-06-30'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //dd($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);


                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-06-01' and '2020-06-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES  2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-06-01' and '2020-06-30') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-06-01' and '2020-06-30')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-06-01' and '2020-06-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-06-01' and '2020-06-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-06-01' and '2020-06-30')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-06-01' and '2020-06-30')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-06-01' and '2020-06-30')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-06-01' and '2020-06-30')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-06-01' and '2020-06-30')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-06-01' and '2020-06-30')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-06-01' and '2020-06-30')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4633 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-06-01' and '2020-06-30')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-06-01' and '2020-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-06-01' and '2020-06-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));


            }

            //Mai 2020
            if($mois_annee == 'Mai 2020'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE  date_inscription between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE date_inscription between '2020-05-01' and '2020-05-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);
                //var_dump($montant_nouveaux_prets);

                /*var_dump($array[0]);
                var_dump($array[0]['montant']);*/


                //var_dump($array[0]);
                $nombre_pret= $array[0]['nombre_pret'];
                $montant= ($array[0]['montant']);

                $nombre_pret_kreditpret = ($array[1]['nombre_pret']);
                $montant_kreditpret = ($array[1]['montant']);

                $total= $montant + $montant_kreditpret;
                //dd($total)
                ;

                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-05-01' and '2020-05-31')
");

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
                //var_dump($montant_nouveaux_prets);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-05-01' and '2020-05-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //dd($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-05-01' and '2020-05-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //dd($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);


                //dd($total);

                //STATISTIQUES DES EMPLOYES

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-05-01' and '2020-05-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES  2021
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-05-01' and '2020-05-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-05-01' and '2020-05-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-05-01' and '2020-05-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-05-01' and '2020-05-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-05-01' and '2020-05-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-05-01' and '2020-05-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-05-01' and '2020-05-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-05-01' and '2020-05-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-05-01' and '2020-05-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-05-01' and '2020-05-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-05-01' and '2020-05-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4633 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-05-01' and '2020-05-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-05-01' and '2020-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-05-01' and '2020-05-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($alicia_stats);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));


            }

            //Avril 2020
            if($mois_annee == 'Avril 2020'){
                //MONTANT NOUVEAUX PRETS

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription between '2020-04-01' and '2020-04-30')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-04-01' and '2020-04-30')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-04-01' and '2020-04-30'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-04-01' and '2020-04-30'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-04-01' and '2020-04-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-04-01' and '2020-04-30') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-04-01' and '2020-04-30')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-04-01' and '2020-04-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-04-01' and '2020-04-30')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-04-01' and '2020-04-30')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-04-01' and '2020-04-30')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-04-01' and '2020-04-30')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-04-01' and '2020-04-30')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-04-01' and '2020-04-30')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-04-01' and '2020-04-30')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-04-01' and '2020-04-30')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-04-01' and '2020-04-30')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-04-01' and '2020-04-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-04-01' and '2020-04-30')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //Mars 2020
            if($mois_annee == 'Mars 2020'){
                //MONTANT NOUVEAUX PRETS

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription between '2020-03-01' and '2020-03-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-03-01' and '2020-03-31')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-03-01' and '2020-03-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-03-01' and '2020-03-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-03-01' and '2020-03-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-03-01' and '2020-03-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-03-01' and '2020-03-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-03-01' and '2020-03-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-03-01' and '2020-03-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-03-01' and '2020-03-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-03-01' and '2020-03-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-03-01' and '2020-03-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-03-01' and '2020-03-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-03-01' and '2020-03-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-03-01' and '2020-03-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-03-01' and '2020-03-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-03-01' and '2020-03-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-03-01' and '2020-03-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-03-01' and '2020-03-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //Fevrier 2020
            if($mois_annee == 'Fevrier 2020'){
                //MONTANT NOUVEAUX PRETS

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription between '2020-02-01' and '2020-02-29')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-02-01' and '2020-02-29')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-02-01' and '2020-02-29'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-02-01' and '2020-02-29'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-02-01' and '2020-02-29')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-02-01' and '2020-02-29') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-02-01' and '2020-02-29')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-02-01' and '2020-02-29')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-02-01' and '2020-02-29')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-02-01' and '2020-02-29')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-02-01' and '2020-02-29')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-02-01' and '2020-02-29')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-02-01' and '2020-02-29')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-02-01' and '2020-02-29')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-02-01' and '2020-02-29')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-02-01' and '2020-02-29')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-02-01' and '2020-02-29')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-02-01' and '2020-02-29')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-02-01' and '2020-02-29')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }

            //Janvier 2020
            if($mois_annee == 'Janvier 2020'){
                //MONTANT NOUVEAUX PRETS

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription between '2020-01-01' and '2020-01-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-01-31')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-01-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-01-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-01-01' and '2020-01-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-01-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-01-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-01-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-01-01' and '2020-01-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-01-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-01-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-01-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-01-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-01-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-01-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-01-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-01-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-01-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-01-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }


            //Décembre 2019
            if($mois_annee == 'Décembre 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-12-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-01-01' and '2019-12-31')
");
            }

            //Novembre 2019
            if($mois_annee == 'Novembre 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-11-01' and '2019-11-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription  between '2019-11-01' and '2019-11-30')
");
            }

            //Octobre 2019
            if($mois_annee == 'Octobre 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-10-01' and '2019-10-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-10-01' and '2019-10-31')
");
            }


            //Septembre 2019
            if($mois_annee == 'Septembre 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription  between '2019-09-01' and '2019-09-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-09-01' and '2019-09-30')
");
            }

            //Août 2019
            if($mois_annee == 'Août 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-08-01' and '2019-08-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-08-01' and '2019-08-31')
");
            }

            //Juillet 2019
            if($mois_annee == 'Juillet 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-07-01' and '2019-07-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-07-01' and '2019-07-31')
");
            }

            //Juin 2019
            if($mois_annee == 'Juin 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-06-01' and '2019-06-30')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-06-01' and '2019-06-30')
");
            }


            //Mai 2019
            if($mois_annee == 'Mai 2019'){
                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-05-01' and '2019-05-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2019-05-01' and '2019-05-31')
");
            }




         ////////////////////////////////////////////////////////////////////////////////////////////////////////////FILTRE ANNEE/////////////
            ///




        }
        //FILTRE PAR ANNEE
        if($request->has('annee')) {

            $annee = $request->input('annee');

            //TOUT 2021
            if($annee == '2021'){
                //MONTANT NOUVEAUX PRETS

                dd('Statistiques à venir...');
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2021-01-01' and '2021-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Non-Traité' and date_inscription between '2021-01-01' and '2021-12-31')
");
            }
            //TOUT 2020
            if($annee == '2020'){
                //MONTANT NOUVEAUX PRETS
                //MONTANT NOUVEAUX PRETS
                //MONTANT NOUVEAUX PRETS

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription between '2020-01-01' and '2020-12-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-12-31')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-12-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2020-01-01' and '2020-12-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2020-01-01' and '2020-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-12-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-12-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2020-01-01' and '2020-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2020-01-01' and '2020-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-12-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-12-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-12-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2020-01-01' and '2020-12-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-12-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-12-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-12-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-12-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2020-01-01' and '2020-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2020-01-01' and '2020-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }
            if($annee == '2019'){


                //MONTANT NOUVEAUX PRETS
                //MONTANT NOUVEAUX PRETS

                //MONTANT NOUVEAUX PRETS
                $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription between '2019-01-01' and '2019-12-31')
");
                $array = json_decode(json_encode($montant_nouveaux_prets), true);



                //NOUVELLES DEMANDES DE PRETS
                $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2019-01-01' and '2019-12-31')
");

                $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);
                //dd($array2);

                /*NOUVEAUX RECOUVREMENTS PRETS
                LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
              /*$nouveaux_recouvrements_prets= DB::select("
                (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                UNION
                (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
                kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
                ");*/

                //dd($nouveaux_recouvrements_prets);

                //NOUVELLES DEMANDES DE PRETS
                $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2019-01-01' and '2019-12-31'
");


                $arraypretabc = json_decode(json_encode($totalpretabc), true);

                if($arraypretabc[0]['montant'] == null){
                    $arraypretabc= 0;
                    $totalpretabc= $arraypretabc;
                }
                else{
                    $totalpretabc= $arraypretabc[0]['montant'];
                    //var_dump($arraypretabc[0]['montant']);
                }
                //var_dump($totalpretabc);

                //NOUVELLES DEMANDES DE PRETS
                $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription between '2019-01-01' and '2019-12-31'
");


                //$arraypretabc = json_decode(json_encode($pretabc), true);

                $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

                if($arraykreditpret[0]['montant'] == null){
                    $arraykreditpret= 0;
                    $totalkreditpret= $arraykreditpret;
                }
                else{
                    $totalkreditpret= $arraykreditpret[0]['montant'];
                }

                //var_dump($totalkreditpret);


                //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
                $total['pretabc'] = $totalpretabc;
                $total['kreditpret'] = $totalkreditpret;

                //dd($total);

                //STATISTIQUES DES EMPLOYES JANVIER 2021

                //ALEX STATISTIQUES
                $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation between '2019-01-01' and '2019-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alex) > 1){
                    $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_refuser= $alex[0]->nombre_pret;
                }

                if(count($alex1) > 1){
                    $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_nontraite= $alex1[0]->nombre_pret;
                }

                if(count($alex2) > 1){
                    $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_commence= $alex2[0]->nombre_pret;
                }

                if(count($alex3) > 1){
                    $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                    //echo 'as pretabc and kreditpret';
                }
                else{
                    $alex_stat_accepte= $alex3[0]->nombre_pret;
                }

                $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
                //var_dump($alex_stats);


                //FELIXE STATISTIQUES DECEMBRE 2020
                //felixe

                $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation between '2019-01-01' and '2019-12-31') 
");
                $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($felixe) > 1){
                    $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

                }
                else{
                    $felixe_stat_refuser= $felixe[0]->nombre_pret;
                }

                if(count($felixe1) > 1){
                    $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

                }
                else{
                    $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
                }

                if(count($felixe2) > 1){
                    $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

                }
                else{
                    $felixe_stat_commence= $felixe2[0]->nombre_pret;
                }

                if(count($felixe3) > 1){
                    $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

                }
                else{
                    $felixe_stat_accepte= $felixe3[0]->nombre_pret;
                }

                $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
                //dd($felixe_stats);


                //ROXANNE STATISTIQUES JANVIER 2021
                $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation between '2019-01-01' and '2019-12-31')
 ");
                $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roxanne) > 1){
                    $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
                }

                if(count($roxanne1) > 1){
                    $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
                }

                if(count($roxanne2) > 1){
                    $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
                }

                if(count($roxanne3) > 1){
                    $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

                }
                else{
                    $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
                }

                $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
                //dd($roxanne_stats);


                $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation between '2019-01-01' and '2019-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($marc_antoine) > 1){
                    $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
                }

                if(count($marc_antoine1) > 1){
                    $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
                }

                if(count($marc_antoine2) > 1){
                    $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
                }

                if(count($marc_antoine3) > 1){
                    $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

                }
                else{
                    $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
                }

                $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
                //dd($marc_antoine_stats);



                $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation between '2019-01-01' and '2019-12-31')
");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emily) > 1){
                    $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

                }
                else{
                    $emily_stat_refuser= $emily[0]->nombre_pret;
                }

                if(count($emily1) > 1){
                    $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

                }
                else{
                    $emily_stat_nontraite= $emily1[0]->nombre_pret;
                }

                if(count($emily2) > 1){
                    $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

                }
                else{
                    $emily_stat_commence= $emily2[0]->nombre_pret;
                }

                if(count($emily3) > 1){
                    $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

                }
                else{
                    $emily_stat_accepte= $emily3[0]->nombre_pret;
                }

                $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
                //dd($emily_stats);




                $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation between '2019-01-01' and '2019-12-31')");



                $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation between '2019-01-01' and '2019-12-31')
");

                $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation between '2019-01-01' and '2019-12-31')
");

                $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation between '2019-01-01' and '2019-12-31')");


                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($math_l) > 1){
                    $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

                }
                else{
                    $math_l_stat_refuser= $math_l[0]->nombre_pret;
                }

                if(count($math_l1) > 1){
                    $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

                }
                else{
                    $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
                }

                if(count($math_l2) > 1){
                    $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

                }
                else{
                    $math_l_stat_commence= $math_l2[0]->nombre_pret;
                }

                if(count($math_l3) > 1){
                    $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

                }
                else{
                    $math_l_stat_accepte= $math_l3[0]->nombre_pret;
                }

                $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
                //dd($math_l_stats);


                $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($patrick) > 1){
                    $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

                }
                else{
                    $patrick_stat_refuser= $patrick[0]->nombre_pret;
                }

                if(count($patrick1) > 1){
                    $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

                }
                else{
                    $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
                }

                if(count($patrick2) > 1){
                    $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

                }
                else{
                    $patrick_stat_commence= $patrick2[0]->nombre_pret;
                }

                if(count($patrick3) > 1){
                    $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

                }
                else{
                    $patrick_stat_accepte= $patrick3[0]->nombre_pret;
                }

                $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
                //dd($patrick_stats);



                $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($emilie) > 1){
                    $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

                }
                else{
                    $emilie_stat_refuser= $emilie[0]->nombre_pret;
                }

                if(count($emilie1) > 1){
                    $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

                }
                else{
                    $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
                }

                if(count($emilie2) > 1){
                    $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

                }
                else{
                    $emilie_stat_commence= $emilie2[0]->nombre_pret;
                }

                if(count($emilie3) > 1){
                    $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

                }
                else{
                    $emilie_stat_accepte= $emilie3[0]->nombre_pret;
                }

                $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
                //dd($emilie_stats);



                $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($mathieu) > 1){
                    $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
                }

                if(count($mathieu1) > 1){
                    $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
                }

                if(count($mathieu2) > 1){
                    $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
                }

                if(count($mathieu3) > 1){
                    $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

                }
                else{
                    $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
                }

                $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
                //dd($mathieu_stats);




                $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($francois) > 1){
                    $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

                }
                else{
                    $francois_stat_refuser= $francois[0]->nombre_pret;
                }

                if(count($francois1) > 1){
                    $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

                }
                else{
                    $francois_stat_nontraite= $francois1[0]->nombre_pret;
                }

                if(count($francois2) > 1){
                    $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

                }
                else{
                    $francois_stat_commence= $francois2[0]->nombre_pret;
                }

                if(count($francois3) > 1){
                    $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

                }
                else{
                    $francois_stat_accepte= $francois3[0]->nombre_pret;
                }

                $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
                //dd($francois_stats);



                $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($khalida) > 1){
                    $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

                }
                else{
                    $khalida_stat_refuser= $khalida[0]->nombre_pret;
                }

                if(count($khalida1) > 1){
                    $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

                }
                else{
                    $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
                }

                if(count($khalida2) > 1){
                    $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

                }
                else{
                    $khalida_stat_commence= $khalida2[0]->nombre_pret;
                }

                if(count($khalida3) > 1){
                    $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

                }
                else{
                    $khalida_stat_accepte= $khalida3[0]->nombre_pret;
                }

                $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
                //dd($khalida_stats);



                $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($nancy) > 1){
                    $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

                }
                else{
                    $nancy_stat_refuser= $nancy[0]->nombre_pret;
                }

                if(count($nancy1) > 1){
                    $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

                }
                else{
                    $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
                }

                if(count($nancy2) > 1){
                    $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

                }
                else{
                    $nancy_stat_commence= $nancy2[0]->nombre_pret;
                }

                if(count($nancy3) > 1){
                    $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

                }
                else{
                    $nancy_stat_accepte= $nancy3[0]->nombre_pret;
                }

                $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
                // dd($nancy_stats);



                $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation between '2019-01-01' and '2019-12-31')");
                $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($roseline) > 1){
                    $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

                }
                else{
                    $roseline_stat_refuser= $roseline[0]->nombre_pret;
                }

                if(count($roseline1) > 1){
                    $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

                }
                else{
                    $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
                }

                if(count($roseline2) > 1){
                    $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

                }
                else{
                    $roseline_stat_commence= $roseline2[0]->nombre_pret;
                }

                if(count($roseline3) > 1){
                    $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

                }
                else{
                    $roseline_stat_accepte= $roseline3[0]->nombre_pret;
                }

                $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
                //dd($roseline_stats);


                $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation between '2019-01-01' and '2019-12-31')");
                $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation between '2019-01-01' and '2019-12-31')");
                $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($max) > 1){
                    $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

                }
                else{
                    $max_stat_refuser= $max[0]->nombre_pret;
                }

                if(count($max1) > 1){
                    $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

                }
                else{
                    $max_stat_nontraite= $max1[0]->nombre_pret;
                }

                if(count($max2) > 1){
                    $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

                }
                else{
                    $max_stat_commence= $max2[0]->nombre_pret;
                }

                if(count($max3) > 1){
                    $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

                }
                else{
                    $max_stat_accepte= $max3[0]->nombre_pret;
                }

                $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
                //dd($max_stats);


                //ALICIA STATS
                $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé'  and prets.responsable = 304 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation between '2019-01-01' and '2019-12-31')
");
                $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation between '2019-01-01' and '2019-12-31')
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation between '2019-01-01' and '2019-12-31')");

                //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
                if(count($alicia) > 1){
                    $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

                }
                else{
                    $alicia_stat_refuser= $alicia[0]->nombre_pret;
                }

                if(count($alicia1) > 1){
                    $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

                }
                else{
                    $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
                }

                if(count($alicia2) > 1){
                    $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

                }
                else{
                    $alicia_stat_commence= $alicia2[0]->nombre_pret;
                }

                if(count($alicia3) > 1){
                    $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

                }
                else{
                    $alicia_stat_accepte= $alicia3[0]->nombre_pret;
                }

                $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
                //dd($totalpretabc.$totalkreditpret);

                //FIN MODULE DE STATISTIQUE DES EMPLOYES



                //return view('admin.index');
                return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));

            }


        }








        else {
        //LAST 30 DAYS SEULEMENT DE BASE LORSQUE LON VA SUR ADMIN

        //MONTANT NOUVEAUX PRETS
       $montant_nouveaux_prets = DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE  date_inscription >= date(NOW() - INTERVAL 30 DAY))
");
        $array = json_decode(json_encode($montant_nouveaux_prets), true);

        //NOUVELLES DEMANDES DE PRETS
       $nouvelles_demandes_prets= DB::select("
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription >= date(NOW() - INTERVAL 30 DAY))
");

        $array2 = json_decode(json_encode($nouvelles_demandes_prets), true);

            /*NOUVEAUX RECOUVREMENTS PRETS
            LES PERSONNES AYANT DEMANDER UN PRET dans le passe qui a deja ete accepte et dont il fait un renouvellement dans les 30 derniers jours
          /*$nouveaux_recouvrements_prets= DB::select("
            (select prets.id as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
            pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur where pretabc.membres.id in (select pretabc.prets.id_demandeur from pretabc.prets where prets.statut ='Accepté' group by pretabc.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
            UNION
            (select prets.id  as id_pret, membres.id as membre_id, prets.montant as montant, membres.date_inscription, prets.statut, prets.date_acceptation from
            kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur where kreditpret.membres.id in (select kreditpret.prets.id_demandeur from kreditpret.prets where prets.statut ='Accepté' group by kreditpret.prets.id_demandeur having count(*) > 1 and prets.date_acceptation <= '2020-11-01') and membres.date_inscription >= '2020-12-01' ORDER BY membres.date_inscription ASC)
            ");*/

        //dd($nouveaux_recouvrements_prets);

        //NOUVELLES DEMANDES DE PRETS
        $totalpretabc= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE statut ='Accepté' and date_inscription >= date(NOW() - INTERVAL 30 DAY)
");


        $arraypretabc = json_decode(json_encode($totalpretabc), true);

            if($arraypretabc[0]['montant'] == null){
                $arraypretabc= 0;
                $totalpretabc= $arraypretabc;
            }
            else{
                $totalpretabc= $arraypretabc[0]['montant'];
                //var_dump($arraypretabc[0]['montant']);
            }
        //var_dump($totalpretabc);

        //NOUVELLES DEMANDES DE PRETS
        $totalkreditpret= DB::select("
SELECT COUNT(prets.id) as nombre_pret, SUM(prets.montant) as montant, prets.statut
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE statut ='Accepté' and date_inscription >= date(NOW() - INTERVAL 30 DAY)
");


            //$arraypretabc = json_decode(json_encode($pretabc), true);

            $arraykreditpret = json_decode(json_encode($totalkreditpret), true);

            if($arraykreditpret[0]['montant'] == null){
                $arraykreditpret= 0;
                $totalkreditpret= $arraykreditpret;
            }
            else{
                $totalkreditpret= $arraykreditpret[0]['montant'];
            }

        //var_dump($totalkreditpret);


            //$total= array('pretabc'=> $totalpretabc, 'kreditpret' => $totalkreditpret);
            $total['pretabc'] = $totalpretabc;
            $total['kreditpret'] = $totalkreditpret;

            //dd($total);

            //STATISTIQUES DES EMPLOYES JANVIER 2021

            //ALEX STATISTIQUES
            $alex= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 63  and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4394 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $alex1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 63 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4394 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $alex2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 63 and prets.date_creation  >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4394  and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $alex3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 63 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4394 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($alex) > 1){
                $alex_stat_refuser = $alex[0]->nombre_pret + $alex[1]->nombre_pret;
                //echo 'as pretabc and kreditpret';
            }
            else{
                $alex_stat_refuser= $alex[0]->nombre_pret;
            }

            if(count($alex1) > 1){
                $alex_stat_nontraite = $alex1[0]->nombre_pret + $alex1[1]->nombre_pret;
                //echo 'as pretabc and kreditpret';
            }
            else{
                $alex_stat_nontraite= $alex1[0]->nombre_pret;
            }

            if(count($alex2) > 1){
                $alex_stat_commence = $alex2[0]->nombre_pret + $alex2[1]->nombre_pret;
                //echo 'as pretabc and kreditpret';
            }
            else{
                $alex_stat_commence= $alex2[0]->nombre_pret;
            }

            if(count($alex3) > 1){
                $alex_stat_accepte = $alex3[0]->nombre_pret + $alex3[1]->nombre_pret;
                //echo 'as pretabc and kreditpret';
            }
            else{
                $alex_stat_accepte= $alex3[0]->nombre_pret;
            }

            $alex_stats= array('refuser'=> $alex_stat_refuser, 'non-traité' => $alex_stat_nontraite, 'commencé' => $alex_stat_commence, 'accepté'=> $alex_stat_accepte);
            //var_dump($alex_stats);


            //FELIXE STATISTIQUES JANVIER 2021
            //felixe

            $felixe= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3305 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4456 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY)) 
");
            $felixe1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3305 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4456 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $felixe2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3305 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4456 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $felixe3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3305 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4456 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($felixe) > 1){
                $felixe_stat_refuser = $felixe[0]->nombre_pret + $felixe[1]->nombre_pret;

            }
            else{
                $felixe_stat_refuser= $felixe[0]->nombre_pret;
            }

            if(count($felixe1) > 1){
                $felixe_stat_nontraite = $felixe1[0]->nombre_pret + $felixe1[1]->nombre_pret;

            }
            else{
                $felixe_stat_nontraite= $felixe1[0]->nombre_pret;
            }

            if(count($felixe2) > 1){
                $felixe_stat_commence = $felixe2[0]->nombre_pret + $felixe2[1]->nombre_pret;

            }
            else{
                $felixe_stat_commence= $felixe2[0]->nombre_pret;
            }

            if(count($felixe3) > 1){
                $felixe_stat_accepte = $felixe3[0]->nombre_pret + $felixe3[1]->nombre_pret;

            }
            else{
                $felixe_stat_accepte= $felixe3[0]->nombre_pret;
            }

            $felixe_stats= array('refuser'=> $felixe_stat_refuser, 'non-traité' => $felixe_stat_nontraite, 'commencé' => $felixe_stat_commence, 'accepté'=> $felixe_stat_accepte);
            //dd($felixe_stats);


            //ROXANNE STATISTIQUES JANVIER 2021
            $roxanne= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5239 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4535 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
 ");
            $roxanne1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5239 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4535 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $roxanne2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5239 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4535 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $roxanne3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5239 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4535 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($roxanne) > 1){
                $roxanne_stat_refuser = $roxanne[0]->nombre_pret + $roxanne[1]->nombre_pret;

            }
            else{
                $roxanne_stat_refuser= $roxanne[0]->nombre_pret;
            }

            if(count($roxanne1) > 1){
                $roxanne_stat_nontraite = $roxanne1[0]->nombre_pret + $roxanne1[1]->nombre_pret;

            }
            else{
                $roxanne_stat_nontraite= $roxanne1[0]->nombre_pret;
            }

            if(count($roxanne2) > 1){
                $roxanne_stat_commence = $roxanne2[0]->nombre_pret + $roxanne2[1]->nombre_pret;

            }
            else{
                $roxanne_stat_commence= $roxanne2[0]->nombre_pret;
            }

            if(count($roxanne3) > 1){
                $roxanne_stat_accepte = $roxanne3[0]->nombre_pret + $roxanne3[1]->nombre_pret;

            }
            else{
                $roxanne_stat_accepte= $roxanne3[0]->nombre_pret;
            }

            $roxanne_stats= array('refuser'=> $roxanne_stat_refuser, 'non-traité' => $roxanne_stat_nontraite, 'commencé' => $roxanne_stat_commence, 'accepté'=> $roxanne_stat_accepte);
            //dd($roxanne_stats);


            $marc_antoine= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9063 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5131 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $marc_antoine1= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9063 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5131 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $marc_antoine2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9063 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5131 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $marc_antoine3= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9063 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5131 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($marc_antoine) > 1){
                $marc_antoine_stat_refuser = $marc_antoine[0]->nombre_pret + $marc_antoine[1]->nombre_pret;

            }
            else{
                $marc_antoine_stat_refuser= $marc_antoine[0]->nombre_pret;
            }

            if(count($marc_antoine1) > 1){
                $marc_antoine_stat_nontraite = $marc_antoine1[0]->nombre_pret + $marc_antoine1[1]->nombre_pret;

            }
            else{
                $marc_antoine_stat_nontraite= $marc_antoine1[0]->nombre_pret;
            }

            if(count($marc_antoine2) > 1){
                $marc_antoine_stat_commence = $marc_antoine2[0]->nombre_pret + $marc_antoine2[1]->nombre_pret;

            }
            else{
                $marc_antoine_stat_commence= $marc_antoine2[0]->nombre_pret;
            }

            if(count($marc_antoine3) > 1){
                $marc_antoine_stat_accepte = $marc_antoine3[0]->nombre_pret + $marc_antoine3[1]->nombre_pret;

            }
            else{
                $marc_antoine_stat_accepte= $marc_antoine3[0]->nombre_pret;
            }

            $marc_antoine_stats= array('refuser'=> $marc_antoine_stat_refuser, 'non-traité' => $marc_antoine_stat_nontraite, 'commencé' => $marc_antoine_stat_commence, 'accepté'=> $marc_antoine_stat_accepte);
            //dd($marc_antoine_stats);



            $emily= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9399 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5230 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $emily1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9399 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5230 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $emily2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9399 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5230 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $emily3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9399 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5230  and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($emily) > 1){
                $emily_stat_refuser = $emily[0]->nombre_pret + $emily[1]->nombre_pret;

            }
            else{
                $emily_stat_refuser= $emily[0]->nombre_pret;
            }

            if(count($emily1) > 1){
                $emily_stat_nontraite = $emily1[0]->nombre_pret + $emily1[1]->nombre_pret;

            }
            else{
                $emily_stat_nontraite= $emily1[0]->nombre_pret;
            }

            if(count($emily2) > 1){
                $emily_stat_commence = $emily2[0]->nombre_pret + $emily2[1]->nombre_pret;

            }
            else{
                $emily_stat_commence= $emily2[0]->nombre_pret;
            }

            if(count($emily3) > 1){
                $emily_stat_accepte = $emily3[0]->nombre_pret + $emily3[1]->nombre_pret;

            }
            else{
                $emily_stat_accepte= $emily3[0]->nombre_pret;
            }

            $emily_stats= array('refuser'=> $emily_stat_refuser, 'non-traité' => $emily_stat_nontraite, 'commencé' => $emily_stat_commence, 'accepté'=> $emily_stat_accepte);
            //dd($emily_stats);




            $math_l= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9062 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5130 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");



            $math_l1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9062 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5130 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");

            $math_l2= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9062 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5130 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");

            $math_l3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9062 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5130 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");


            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($math_l) > 1){
                $math_l_stat_refuser = $math_l[0]->nombre_pret + $math_l[1]->nombre_pret;

            }
            else{
                $math_l_stat_refuser= $math_l[0]->nombre_pret;
            }

            if(count($math_l1) > 1){
                $math_l_stat_nontraite = $math_l1[0]->nombre_pret + $math_l1[1]->nombre_pret;

            }
            else{
                $math_l_stat_nontraite= $math_l1[0]->nombre_pret;
            }

            if(count($math_l2) > 1){
                $math_l_stat_commence = $math_l2[0]->nombre_pret + $math_l2[1]->nombre_pret;

            }
            else{
                $math_l_stat_commence= $math_l2[0]->nombre_pret;
            }

            if(count($math_l3) > 1){
                $math_l_stat_accepte = $math_l3[0]->nombre_pret + $math_l3[1]->nombre_pret;

            }
            else{
                $math_l_stat_accepte= $math_l3[0]->nombre_pret;
            }

            $math_l_stats= array('refuser'=> $math_l_stat_refuser, 'non-traité' => $math_l_stat_nontraite, 'commencé' => $math_l_stat_commence, 'accepté'=> $math_l_stat_accepte);
            //dd($math_l_stats);


            $patrick= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6774 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4403 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $patrick1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6774 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4403 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $patrick2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6774 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4403 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $patrick3=DB:: select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6774 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4403 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($patrick) > 1){
                $patrick_stat_refuser = $patrick[0]->nombre_pret + $patrick[1]->nombre_pret;

            }
            else{
                $patrick_stat_refuser= $patrick[0]->nombre_pret;
            }

            if(count($patrick1) > 1){
                $patrick_stat_nontraite = $patrick1[0]->nombre_pret + $patrick1[1]->nombre_pret;

            }
            else{
                $patrick_stat_nontraite= $patrick1[0]->nombre_pret;
            }

            if(count($patrick2) > 1){
                $patrick_stat_commence = $patrick2[0]->nombre_pret + $patrick2[1]->nombre_pret;

            }
            else{
                $patrick_stat_commence= $patrick2[0]->nombre_pret;
            }

            if(count($patrick3) > 1){
                $patrick_stat_accepte = $patrick3[0]->nombre_pret + $patrick3[1]->nombre_pret;

            }
            else{
                $patrick_stat_accepte= $patrick3[0]->nombre_pret;
            }

            $patrick_stats= array('refuser'=> $patrick_stat_refuser, 'non-traité' => $patrick_stat_nontraite, 'commencé' => $patrick_stat_commence, 'accepté'=> $patrick_stat_accepte);
            //dd($patrick_stats);



            $emilie= DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 6134 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4711 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $emilie1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 6134 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4711 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $emilie2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 6134 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut,prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4711 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $emilie3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 6134 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4711 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($emilie) > 1){
                $emilie_stat_refuser = $emilie[0]->nombre_pret + $emilie[1]->nombre_pret;

            }
            else{
                $emilie_stat_refuser= $emilie[0]->nombre_pret;
            }

            if(count($emilie1) > 1){
                $emilie_stat_nontraite = $emilie1[0]->nombre_pret + $emilie1[1]->nombre_pret;

            }
            else{
                $emilie_stat_nontraite= $emilie1[0]->nombre_pret;
            }

            if(count($emilie2) > 1){
                $emilie_stat_commence = $emilie2[0]->nombre_pret + $emilie2[1]->nombre_pret;

            }
            else{
                $emilie_stat_commence= $emilie2[0]->nombre_pret;
            }

            if(count($emilie3) > 1){
                $emilie_stat_accepte = $emilie3[0]->nombre_pret + $emilie3[1]->nombre_pret;

            }
            else{
                $emilie_stat_accepte= $emilie3[0]->nombre_pret;
            }

            $emilie_stats= array('refuser'=> $emilie_stat_refuser, 'non-traité' => $emilie_stat_nontraite, 'commencé' => $emilie_stat_commence, 'accepté'=> $emilie_stat_accepte);
            //dd($emilie_stats);



            $mathieu=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 5636 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4457 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $mathieu1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 5636 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4457 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $mathieu2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 5636 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4457 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $mathieu3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 5636 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4457 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($mathieu) > 1){
                $mathieu_stat_refuser = $mathieu[0]->nombre_pret + $mathieu[1]->nombre_pret;

            }
            else{
                $mathieu_stat_refuser= $mathieu[0]->nombre_pret;
            }

            if(count($mathieu1) > 1){
                $mathieu_stat_nontraite = $mathieu1[0]->nombre_pret + $mathieu1[1]->nombre_pret;

            }
            else{
                $mathieu_stat_nontraite= $mathieu1[0]->nombre_pret;
            }

            if(count($mathieu2) > 1){
                $mathieu_stat_commence = $mathieu2[0]->nombre_pret + $mathieu2[1]->nombre_pret;

            }
            else{
                $mathieu_stat_commence= $mathieu2[0]->nombre_pret;
            }

            if(count($mathieu3) > 1){
                $mathieu_stat_accepte = $mathieu3[0]->nombre_pret + $mathieu3[1]->nombre_pret;

            }
            else{
                $mathieu_stat_accepte= $mathieu3[0]->nombre_pret;
            }

            $mathieu_stats= array('refuser'=> $mathieu_stat_refuser, 'non-traité' => $mathieu_stat_nontraite, 'commencé' => $mathieu_stat_commence, 'accepté'=> $mathieu_stat_accepte);
            //dd($mathieu_stats);




            $francois=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3310 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4804 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $francois1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3310 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4804 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $francois2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3310 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4804 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $francois3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3310 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4804 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($francois) > 1){
                $francois_stat_refuser = $francois[0]->nombre_pret + $francois[1]->nombre_pret;

            }
            else{
                $francois_stat_refuser= $francois[0]->nombre_pret;
            }

            if(count($francois1) > 1){
                $francois_stat_nontraite = $francois1[0]->nombre_pret + $francois1[1]->nombre_pret;

            }
            else{
                $francois_stat_nontraite= $francois1[0]->nombre_pret;
            }

            if(count($francois2) > 1){
                $francois_stat_commence = $francois2[0]->nombre_pret + $francois2[1]->nombre_pret;

            }
            else{
                $francois_stat_commence= $francois2[0]->nombre_pret;
            }

            if(count($francois3) > 1){
                $francois_stat_accepte = $francois3[0]->nombre_pret + $francois3[1]->nombre_pret;

            }
            else{
                $francois_stat_accepte= $francois3[0]->nombre_pret;
            }

            $francois_stats= array('refuser'=> $francois_stat_refuser, 'non-traité' => $francois_stat_nontraite, 'commencé' => $francois_stat_commence, 'accepté'=> $francois_stat_accepte);
            //dd($francois_stats);



            $khalida=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1617 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4626 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $khalida1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1617 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4626 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $khalida2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1617 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4626 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $khalida3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1617 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4626 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($khalida) > 1){
                $khalida_stat_refuser = $khalida[0]->nombre_pret + $khalida[1]->nombre_pret;

            }
            else{
                $khalida_stat_refuser= $khalida[0]->nombre_pret;
            }

            if(count($khalida1) > 1){
                $khalida_stat_nontraite = $khalida1[0]->nombre_pret + $khalida1[1]->nombre_pret;

            }
            else{
                $khalida_stat_nontraite= $khalida1[0]->nombre_pret;
            }

            if(count($khalida2) > 1){
                $khalida_stat_commence = $khalida2[0]->nombre_pret + $khalida2[1]->nombre_pret;

            }
            else{
                $khalida_stat_commence= $khalida2[0]->nombre_pret;
            }

            if(count($khalida3) > 1){
                $khalida_stat_accepte = $khalida3[0]->nombre_pret + $khalida3[1]->nombre_pret;

            }
            else{
                $khalida_stat_accepte= $khalida3[0]->nombre_pret;
            }

            $khalida_stats= array('refuser'=> $khalida_stat_refuser, 'non-traité' => $khalida_stat_nontraite, 'commencé' => $khalida_stat_commence, 'accepté'=> $khalida_stat_accepte);
            //dd($khalida_stats);



            $nancy=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 3306 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4388 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $nancy1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 3306 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4388 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $nancy2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 3306 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4388 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $nancy3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 3306 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4388 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($nancy) > 1){
                $nancy_stat_refuser = $nancy[0]->nombre_pret + $nancy[1]->nombre_pret;

            }
            else{
                $nancy_stat_refuser= $nancy[0]->nombre_pret;
            }

            if(count($nancy1) > 1){
                $nancy_stat_nontraite = $nancy1[0]->nombre_pret + $nancy1[1]->nombre_pret;

            }
            else{
                $nancy_stat_nontraite= $nancy1[0]->nombre_pret;
            }

            if(count($nancy2) > 1){
                $nancy_stat_commence = $nancy2[0]->nombre_pret + $nancy2[1]->nombre_pret;

            }
            else{
                $nancy_stat_commence= $nancy2[0]->nombre_pret;
            }

            if(count($nancy3) > 1){
                $nancy_stat_accepte = $nancy3[0]->nombre_pret + $nancy3[1]->nombre_pret;

            }
            else{
                $nancy_stat_accepte= $nancy3[0]->nombre_pret;
            }

            $nancy_stats= array('refuser'=> $nancy_stat_refuser, 'non-traité' => $nancy_stat_nontraite, 'commencé' => $nancy_stat_commence, 'accepté'=> $nancy_stat_accepte);
           // dd($nancy_stats);



            $roseline=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 9398  and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 5229 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");
            $roseline1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 9398  and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 5229 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $roseline2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 9398 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 5229 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $roseline3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 9398 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 5229 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($roseline) > 1){
                $roseline_stat_refuser = $roseline[0]->nombre_pret + $roseline[1]->nombre_pret;

            }
            else{
                $roseline_stat_refuser= $roseline[0]->nombre_pret;
            }

            if(count($roseline1) > 1){
                $roseline_stat_nontraite = $roseline1[0]->nombre_pret + $roseline1[1]->nombre_pret;

            }
            else{
                $roseline_stat_nontraite= $roseline1[0]->nombre_pret;
            }

            if(count($roseline2) > 1){
                $roseline_stat_commence = $roseline2[0]->nombre_pret + $roseline2[1]->nombre_pret;

            }
            else{
                $roseline_stat_commence= $roseline2[0]->nombre_pret;
            }

            if(count($roseline3) > 1){
                $roseline_stat_accepte = $roseline3[0]->nombre_pret + $roseline3[1]->nombre_pret;

            }
            else{
                $roseline_stat_accepte= $roseline3[0]->nombre_pret;
            }

            $roseline_stats= array('refuser'=> $roseline_stat_refuser, 'non-traité' => $roseline_stat_nontraite, 'commencé' => $roseline_stat_commence, 'accepté'=> $roseline_stat_accepte);
            //dd($roseline_stats);


            $max=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 1 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé' and prets.responsable = 4389 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");
            $max1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 1 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4389 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");
            $max2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 1 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4389 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $max3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 1 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4389 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($max) > 1){
                $max_stat_refuser = $max[0]->nombre_pret + $max[1]->nombre_pret;

            }
            else{
                $max_stat_refuser= $max[0]->nombre_pret;
            }

            if(count($max1) > 1){
                $max_stat_nontraite = $max1[0]->nombre_pret + $max1[1]->nombre_pret;

            }
            else{
                $max_stat_nontraite= $max1[0]->nombre_pret;
            }

            if(count($max2) > 1){
                $max_stat_commence = $max2[0]->nombre_pret + $max2[1]->nombre_pret;

            }
            else{
                $max_stat_commence= $max2[0]->nombre_pret;
            }

            if(count($max3) > 1){
                $max_stat_accepte = $max3[0]->nombre_pret + $max3[1]->nombre_pret;

            }
            else{
                $max_stat_accepte= $max3[0]->nombre_pret;
            }

            $maxime_stats= array('refuser'=> $max_stat_refuser, 'non-traité' => $max_stat_nontraite, 'commencé' => $max_stat_commence, 'accepté'=> $max_stat_accepte);
            //dd($max_stats);


            //ALICIA STATS
            $alicia=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Refusé' and prets.responsable = 304 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Refusé'  and prets.responsable = 4633 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $alicia1=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Non-Traité' and prets.responsable = 304 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Non-Traité' and prets.responsable = 4633 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
");
            $alicia2=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Commencé' and prets.responsable = 304 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Commencé' and prets.responsable = 4633 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            $alicia3=DB::select("(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM pretabc.prets WHERE prets.statut ='Accepté' and prets.responsable = 304 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))
UNION
(SELECT COUNT(prets.id) as nombre_pret, prets.statut, prets.date_creation
FROM kreditpret.prets  WHERE prets.statut ='Accepté' and prets.responsable = 4633 and prets.date_creation >= date(NOW() - INTERVAL 30 DAY))");

            //check if the sql as the data; if yes we have data from pretabc and kreditpret; else only one compagny
            if(count($alicia) > 1){
                $alicia_stat_refuser = $alicia[0]->nombre_pret + $alicia[1]->nombre_pret;

            }
            else{
                $alicia_stat_refuser= $alicia[0]->nombre_pret;
            }

            if(count($alicia1) > 1){
                $alicia_stat_nontraite = $alicia1[0]->nombre_pret + $alicia1[1]->nombre_pret;

            }
            else{
                $alicia_stat_nontraite= $alicia1[0]->nombre_pret;
            }

            if(count($alicia2) > 1){
                $alicia_stat_commence = $alicia2[0]->nombre_pret + $alicia2[1]->nombre_pret;

            }
            else{
                $alicia_stat_commence= $alicia2[0]->nombre_pret;
            }

            if(count($alicia3) > 1){
                $alicia_stat_accepte = $alicia3[0]->nombre_pret + $alicia3[1]->nombre_pret;

            }
            else{
                $alicia_stat_accepte= $alicia3[0]->nombre_pret;
            }

            $alicia_stats= array('refuser'=> $alicia_stat_refuser, 'non-traité' => $alicia_stat_nontraite, 'commencé' => $alicia_stat_commence, 'accepté'=> $alicia_stat_accepte);
            //dd($alicia_stats);

            //FIN MODULE DE STATISTIQUE DES EMPLOYES


            //MODULE LISTE DES DEMANDES DE PRET DE LA DERNIERE HEURE (à continuer)
            $pret_derniere_heure=DB::select("(SELECT
membres.id, membres.nom, membres.prenom, membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,membres.flinksLoginId,
prets.id, prets.id_demandeur, prets.montant, prets.date_creation, 
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() ) )
UNION
(SELECT membres.id, membres.nom, membres.prenom, membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,membres.flinksLoginId,
prets.id, prets.id_demandeur, prets.montant, prets.date_creation, 
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() ) )
");
            //dd($pret_derniere_heure);

////////////////////////////////////////////////////FIN PRET DERNIERE HEURE///////////////////////////////////////////////////////////////////



            //return view('admin.index');
            return view('admin.index',compact('montant_nouveaux_prets', 'nouvelles_demandes_prets', 'nouveaux_recouvrements_prets', 'totalkreditpret','totalpretabc', 'alex_stats','felixe_stats', 'roxanne_stats','marc_antoine_stats','emily_stats','math_l_stats','patrick_stats','emilie_stats','mathieu_stats','francois_stats','khalida_stats','nancy_stats','roseline_stats','maxime_stats','alicia_stats'));


        }
        //return view('admin.statistiques');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input= $request->all();
        dd($input);
        $prets = DB::select('SELECT
membres.id, membres.nom, membres.prenom, 
prets.id, prets.id_demandeur, prets.montant, prets.date_creation, 
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat 
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur');
        $tasks = Task::create(['employe_id' =>$input]);

        //dd($taks);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //check all the request
        $input= $request->all();

        //check the current loan that the admin is setting for employe
        $prets= DB::table('prets')->where('id', $id)->first();

        //put the current loan id into variable so the task can have a copy of it later
        $pret_id= $prets->id;

        //put the current employe id into variable so the task can have the name of the employe
        $employe_id= $request->input('employe_id');

        //set the name of the owner (responsable)
        $prets_membres= DB::table('prets')->where('id', $id)->update(
            [
                'responsable' => $request->input('responsable')
            ]);

        //pull out the users model to get the name of the employe
        $membres=DB::table('users')->where('id', $employe_id)->first();

        $tasks = Task::create([
            'pret_id' =>$pret_id,
            'employe_id' =>$request->input('employe_id'),
            'name' =>$membres->name
            ]);



        return redirect('task');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function Pdfdownload(Request $request){
        //return 'allo';
        $id = $_GET['id'];

        $InfoPrets = DB::select("SELECT
*
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE prets.id ='$id'");
        //dd($InfoPrets);
        foreach($InfoPrets as $usr)
            // instantiate and use the dompdf class
            $dompdf = new Dompdf();
        $dompdf->loadHtml(" <h1>Informations sur le prêt client</h1>
            <h4>Informations administratives</h4>
                <p>Responsable : ".$usr->responsable."</p>
                <p>Message privé de l'administration : ".$usr->message_admin_private."</p>

            <h4>Informations meta du prêt </h4>
            <p>No de dossier : ".$usr->id."</p>
            <p>État : ".$usr->statut."</p>
            <p>Statut: ".$usr->statutnew."</p>
            <p>Prêt demandé le : ".$usr->date_inscription."</p>
            <p>Prêt accepté le : ".$usr->date_acceptation."</p>

            <h4>Informations générales du prêt</h4>
            <p>Montant demandé : ".$usr->montant."</p>
            <p>Fréquence des paiements: ".$usr->frequence_remboursement."</p>
            <p>Message du client : ".$usr->message."</p>

            <h4>Informations uniques du client en rapport au prêt</h4>
            <p>Prédiction de faillite : ".$usr->faillite."</p>
            <p>Date de prochaine paie : ".$usr->date_prochaine_paie."</p>

            <h4>Informations du prêt</h4>
            <p>Devise : ".$usr->devise."</p>
            <p>No de dossier du software: ".$usr->no_dossier_unique."</p>
            <p>Intérêts : ".$usr->interets.".</p>
            <p>Frais compagnie de courtage: ".$usr->frais_compagnie_courtage."</p>
            <p>Frais de crédit : ".$usr->total_frais_credit_pour_duree_pret."</p>
            <p>Obligation totale du consommateur : ".$usr->obligation_total_du_consommateur."</p>
            <p>Nombre de versements : ".$usr->nombre_versements."</p>
            <p>Fréquence des paiements: ".$usr->frequence_paiements."</p>
            <p>Montant de chacun des versements: ".$usr->montant_versements_consecutifs."</p>
            <p>Date du premier versement : ".$usr->date_premier_versement."</p>
            <p>Date du dernier versement : ".$usr->date_dernier_versement."</p>
            <p>No d'institution : ".$usr->compte_bancaire_no_compte."</p>
            <p>No de transit : ".$usr->compte_bancaire_no_compte."</p>
            <p>No de compte: ".$usr->compte_bancaire_no_compte."</p>
            <p>Date d'importation du contrat: ".$usr->date_importation_contrat."</p>

            <h4>Informations générales du client</h4>
            <p>Date d'inscription : ".$usr->date_inscription."</p>
            <p>Prénom: ".$usr->prenom."</p>
            <p>Nom : ".$usr->nom."</p>
            <p>Courriel: ".$usr->courriel."</p>
            <p>Sexe :</p>
            <p>Nas:</p>
            <p>Date de naissance : ".$usr->date_naissance."</p>
            <p>Téléphone: ".$usr->telephone."</p>
            <p>Compagnie de téléphone: ".$usr->telephone."</p>
            <p>Adresse : ".$usr->adresse."</p>
            <p>Appartement : ".$usr->appartement."</p>
            <p>Ville : ".$usr->ville."</p>
            <p>Code postal : ".$usr->code_postal."</p>
            <p>Province: ".$usr->province."</p>
            <p>Source de revenus : ".$usr->source_revenus."</p>
            <p>Banque : ".$usr->banque."</p>

            <h4>Informations générales du client</h4>
            <p>Nom de l'employeur : ".$usr->nom_employeur."</p>
            <p>Téléphone de l'employeur : ".$usr->telephone_employeur."</p>
            <p>Personne ressource : ".$usr->personne_ressource."</p>
            <p>Date d'embauche : ".$usr->date_embauche."</p>

            <h4>Informations sur la personne ressource du client</h4>
            <p>Type de référence : ".$usr->type_reference."</p>
            <p>Nom de la personne référence : ".$usr->nom_reference."</p>
            <p>Téléphone de la personne référence : ".$usr->telephone_reference."</p>
            <p>Relation avec la personne référence : ".$usr->relation_reference."</p>


            <h4>Initiatives prises par le client en rapport au prêt</h4>
            <p>Autorisation prêt d'argent : ".$usr->autorisation_pret_argent."</p>
            <p>Autorisation donné le : ".$usr->autorisation_pret_argent_date."</p>
            <p>Autorisation frais de courtage : ".$usr->autorisation_frais_courtage."</p>
            <p>Autorisation donné le: ".$usr->autorisation_frais_courtage_date."</p>
            <p>Autorisation entente de débit : ".$usr->autorisation_entente_debit."</p>
            <p>Autorisation donné le: ".$usr->autorisation_entente_debit_date."</p> ");

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();


    }

    public function jsonDownload(Request $request){
        $input= $request->all();
        //return 'Hello World';
        //dd($input);
        $id = $_GET['id'];

        $InfoPrets = DB::select("SELECT
* FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE prets.id ='$id'");
        //dd($InfoPrets);


        $users = DB::table('membres')
            ->join('prets', 'membres.id', '=', 'prets.id_demandeur')
            ->where('prets.id', '=', $id)
            ->get();

        $users->toArray();
        foreach($users as $usr)
            //echo $usr->id;

            $jsonOBJ = (object) [
                'Version' => "1.1",
                'LoanRequests' => array()
            ];
        //cho strtotime($usr->date_inscription);


        $obj = (object)[
            "Loan" => [
                "Date" =>  $usr->date_inscription,
                "Amount" => $usr->montant,
                "Comment"  => $usr->message,
                "Unique id" => $usr->id,
                "Borrower" =>[
                    "First_Name" => $usr->prenom,
                    "Last_Name" => $usr->nom,
                    "Business" => "",
                    "Social_sec" => $usr->nas,
                    "PhoneNo" => $usr->telephone,
                    "MobileNo" => $usr->telephone,
                    "Email" => $usr->courriel,
                    "Website" => "",
                    "Birth_date" => $usr->date_naissance,
                    "Marital_Status" => "",
                    "ApplicantType" => 1,

                    "Address1" => $usr->adresse." App(".$usr->appartement.")",
                    "Address2" => "",
                    "State_Prov" => $usr->province,
                    "City" => $usr->ville,
                    "Fax" => "",
                    "Country" => "Canada",
                    "ZipPostal" => $usr->code_postal,
                    "Occupation" => ""
                ],
                "Employer" =>[
                    "Occupation" => "",
                    "Business" => "",
                    "PhoneNo" => "",
                    "Address1" => "",
                    "Address2" => "",
                    "State_Prov" => "",
                    "City" => "",
                    "ZipPostal" => "",

                ],
                "LoanCustomFields" =>[
                    [
                        "Name" => "id_pret",
                        "Value" => $usr->id

                    ],

                ],
            ]
        ];
        //dd($obj);
        array_push($jsonOBJ->LoanRequests, $obj);
        //echo asset('storage/results.json');
        $fp = fopen('storage/results.json', 'w');
        fwrite($fp, json_encode($jsonOBJ, JSON_UNESCAPED_UNICODE));
        fclose($fp);
        $file_url = 'storage/results.json';
        header('Content-Type: application/json;charset=utf-8');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url);

    }
}
