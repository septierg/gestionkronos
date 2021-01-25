<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KreditpretPretController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        if ($request->has('responsable') and
            $request->has('statut')  and
            $request->has('durée'))
        {


            //search membre
            if($request->input('search_Membre') == "")
                $search_Membre = "";
            else {
                $search_Membre = $request->input('search_Membre');}

            //date debut
            if($request->input('date_debut') == "")
                $date_debut = "";
            else {
                $date_debut = $request->input('date_debut');}


            //date fin
            if($request->input('date_fin') == "")
                $date_fin = "";
            else {
                $date_fin= $request->input('date_fin');}


            //email
            if($request->input('search_Email') == "")
                $search_Email = "";
            else {
                $search_Email = $request->input('search_Email');}


            //statut
            if($request->input('statut') == "")
                $statut = "";
            else {
                $statut = $request->input('statut');}


            //responsable
            if($request->input('responsable') == "")
                $responsable = "";
            else {
                $responsable = $request->input('responsable');}

            $date_debut = $request->input('date_debut');
            $date_fin= $request->input('date_fin');
            $search_Email = $request->input('search_Email');
            $par_durée = $request->input('durée');

            if($par_durée == "Dernier mois") {
                $par_durée= 30;
            }
            if($par_durée == "Aujourd'hui") {
                $par_durée= 0;
            }
            if($par_durée == "Derniere semaine") {
                $par_durée= 7;
            }
            if($par_durée == "2 dernieres semaines") {
                $par_durée= 14;
            }

            if($par_durée == "3 dernieres semaines") {
                $par_durée= 14;
            }
            if($par_durée == "3 derniers mois") {
                $par_durée= 90;
            }
            if($par_durée == "6 derniers mois") {
                $par_durée= 180;
            }
            if($par_durée == "Année en cours") {
                $par_durée= 365;
            }
            $statut = $request->input('statut');
            $responsable = $request->input('responsable');

            $prets =  DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE (date_inscription >= date(NOW() - INTERVAL $par_durée DAY) and statut = '$statut' and responsable='$responsable') ");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($loansRappelCount);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));



        }

        //filtre date debut et fin plus responsable et statut
        if ($request->has('responsable') and
            $request->has('statut')  and
            $request->has('date_debut') and $request->has('date_fin'))
        {

            //date debut
            if($request->input('date_debut') == "")
                $date_debut = "";
            else {
                $date_debut = $request->input('date_debut');}

            //date fin
            if($request->input('date_fin') == "")
                $date_fin = "";
            else {
                $date_fin= $request->input('date_fin');}

            //statut
            if($request->input('statut') == "")
                $statut = "";
            else {
                $statut = $request->input('statut');}

            //responsable
            if($request->input('responsable') == "")
                $responsable = "";
            else {
                $responsable = $request->input('responsable');}

            $date_debut = $request->input('date_debut');
            $date_fin= $request->input('date_fin');
            $search_Email = $request->input('search_Email');

            $statut = $request->input('statut');
            $responsable = $request->input('responsable');

            $prets =  DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE (date_inscription  between '$date_debut' and  '$date_fin'  and statut = '$statut' and responsable='$responsable') ");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($loansRappelCount);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));



        }

        if ($request->has('statut') and $request->has('date_debut') and $request->has('date_fin')) {
            $statut = $request->input('statut');
            $date_debut = $request->input('date_debut');
            $date_fin= $request->input('date_fin');
            //dd('GOT 3');
            $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE (date_inscription >= '$date_debut' and date_inscription <= '$date_fin' and statut = '$statut') ");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));

        }
        if ($request->has('date_debut') and $request->has('date_fin')) {
            $date_debut = $request->input('date_debut');
            $date_fin= $request->input('date_fin');
            //dd('GOT 2');
            $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE (date_inscription >= '$date_debut' and date_inscription <= '$date_fin') ");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));

        }
        if ($request->has('statut')) {
            $statut = $request->input('statut');

            //dd('GOT 1');
            $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE (statut = '$statut') ");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));

        }
        if ($request->has('durée')) {
            $durée = $request->input('durée');
            if($durée == "Dernier mois")
            {$prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 31 DAY)");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));
            }

            if($durée == "Aujourd'hui")
            {
                //dd($date);
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW()) ;");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));

            }
            if($durée == "Derniere semaine") {
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 7 DAY) ;");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));
            }
            if($durée == "2 dernieres semaines"){
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 15 DAY) ;");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));

            }
            if($durée == "3 dernieres semaines"){
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 21 DAY) ;");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));
            }
            if($durée == "3 derniers mois"){
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 90 DAY) ;");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));
            }
            if($durée == "6 derniers mois")
            {
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 180 DAY) ;");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));
                //return view('kreditpret.index',compact('prets'));
            }
            if($durée == "Année en cours")
            {
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= '2020-01-01' ;");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));
            }
            if($durée == "Année précédente")
            {
                $prets =DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= '2019-01-01' and date_inscription <='2020-01-01';");

                $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
                $loansRappelCount = count($rappel);
                //dd($prets);
                return view('kreditpret.index',compact('prets','loansRappelCount'));
            }
            if($durée == "Voir tout")
                $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= '2019-01-01' ;");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));

        }

        if ($request->has('search_Membre')) {
            $search_Membre = $request->input('search_Membre');
            //dd($search_Membre);
            $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE membres.nom = '$search_Membre'");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));
        }

        if ($request->has('search_Email')) {
            $search_Email = $request->input('search_Email');
            $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE membres.courriel = '$search_Email'");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));
        }
        if ($request->has('responsable')) {
            $responsable = $request->input('responsable');
            $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE prets.responsable = '$responsable'");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
            //dd($prets);
            return view('kreditpret.index',compact('prets','loansRappelCount'));
            //return view('kreditpret.index',compact('prets'));
        }
        else{
        $prets = DB::connection('mysql2')->select("SELECT
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
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE date_inscription >= date(NOW() - INTERVAL 30 DAY) AND statut ='Non-traité'");

            $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
                GROUP BY 
                    membres.id");
            $loansRappelCount = count($rappel);
        //dd($prets);
        return view('kreditpret.index',compact('prets','loansRappelCount'));
    }

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
        //dd($input);
        $id_user= $request->input('id');

        $prets= DB::table('kreditpret.prets')->where('id', $id_user)->update(
            [
                'message_admin_private' => $request->input('body')
            ]);

        return redirect('kreditpret');
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
        $prets= DB::table('kreditpret.prets')->where('id', $id)->first();

        return view('kreditpret.prets.show',compact('prets'));
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

        $prets= DB::table('kreditpret.prets')->where('id', $id)->first();

        return view('kreditpret.prets.edit',compact('prets'));
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
        //
        $input= $request->all();
        //dd($input);
        $prets= DB::table('kreditpret.prets')->where('id', $id)->update(
            [
                'statut' => $request->input('statut'),
                'montrer_contrat' => $request->input('montrer_contrat'),
                'responsable' => $request->input('responsable'),
                'statutnew' => $request->input('statutnew'),
                'message_admin_private' => $request->input('message_admin_private'),
                'montant' => $request->input('montant')
            ]);

        if($request->input('statut') == "Accepté") {


            $user = User::find(1)->toArray();
            Mail::send('emails.mail', $user, function($message) use ($user) {
                $message->from('emmanuel.septier@hotmail.com');
                $message->to('bboybourrik972@hotmail.com');
                $message->subject('Votre demande de pret a été Accepté');
            });
        }
        if($request->input('statut') == "Refusé") {


            $user = User::find(1)->toArray();
            Mail::send('emails.mail', $user, function($message) use ($user) {
                $message->from('emmanuel.septier@hotmail.com');
                $message->to('bboybourrik972@hotmail.com');
                $message->subject('Votre demande de pret a été Refusée');
            });
        }
        if($file= $request->file('file')) {
            $inputFileName = $request->file('file');
            //dd($request->file('file'));
            // Identify file extension
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

            // Create a reader for that extension
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

            // Set specific options for the reading
            $reader->setReadDataOnly(true);

            // Load the inputfile to a spreadsheet object
            $spreadsheet = $reader->load($inputFileName);

            // Create a worksheet - Manipulate the file with this object
            $worksheet = $spreadsheet->getActiveSheet();

            foreach ($worksheet->getRowIterator() as $index => $row) {
                if($index !== 1) {
                    $cellIterator = $row->getCellIterator();

                    // Set the iterator to loop through all cells even if a cell value is not set
                    $cellIterator->setIterateOnlyExistingCells(FALSE);

                    $cell_values = array();
                    foreach ($cellIterator as $cell) {
                        $column = $cell->getColumn();
                        if ($column == "J" || $column == "K") {
                            $value = $cell->getFormattedValue();
                            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                            array_push($cell_values, $date);
                        } else {
                            array_push($cell_values, $cell->getFormattedValue());
                        }
                    }

                    $devise = $cell_values[0];
                    $no_dossier_unique = $cell_values[1];
                    $interets = $cell_values[2];
                    $frais_compagnie_courtage = $cell_values[3];
                    $total_frais_credit_pour_duree_pret = $cell_values[4];
                    $obligation_total_du_consommateur = $cell_values[5];
                    $nombre_versements = $cell_values[6];
                    $frequence_paiements = $cell_values[7];
                    $montant_versements_consecutifs = $cell_values[8];
                    $date_premier_versement = date('Y-m-d', strtotime('+12 hour', $cell_values[9]));
                    $date_dernier_versement = date('Y-m-d', strtotime('+12 hour', $cell_values[10]));
                    $compte_bancaire_institution = $cell_values[11];
                    $compte_bancaire_transit = $cell_values[12];
                    $compte_bancaire_no_compte = $cell_values[13];
                    $interets_courus = $cell_values[14];

                    $prets= DB::table('kreditpret.prets')->where('id', $id)->update(
                        [
                            'devise' => $devise,
                            'no_dossier_unique' => $no_dossier_unique,
                            'interets' => $interets,
                            'frais_compagnie_courtage' => $frais_compagnie_courtage,
                            'total_frais_credit_pour_duree_pret' => $total_frais_credit_pour_duree_pret,
                            'obligation_total_du_consommateur' => $obligation_total_du_consommateur,
                            'nombre_versements' => $nombre_versements,
                            'frequence_paiements' => $frequence_paiements,
                            'montant_versements_consecutifs' => $montant_versements_consecutifs,
                            'date_premier_versement' => $date_premier_versement,
                            'date_dernier_versement' => $date_dernier_versement,
                            'compte_bancaire_institution' => $compte_bancaire_institution,
                            'compte_bancaire_transit' => $compte_bancaire_transit,
                            'compte_bancaire_no_compte' => $compte_bancaire_no_compte,
                            'interets_courus'=> $interets_courus,
                            'date_importation_contrat' => Carbon::now()
                        ]);

                    return back()->withInput();
                    return redirect('kreditpret');

                }
            }
        }
        return back()->withInput();
        return redirect('kreditpret');
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


    public function responsable(request $request)
    {
        //
        $input= $request->all();
       // dd($input);

        $id_pret= $input['id_pret'];
        $id_responsable= $input['id_responsable'];

        echo 'id_pret:'.$id_pret;
        echo 'id_responsable:'.$id_responsable;

        $prets= DB::table('kreditpret.prets')->where('id', $id_pret)->update(
            [
                'responsable' => $id_responsable
            ]);
    }

    public function etat(request $request)
    {
        //
        $input= $request->all();
       // dd($input);
        $id_pret=$input['id_pret'];
        $etat= $input['etat'];

        echo 'id_pret'.$id_pret;
        echo 'etat'.$etat;

        $prets= DB::table('kreditpret.prets')->where('id', $id_pret)->update(
            [
                'statut' => $etat
            ]);
    }


    public function statut(request $request){
        //
        $input= $request->all();
        //dd($input);
        $id_pret=$input['id_pret'];
        $statut= $input['statut'];

        echo 'id_pret'.$id_pret;
        echo 'statut'.$statut;

        $prets= DB::table('kreditpret.prets')->where('id', $id_pret)->update(
            [
                'statutnew' => $statut
            ]);

    }


    public function cb_Kreditpret(request $request)
    {
        //
        $input= $request->all();
        //dd($input);

        $id_pret=$input['id_pret'];
        echo 'id_pret'.$id_pret;

        $prets= DB::table('kreditpret.prets')->where('id', $id_pret)->update(
            [
                'ibv' => 1
            ]);

    }

    public function Pdfdownload_kreditpret(Request $request){
        //return 'allo';
        $id = $_GET['id'];

        $InfoPrets = DB::select("SELECT
*
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE kreditpret.prets.id ='$id'");
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

    public function jsonDownload_kreditpret(request $request){

        $input= $request->all();
        //return 'Hello World';
        //dd($input);
        $id = $_GET['id'];

        $InfoPrets = DB::select("SELECT
* FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE kreditpret.prets.id ='$id'");
        //dd($InfoPrets);


        $users = DB::table('kreditpret.membres')
            ->join('kreditpret.prets', 'kreditpret.membres.id', '=', 'kreditpret.prets.id_demandeur')
            ->where('kreditpret.prets.id', '=', $id)
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

    public function flinksdownload(request $request){
        $input= $request->all();


        $customerId = "3ca7c8b3-f9b3-4e44-8c4b-222cb19e3671";
        // $loginId = "d46bcd66-aa7d-496d-8156-08d6f1259d5b";

        $loginId = $input['id'];


        $users = DB::table('kreditpret.membres')
            ->join('kreditpret.prets', 'kreditpret.membres.id', '=', 'kreditpret.prets.id_demandeur')
            ->where('kreditpret.prets.id', '=', $loginId)
            ->value('flinksLoginId');

        //dd($users);
        $postData = json_encode(array(
            'LoginId' => $users,
            'MostRecentCached' => true,
        ));
        // Initiate the secured Flinks session
        try{

            $url = "https://pretabc-api.private.fin.ag/v3/".$customerId."/BankingServices/Authorize";

            // Curl init
            $ch = curl_init();

            // -- Set the url
            curl_setopt($ch, CURLOPT_URL,$url);

            // -- Uncomment those lines if in production
            // -- This get rid of unauthenticated errors when using a developpement server (caused while testing Flinks API)
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            // -- Headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));

            // -- Returned content
            // -- true to return the data
            // -- false to print the data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // -- Http method
            curl_setopt($ch, CURLOPT_POST, true);

            // -- Http Post data
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            // -- Get the returned content
            $data = curl_exec($ch);


            // -- Debugging infos
            // var_dump(curl_getinfo($ch));
            // echo "<br/><br/>";

            // echo "errno: ".curl_errno($ch);
            // echo "<br/><br/>";

            // echo "error: ".curl_error($ch);
            // echo "<br/><br/>";

            // -- Curl terminate session
            curl_close($ch);

            // -- Manipulate the returned data
            $dataJSON = json_decode($data);
            $requestId = $dataJSON->RequestId;



            $url2 = "https://pretabc-api.private.fin.ag/v3/".$customerId."/insight/login/".$users."/attributes/".$requestId."";
            $postData2 = json_encode(array(
                "Attributes" => array(
                    "Card" => array(
                        "count_nsf_30_days",
                        "count_nsf_60_days",
                        "sum_nsf_60_days",
                        "sum_employer_income_30_days",
                        "average_monthly_employer_income",
                        "sum_loan_deposits_90_days",
                        "account_age",
                        "sum_loan_payments_30_days",
                        "sum_loan_payments_60_days",
                        "sum_loan_payments_90_days",
                        "total_debits_30_days",
                        "total_credits_30_days",
                        "sum_non_employer_income_30_days",
                        "sum_disability_60_days",
                        "sum_micro_loan_payments_60_days",
                        "average_monthly_free_cash_flow",
                        // "count_days_negative_balance"
                    )
                ),
                "Filters" => array(
                    "AccountCategory" => array(
                        "Operations"
                    )
                )
            ));
            // Curl init
            $ch2 = curl_init();

            // -- Set the url
            curl_setopt($ch2, CURLOPT_URL,$url2);

            // -- Uncomment those lines if in production
            // -- This get rid of unauthenticated errors when using a developpement server
            curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);

            // -- Headers
            curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));

            // -- Returned content
            // -- true to return the data
            // -- false to print the data
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

            // -- Http method
            curl_setopt($ch2, CURLOPT_POST, true);

            // -- Http Post data
            curl_setopt($ch2, CURLOPT_POSTFIELDS, $postData2);

            // -- Get the returned content
            $data2 = curl_exec($ch2);
            //dd($data2);
            // -- Debugging infos
            // var_dump(curl_getinfo($ch2));
            // echo "<br/><br/>";

            // echo "errno: ".curl_errno($ch2);
            // echo "<br/><br/>";

            // echo "error: ".curl_error($ch2);
            // echo "<br/><br/>";

            // -- Curl terminate session
            curl_close($ch2);

            // -- Manipulate the returned data
            $dataJSON2 = json_decode($data2, true);

            $file_url = 'storage/resultsFlinks.json';

            $fp = fopen($file_url, 'w');

            fwrite($fp, json_encode($dataJSON2, JSON_UNESCAPED_UNICODE));
            fclose($fp);

            header('Content-Description: File Transfer');
            header('Content-Type: application/json;charset=utf-8');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");

            readfile($file_url);
            exit;

        }catch(Exception $err){
            var_dump($err);
        }







    }
    public function email_Rappel(request $request){

        //dd($request->input('submitRappels'));


        $rappel= DB::connection('mysql2')->select("SELECT
                    `membres`.`id`, 
                    `membres`.`courriel`, 
                    `membres`.`prenom`,
                    `membres`.`nom`, 
                    COUNT(*) AS `nbOfLoansPendingBecauseOfDocuments`

                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`statut` IN ('Non-Traité', 'Commencé') AND 
                    -- `membres`.`acceptation_documents_par_admin` = 0 
                    (`membres`.`path_permis_conduire` IS NULL ||
                    `membres`.`path_talon_paie` IS NULL)
             
                GROUP BY 
                    membres.id");


        $loansRappelCount = count($rappel);
        //dd($rappel);

        foreach($rappel as $value) {

            $data = array('nom'=> $value->nom);

            $courriel= $value->courriel;

            Mail::send('emails.kreditpret_multiplemail',  $data, function($message) use ($courriel) {
                $message->from('info@kreditpret.com');
                $message->to($courriel);
                $message->subject('Kredit Prêt - Veuillez envoyer vos documents / Kredit Prêt - Please send us your documents');
            });

            return redirect('kreditpret');
        }

    }

    public function email($id)
    {
        //get collection

        $pret= DB::connection('mysql2')->select("SELECT
                    courriel
                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`id` = $id
              ");

        //Get only the email
        $courriel= $pret[0]->courriel;
        //dd($courriel);

        $membres= DB::connection('mysql2')->select("SELECT
                    nom
                  FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                     `prets`.`id` = $id
              ");

        //Get only the name get into array
        $data = array('nom'=> $membres[0]->nom);

        Mail::send('emails.kreditpret_mail',  $data, function($message) use ($courriel) {
            $message->from('info@kreditpret.com');
            $message->to($courriel);
            $message->subject('Kredit Prêt - Veuillez envoyer vos documents / Kredit Prêt - Please send us your documents');
        });
        return redirect('kreditpret');

    }

    public function contrat($id)
    {
        //get collection

        $pret= DB::connection('mysql2')->select("SELECT
                    courriel
                FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                    `prets`.`id` = $id
              ");

        //Get only the email
        $courriel= $pret[0]->courriel;
        //dd($courriel);

        $membres= DB::connection('mysql2')->select("SELECT
                    nom
                  FROM 
                    `membres` 
                INNER JOIN 
                    `prets` ON `membres`.`id` = `prets`.`id_demandeur` 
                 WHERE 
                     `prets`.`id` = $id
              ");

        //Get only the name get into array
        $data = array('nom'=> $membres[0]->nom);

        Mail::send('emails.kreditpret_contrat',  $data, function($message) use ($courriel) {
            $message->from('info@kreditpret.com');
            $message->to($courriel);
            $message->subject('Kredit Prêt - Veuillez accepter les contrats / Kredit Prêt - Please accept the contracts');
        });
        return redirect('kreditpret');

    }

}
