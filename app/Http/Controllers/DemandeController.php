<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        /*if ($request->has('statut') and $request->has('date_debut') and $request->has('date_fin')) {
            $statut = $request->input('statut');
            $date_debut = $request->input('date_debut');
            $date_fin= $request->input('date_fin');
            //dd('GOT 3');
            $prets = DB::select("SELECT
membres.id, membres.nom, membres.prenom, membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,
prets.id,prets.compagnie prets.id_demandeur, prets.montant, prets.date_creation, 
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation
FROM pretabc.membres INNER JOIN pretabc.prets ON pretabc.membres.id = pretabc.prets.id_demandeur WHERE (date_inscription >= '$date_debut' and date_inscription <= '$date_fin' and statut = '$statut') limit 100
UNION
SELECT
membres.id, membres.nom, membres.prenom, membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,
prets.id,prets.compagnie prets.id_demandeur, prets.montant, prets.date_creation, 
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation
FROM kreditpret.membres INNER JOIN kreditpret.prets ON kreditpret.membres.id = kreditpret.prets.id_demandeur WHERE (date_inscription >= '$date_debut' and date_inscription <= '$date_fin' and statut = '$statut') limit 100




");

            return view('demande.index',compact('prets'));

        }*/

        /*if ($request->has('statut') and $request->has('date_debut') and $request->has('date_fin')) {
            $statut = $request->input('statut');
            $date_debut = $request->input('date_debut');
            $date_fin= $request->input('date_fin');
            //dd('GOT 3');
            $prets = DB::select("
        (SELECT prets.id, membres.prenom, membres.nom, prets.date_creation, prets.compagnie,membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,
prets.id, prets.id_demandeur, prets.montant, prets.date_creation,
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation FROM pretabc.membres inner join pretabc.prets  on pretabc.membres.id = pretabc.prets.id_demandeur WHERE date_inscription >= '$date_debut' and date_inscription <= '$date_fin' and statut = '$statut' limit 100)
        UNION
        (SELECT prets.id, membres.prenom, membres.nom, prets.date_creation, prets.compagnie, membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,
prets.id, prets.id_demandeur, prets.montant, prets.date_creation,
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation FROM kreditpret.membres inner join kreditpret.prets on kreditpret.membres.id = kreditpret.prets.id_demandeur where WHERE date_inscription >= '$date_debut' and date_inscription <= '$date_fin' and statut = '$statut' limit 100);
        ");


            return view('demande.index',compact('prets'));

        }
*/
        $prets = DB::select("
        (SELECT prets.id, membres.prenom, membres.nom, prets.date_creation, prets.compagnie,membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,
prets.id, prets.id_demandeur, prets.montant, prets.date_creation, 
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation FROM pretabc.membres inner join pretabc.prets  on pretabc.membres.id = pretabc.prets.id_demandeur where statut ='Non-traité' and date_inscription >= date(NOW() - INTERVAL 30 DAY)  limit 100)
        UNION
        (SELECT prets.id, membres.prenom, membres.nom, prets.date_creation, prets.compagnie, membres.courriel,membres.date_inscription,
membres.path_permis_conduire, membres.date_ajout_permis_conduire,
membres.path_specimen_cheque, membres.date_ajout_specimen_cheque,
membres.path_talon_paie, membres.date_ajout_talon_paie,
membres.path_releve_bancaire, membres.date_ajout_releve_bancaire,
membres.path_preuve_residence, membres.date_ajout_preuve_residence,
membres.montant_mensuel_matrice, membres.revenu_mensuel_brut, membres.loyer_mensuel,
membres.montant_electricite_mensuel, membres.autre_montant_mensuel_habit, membres.montant_loc_auto,
membres.montant_achat_meuble, montant_autre_oblig, membres.compte_conjoint,
prets.id, prets.id_demandeur, prets.montant, prets.date_creation, 
prets.frequence_remboursement, prets.statut, prets.montrer_contrat,
prets.message_admin_private, prets.responsable, prets.frequence_paiements, prets.faillite,
prets.date_prochaine_paie, prets.date_importation_contrat, prets.date_exportation, prets.statutnew, prets.ibv, prets.date_acceptation FROM kreditpret.membres inner join kreditpret.prets on kreditpret.membres.id = kreditpret.prets.id_demandeur where statut ='Non-traité' and date_inscription >= date(NOW() - INTERVAL 30 DAY) limit 100);
        ");

        return view('demande.index',compact('prets'));


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
        $id_user= $request->input('id');
        $prets= DB::table('prets')->where('id', $id_user)->update(
            [
                'message_admin_private' => $request->input('body')
            ]);

        return redirect('demande');
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
        dd($id);
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
        dd($id);
        $prets= DB::table('prets')->where('id', $id)->first();

        return view('demande.edit', compact('prets'));
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
}
