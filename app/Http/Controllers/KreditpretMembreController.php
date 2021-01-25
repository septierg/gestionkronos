<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KreditpretMembreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        //
        if ($request->has('search_Membre')) {
            $search_Membre = $request->input('search_Membre');

            //$membres = DB::connection('mysql2')->select("SELECT *, prets.id_demandeur, prets.statut, prets.montant, prets.date_importation_contrat, prets.message_admin_private FROM `membres` inner join prets on membres.id = prets.id_demandeur WHERE membres.nom = '$search_Membre'");
            $membres = DB::table('kreditpret.membres')
                ->join('kreditpret.prets', 'kreditpret.membres.id', '=', 'kreditpret.prets.id_demandeur')
                ->where('membres.nom', '=', $search_Membre)
                ->paginate(50);
            return view('kreditpret.membres.index',compact('membres'));
        }

        if ($request->has('search_Courriel') or ($request->has('search_Membre'))) {
            $search_Courriel = $request->input('search_Courriel');
            $search_Membre = $request->input('search_Membre');
            //$membres = DB::connection('mysql2')->select("SELECT *, prets.id_demandeur, prets.statut, prets.montant, prets.date_importation_contrat, prets.message_admin_private FROM `membres` inner join prets on membres.id = prets.id_demandeur WHERE membres.courriel = '$search_Courriel'");
            $membres = DB::table('kreditpret.membres')
                ->join('kreditpret.prets', 'kreditpret.membres.id', '=', 'kreditpret.prets.id_demandeur')
                ->where('membres.courriel', '=', $search_Courriel)
                ->orWhere('membres.nom', '=', $search_Membre)
                ->paginate(50);

            return view('kreditpret.membres.index',compact('membres'));
        }



            $membres = DB::table('kreditpret.membres')
                ->join('kreditpret.prets', 'kreditpret.membres.id', '=', 'kreditpret.prets.id_demandeur')
                ->orderBy('date_inscription', 'desc')
                ->paginate(50);

        //dd($membres);
        return view('kreditpret.membres.index',compact('membres'));
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
        //$membres = DB::connection('mysql2')->select("SELECT *, prets.id_demandeur, prets.statut, prets.montant, prets.date_importation_contrat, prets.message_admin_private FROM `membres` inner join prets on membres.id = prets.id_demandeur where membres.id= '$id'");

        $membres= DB::table('kreditpret.membres')
            ->join('kreditpret.prets', 'kreditpret.membres.id', '=', 'kreditpret.prets.id_demandeur')
            ->where('kreditpret.membres.id', $id)->first();

        //dd($membres);

        return view('kreditpret.membres.edit',compact('membres'));
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
        //dd($request->all());

        $membres= DB::table('kreditpret.membres')->where('id', $id)->update(
            [
                'prenom' => $request->input('prenom'),
                'nom'=> $request->input('nom'),
                'courriel'=> $request->input('courriel'),
                'date_naissance'=> $request->input('date_naissance'),
                'telephone'=> $request->input('telephone'),
                'adresse'=> $request->input('adresse'),
                'appartement'=> $request->input('appartement'),
                'ville'=> $request->input('ville'),
                'code_postal'=> $request->input('code_postal'),
                'province'=> $request->input('province'),
                'nom_employeur'=> $request->input('nom_employeur'),
                'telephone_employeur'=> $request->input('telephone_employeur'),
                'nom_reference'=> $request->input('nom_reference'),
                'telephone_reference'=> $request->input('telephone_reference'),

                'revenu_mensuel_brut'=> $request->input('revenu_mensuel_brut'),
                'loyer_mensuel'=> $request->input('loyer_mensuel'),
                'montant_electricite_mensuel'=> $request->input('montant_electricite_mensuel'),
                'autre_montant_mensuel_habit'=> $request->input('autre_montant_mensuel_habit'),
                'montant_loc_auto'=> $request->input('montant_loc_auto'),
                'montant_achat_meuble'=> $request->input('montant_achat_meuble'),
                'montant_autre_oblig'=> $request->input('montant_autre_oblig'),
                'compte_conjoint'=> $request->input('compte_conjoint'),
                'montant_mensuel_matrice' => $request->input('montant_m1' ). ";" . $request->input('montant_m2') . ";" . $request->input('montant_m3') . ";" . $request->input('montant_m4') . ";" . $request->input('montant_m5') . ";" . $request->input('montant_m6') . ";" . $request->input('montant_m7'),
                'acceptation_documents_par_admin'=> $request->input('acceptation_documents_par_admin'),


            ]);

        return back()->withInput();
        return redirect('kreditpret-membre');

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

    public function email($id)
    {
        //
        $courriel= DB::table('kreditpret.membres')->where('id', $id)->value('courriel');
        $membres= DB::table('kreditpret.membres')->where('id', $id)->get(array('nom'));

        //get into array
        $membres= $membres->toArray()[0];
        $data = array('nom'=> $membres->nom);

        Mail::send('emails.kreditpret_mail', $data, function($message) use ($courriel) {
            $message->from('info@kreditpret.com');
            $message->to($courriel);
            $message->subject('Kredit Prêt - Veuillez envoyer vos documents / Kredit Prêt - Please send us your documents');
        });
        return redirect('kreditpret-membre');

    }

    public function contrat($id)
    {
        //
        $courriel= DB::table('kreditpret.membres')->where('id', $id)->value('courriel');
        $membres= DB::table('kreditpret.membres')->where('id', $id)->get(array('nom'));

        //get into array
        $membres= $membres->toArray()[0];
        $data = array('nom'=> $membres->nom);

        Mail::send('emails.kreditpret_contrat',  $data, function($message) use ($courriel) {
            $message->from('info@kreditpret.com');
            $message->to($courriel);
            $message->subject('Kredit Prêt - Veuillez accepter les contrats / Kredit Prêt - Please accept the contracts');
        });
        return redirect('kreditpret-membre');

    }




}
