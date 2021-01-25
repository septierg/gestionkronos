<?php

          if(count($prets) > 0){
                //echo ' pdf';
                $id= $prets->id;
                //echo $id;
              $InfoPrets = DB::select("SELECT
*
FROM membres INNER JOIN prets ON membres.id = prets.id_demandeur WHERE prets.id ='$id'");
                //var_dump($InfoPrets);
          }
else{echo 'no pdf';}


?>

    @foreach($InfoPrets as $usr)


            {!! Form::open(['method' => 'GET','action' => ['PretabcPretController@Pdfdownload']]) !!}

            <input type="hidden" id="id" name="id" value="{{$usr->id}}"><br>


            <h1>Informations sur le prêt client</h1>

            {!! Form::submit('Télécharger',['class'=> 'w3-blue-grey']) !!}
            {!! Form::close() !!}


            <h4>Informations administratives</h4>
                <p>Responsable : {{$usr->responsable}}</p>
                <p>Message privé de l'administration : {{$usr->message_admin_private}}</p>

            <h4>Informations meta du prêt </h4>
            <p>No de dossier : {{$usr->id}}</p>
            <p>État : {{$usr->statut}}</p>
            <p>Statut: {{$usr->statutnew}}</p>
            <p>Prêt demandé le : {{$usr->date_inscription}}</p>
            <p>Prêt accepté le : {{$usr->date_acceptation}}</p>

            <h4>Informations générales du prêt</h4>
            <p>Montant demandé : {{$usr->montant}}</p>
            <p>Fréquence des paiements: {{$usr->frequence_remboursement}}</p>
            <p>Message du client : {{$usr->message}}</p>

            <h4>Informations uniques du client en rapport au prêt</h4>
            <p>Prédiction de faillite :{{$usr->faillite}}</p>
            <p>Date de prochaine paie : {{$usr->date_prochaine_paie}}</p>

            <h4>Informations du prêt</h4>
            <p>Devise : {{$usr->devise}}</p>
            <p>No de dossier du software: {{$usr->no_dossier_unique}}</p>
            <p>Intérêts : {{$usr->interets}}</p>
            <p>Frais compagnie de courtage: {{$usr->frais_compagnie_courtage}}</p>
            <p>Frais de crédit : {{$usr->total_frais_credit_pour_duree_pret}}</p>
            <p>Obligation totale du consommateur : {{$usr->obligation_total_du_consommateur}}</p>
            <p>Nombre de versements : {{$usr->nombre_versements}}</p>
            <p>Fréquence des paiements: {{$usr->frequence_paiements}}</p>
            <p>Montant de chacun des versements: {{$usr->montant_versements_consecutifs}}</p>
            <p>Date du premier versement : {{$usr->date_premier_versement}}</p>
            <p>Date du dernier versement : {{$usr->date_dernier_versement}}</p>
            <p>No d'institution : {{$usr->compte_bancaire_no_compte}}</p>
            <p>No de transit : {{$usr->compte_bancaire_no_compte}}</p>
            <p>No de compte: {{$usr->compte_bancaire_no_compte}}</p>
            <p>Date d'importation du contrat: {{$usr->date_importation_contrat}}</p>

            <h4>Informations générales du client</h4>
            <p>Date d'inscription : {{$usr->date_inscription}}</p>
            <p>Prénom: {{$usr->prenom}}</p>
            <p>Nom : {{$usr->nom}}</p>
            <p>Courriel: {{$usr->courriel}}</p>
            <p>Sexe :</p>
            <p>Nas:</p>
            <p>Date de naissance :{{$usr->date_naissance}}</p>
            <p>Téléphone: {{$usr->telephone}}</p>
            <p>Compagnie de téléphone: {{$usr->telephone}}</p>
            <p>Adresse : {{$usr->adresse}}</p>
            <p>Appartement : {{$usr->appartement}}</p>
            <p>Ville : {{$usr->ville}}</p>
            <p>Code postal : {{$usr->code_postal}}</p>
            <p>Province: {{$usr->province}}</p>
            <p>Source de revenus : {{$usr->source_revenus}}</p>
            <p>Banque : {{$usr->banque}}</p>

            <h4>Informations générales du client</h4>
            <p>Nom de l'employeur : {{$usr->nom_employeur}}</p>
            <p>Téléphone de l'employeur : {{$usr->telephone_employeur}}</p>
            <p>Personne ressource : {{$usr->personne_ressource}}</p>
            <p>Date d'embauche : {{$usr->date_embauche}}</p>

            <h4>Informations sur la personne ressource du client</h4>
            <p>Type de référence : {{$usr->type_reference}}</p>
            <p>Nom de la personne référence : {{$usr->nom_reference}}</p>
            <p>Téléphone de la personne référence : {{$usr->telephone_reference}}</p>
            <p>Relation avec la personne référence : {{$usr->relation_reference}}</p>


            <h4>Initiatives prises par le client en rapport au prêt</h4>
            <p>Autorisation prêt d'argent : {{$usr->autorisation_pret_argent}}</p>
            <p>Autorisation donné le : {{$usr->autorisation_pret_argent_date}}</p>
            <p>Autorisation frais de courtage : {{$usr->autorisation_frais_courtage}}</p>
            <p>Autorisation donné le: {{$usr->autorisation_frais_courtage_date}}</p>
            <p>Autorisation entente de débit : {{$usr->autorisation_entente_debit}}</p>
            <p>Autorisation donné le: {{$usr->autorisation_entente_debit_date}}</p>


@endforeach
