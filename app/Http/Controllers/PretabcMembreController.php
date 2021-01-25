<?php

namespace App\Http\Controllers;

use App\pretabc_membres;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PretabcMembreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        if ($request->has('search_Membre')) {
            $search_Membre = $request->input('search_Membre');

            $membres = DB::table('membres')
                ->join('prets', 'membres.id', '=', 'prets.id_demandeur')
                ->Where('membres.nom', '=', $search_Membre)
                ->paginate(50);
            //$membres = DB::select("SELECT *, prets.id_demandeur, prets.statut, prets.montant, prets.date_importation_contrat, prets.message_admin_private FROM `membres` inner join prets on membres.id = prets.id_demandeur WHERE membres.nom = '$search_Membre'");

            return view('pretabc.membres.index',compact('membres'));
        }

        if ($request->has('search_Courriel')) {
            $search_Courriel = $request->input('search_Courriel');
            $search_Membre = $request->input('search_Membre');

            $membres = DB::table('membres')
                ->join('prets', 'membres.id', '=', 'prets.id_demandeur')
                ->where('membres.courriel', '=', $search_Courriel)
                ->orWhere('membres.nom', '=', $search_Membre)
                ->paginate(50);
            //$membres = DB::select("SELECT *, prets.id_demandeur, prets.statut, prets.montant, prets.date_importation_contrat, prets.message_admin_private FROM `membres` inner join prets on membres.id = prets.id_demandeur WHERE membres.courriel = '$search_Courriel'");

            return view('pretabc.membres.index',compact('membres'));
        }


        $membres = DB::table('membres')
            ->join('prets', 'membres.id', '=', 'prets.id_demandeur')
            ->orderBy('date_inscription', 'desc')
            ->paginate(50);

        //dd($membres);

        //$membres = DB::select('SELECT *, prets.id_demandeur, prets.statut, prets.montant, prets.date_importation_contrat, prets.message_admin_private FROM `membres` inner join prets on membres.id = prets.id_demandeur limit 200');

        return view('pretabc.membres.index',compact('membres'));


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
        //$input->save();
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
        //$membres= pretabc_membres::findOrFail($id);
        $membres= DB::table('membres')
            ->join('prets', 'membres.id', '=', 'prets.id_demandeur')
            ->where('membres.id', $id)->first();
        //dd($membres);

        return view('pretabc.membres.edit', compact('membres'));
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
        $membres= DB::table('membres')->where('id', $id)->update(
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
                'montant_mensuel_matrice' => $request->input('montant_m1' ). ";" . $request->input('montant_m2') . ";" . $request->input('montant_m3') . ";" . $request->input('montant_m4') . ";" . $request->input('montant_m5') . ";" . $request->input('montant_m6') . ";" . $request->input('montant_m7') . ";" . $request->input('montant_m8') . ";" . $request->input('montant_m9') . ";" . $request->input('montant_m10') . ";" . $request->input('montant_m11'),
                'acceptation_documents_par_admin'=> $request->input('acceptation_documents_par_admin'),


            ]);

        return back()->withInput();
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
        //get collection
        $courriel= DB::table('membres')->where('id', $id)->value('courriel');
        $membres= DB::table('membres')->where('id', $id)->get(array('nom'));
        //$membres= DB::table('membres')->where('id', $id)->get();

        //get into array
        $membres= $membres->toArray()[0];
        $data = array('nom'=> $membres->nom);

        Mail::send('emails.pretabc_mail',  $data, function($message) use ($courriel) {
            $message->from('info@pretabc.com');
            $message->to($courriel);
            $message->subject('PrêtABC - Veuillez envoyer vos documents  / PrêtABC - Please send us your documents');
        });
        return redirect('membre');

    }

    public function contrat($id)
    {
        //
        $courriel= DB::table('membres')->where('id', $id)->value('courriel');
        $membres= DB::table('membres')->where('id', $id)->get(array('nom'));

        $membres= $membres->toArray()[0];
        $data = array('nom'=> $membres->nom);

        Mail::send('emails.pretabc_contrat',  $data, function($message) use ($courriel) {
            $message->from('info@pretabc.com');
            $message->to($courriel);
            $message->subject('PrêtABC - Veuillez accepter les contrats / PrêtABC - Please accept the contracts');
        });
        return redirect('membre');

    }


}
