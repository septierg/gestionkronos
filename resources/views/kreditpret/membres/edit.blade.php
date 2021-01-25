@extends('layouts.demandes_edit')

@section('content')
    <style>
        .padding_left{
            margin-left:0px;
        }
        .margin{
            margin-right:10px;
        }
        .w3-button{
            padding:0px;

        }
        .d-flex {
            display: flex !important;
        }
        .container-fluid {
            padding: 0px 25px;
            margin-top: -1px;
        }
        .container-fluid {
            width: 100%;
            padding-right: 10px;
            padding-left: 10px;
            margin-right: auto;
            margin-left: auto;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
        }
        .page-titles {
            background: #000;
            margin: 0 -10px 0px -24px;
        }
        .align-self-center {
            align-self: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .align-items-center {
            align-items: center !important;
        }
        .justify-content-end {
            justify-content: flex-end !important;
        }
        .page-titles .breadcrumb {
            padding: 0px;
            margin: 0px;
            background: transparent;
            font-size: 12px;
        }
        .page-titles .breadcrumb .breadcrumb-item.active {
            color: #80b8c3;
            font-weight: 500;
        }
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            list-style: none;
            background-color: #fff;
            border-radius: 0.25rem;
        }
        .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.5rem;
        }
        html body .m-l-15 {
            margin-left: 15px;
        }
        .btn-info {
            color: #fff;
            background-color: #80b8c3;
            border-color: #80b8c3;
        }
        button:not(:disabled), [type="button"]:not(:disabled), [type="reset"]:not(:disabled), [type="submit"]:not(:disabled) {
            cursor: pointer;
        }
        ol li {
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: white;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: #80b8c3;
            border-color: #80b8c3;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col, .col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm, .col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md, .col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg, .col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl, .col-xl-auto {
            position: relative;
            padding-right: 10px;
            padding-left: 10px;
        }
        .text-white {
            color: #fff !important;
        }
        h4, .h4 {
            font-size: 1.125rem;
        }
        @media (min-width: 992px){
            .d-lg-block {
                display: block !important;
            }
        }
        @media (min-width: 768px){
            .col-md-5 {
                flex: 0 0 41.66667%;
                max-width: 41.66667%;
            }}

        @media (min-width: 768px){
            .col-md-7 {
                flex: 0 0 58.33333%;
                max-width: 58.33333%;
            }
        }
        @media (min-width: 576px) {


        }





    </style>



    <?php
    session_start();
    // Set session variables
    $_SESSION["admin"] = "isAdmin";

    //echo $_SESSION["admin"];
    ?>

    <div class="container-fluid">
        <div class="row page-titles" style="position: relative;top: -43px; ">
            <div class="col-md-5 align-self-center">
                <h4 class="text-white">Ajouter ou modifier un membre</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                        <li class="breadcrumb-item active">Ajouter ou modifier un membre</li>
                    </ol>
                    {!! Form::model($membres, ['method' => 'PATCH','action' => ['KreditpretMembreController@update', $membres->id_demandeur],'files' =>true])!!}

                    <a href="{{ route('kreditpret-membre.edit', $membres->id) }}">
                        <button class="btn btn-info d-none d-lg-block m-l-15" ><a href="https://kreditpret.com/mon-compte.php?admin={{ $_SESSION["admin"]}}&id={{ $membres->id_demandeur }}"  style="color:white;"><i class="fa fa-plus-circle"></i> Voir les contrats et informations de ce client</a></button>

                        <input type="hidden" name="admin" value="{{ $membres->id_demandeur}}">

                </div>
            </div>
        </div>

        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h4>Informations personnelles</h4>



                        <label>Compagnie</label>
                        <img src="../../images/icon_kreditpret.png"  style="width:35px; margin:15px;" alt="kreditpret" class=""><br>
                        <label>No de Client</label>
                        <input type="text" id="id" name="id" disabled value="{{$membres->id_demandeur}}"><br><br>
                        {!! Form::label('title', 'Prenom',['class'=> 'margin']) !!}
                        {!! Form::text('prenom', null, array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Nom',['class'=> 'margin']) !!}
                        {!! Form::text('nom', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Date de naissance',['class'=> 'margin']) !!}
                        {!! Form::text('date_naissance', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Telephone',['class'=> 'margin']) !!}
                        {!! Form::text('telephone', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Adresse',['class'=> 'margin']) !!}
                        {!! Form::text('adresse', null,array_merge(['class' => 'padding_left']))!!}

                        {!! Form::label('title', 'Appartement',['class'=> 'margin']) !!}
                        {!! Form::text('appartement', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Ville',['class'=> 'margin']) !!}
                        {!! Form::text('ville', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Code postal',['class'=> 'margin']) !!}
                        {!! Form::text('code_postal', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Province',['class'=> 'margin']) !!}
                        {!! Form::text('province', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Note ') !!}
                        {!! Form::text('message_admin_private', null, array_merge(['class' => 'padding_left']))!!}<br><br>

                    </div>
                </div>


            </div>
        </div>
        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h4>Informations bancaires</h4>
                        {!! Form::label('title', 'Source revenus',['class'=> 'margin']) !!}
                        {!! Form::text('source_revenus', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Institutions bancaires',['class'=> 'margin']) !!}
                        {!! Form::text('banque', null,array_merge(['class' => 'padding_left']))!!}<br>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h4>Employeur</h4>
                        {!! Form::label('title', 'Nom de l\'employeur',['class'=> 'margin']) !!}
                        {!! Form::text('nom_employeur', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Numéro de téléphone de l\' employeur',['class'=> 'margin']) !!}
                        {!! Form::text('telephone_employeur', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Personne ressource',['class'=> 'margin']) !!}
                        {!! Form::text('personne_ressource', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'Date d\'embauche',['class'=> 'margin']) !!}
                        <input type="text" id="id" name="id" disabled value="{{ $membres->date_embauche}}">


                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h4>Référence</h4>
                        {!! Form::label('title', 'Type de référence',['class'=> 'margin']) !!}
                        {!! Form::text('type_reference', null,array_merge(['class' => 'padding_left']))!!}<br>


                        {!! Form::label('title', 'nom reference',['class'=> 'margin']) !!}
                        {!! Form::text('nom_reference', null,array_merge(['class' => 'padding_left']))!!}<br>



                        {!! Form::label('title', 'Téléphone de la reference',['class'=> 'margin']) !!}
                        {!! Form::text('telephone_reference', null,array_merge(['class' => 'padding_left']))!!}<br>


                        {!! Form::label('title', 'Relation avec cette personne',['class'=> 'margin']) !!}
                        {!! Form::text('relation_reference', null,array_merge(['class' => 'padding_left']))!!}<br>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h4>Prêt en cours</h4>

                        {!! Form::model($membres, ['method' => 'PATCH','action' => ['KreditpretMembreController@show', $membres->id],'files' =>true]) !!}
                        <label>Numéro d'entente</label><br>
                        <input type="text" id="id" name="id" disabled value="{{ $membres->id}}"><br>

                        <label>Date d'inscription au prêt </label><br>
                        <input type="text" id="id" name="id" disabled value="{{ $membres->date_inscription}}"><br>

                        <label>Statut </label><br>
                        <input type="text" id="id" name="id" disabled value="{{ $membres->statut}}"><br>

                        <label>Montant du prêt </label><br>
                        <input type="text" id="id" name="id" disabled value="{{ $membres->montant}}"><br>

                        <label>Message </label><br>
                        <input type="text" id="id" name="id" disabled value="{{ $membres->message_admin_private}}"><br>

                        <label>Date d'importation des données (contrats) </label><br>
                        <input type="text" id="id" name="id" disabled value="{{ $membres->date_importation_contrat}}"><br>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button class="w3-blue-grey"><a href="{{route('kreditpret.edit', $membres->id) }}" style="color:white">Voir le pret en cours</a></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-6" style="padding-top:10px;">
                    <div class="card">
                        <h4>Ratio endettement</h4>
                        {!! Form::label('title', 'revenu mensuel brut',['class'=> 'margin']) !!}
                        {!! Form::text('revenu_mensuel_brut', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'loyer mensuel',['class'=> 'margin']) !!}
                        {!! Form::text('loyer_mensuel', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'montant electricite mensuel',['class'=> 'margin']) !!}
                        {!! Form::text('montant_electricite_mensuel', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'autre montant mensuel habitude',['class'=> 'margin']) !!}
                        {!! Form::text('autre_montant_mensuel_habit', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'montant location auto',['class'=> 'margin']) !!}
                        {!! Form::text('montant_loc_auto', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'montant achat meuble',['class'=> 'margin']) !!}
                        {!! Form::text('montant_achat_meuble', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'montant autre obligation',['class'=> 'margin']) !!}
                        {!! Form::text('montant_autre_oblig', null,array_merge(['class' => 'padding_left']))!!}<br>

                        {!! Form::label('title', 'compte conjoint',['class'=> 'margin']) !!}
                        {!! Form::text('compte_conjoint', null,array_merge(['class' => 'padding_left']))!!}<br>


                    </div>
                </div>

                <div class="col-6" style="padding-top:10px;">
                    <div class="card">
                        <h4>Prêt versus montant par mois</h4>
                        <?php
                        $mm_matrice = explode(";", $membres->montant_mensuel_matrice);
                        ?>

                        <div class="form-group">
                            {!! Form::label('title', '100$',['class'=> 'margin']) !!}

                            <input id="" name="montant_m1" value="<?= $mm_matrice[0] ?>" type="number" step="0.01" class="form-control">

                        </div>
                        <div class="form-group">
                            {!! Form::label('title', '250$',['class'=> 'margin']) !!}

                            <input id="" name="montant_m2" value="<?= $mm_matrice[1] ?>" type="number" step="0.01" class="form-control">

                        </div>
                        <div class="form-group">
                            {!! Form::label('title', '500$',['class'=> 'margin']) !!}

                            <input id="" name="montant_m3" value="<?= $mm_matrice[2] ?>" type="number" step="0.01" class="form-control">

                        </div>
                        <div class="form-group">
                            {!! Form::label('title', '750$',['class'=> 'margin']) !!}

                            <input id="" name="montant_m4" value="<?= $mm_matrice[3] ?>" type="number" step="0.01" class="form-control">

                        </div>
                        <div class="form-group">
                            {!! Form::label('title', '1000$',['class'=> 'margin']) !!}

                            <input id="" name="montant_m5" value="<?= $mm_matrice[4] ?>" type="number" step="0.01" class="form-control">

                        </div>
                        <div class="form-group">
                            {!! Form::label('title', '1250$',['class'=> 'margin']) !!}

                            <input id="" name="montant_m6" value="<?= $mm_matrice[5] ?>" type="number" step="0.01" class="form-control">

                        </div>
                        <div class="form-group">
                            {!! Form::label('title', '1500$',['class'=> 'margin']) !!}

                            <input id="" name="montant_m7" value="<?= $mm_matrice[6] ?>" type="number" step="0.01" class="form-control">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">

                    <h4>Liste des documents</h4>

                        <div class="row">
                            <div class="col-12">

                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th style="text-align: center!important;">Photo</th>
                                            <th>Type de document</th>
                                            <th>Date de création</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th style="text-align: center!important;">Photo</th>
                                            <th>Type de document</th>
                                            <th>Date de création</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        <tr>
                                            <td style="text-align: center!important;">
                                                <a href="{{url('https://kreditpret.com/'.$membres->path_permis_conduire)}}">
                                                    <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">
                                                        <i class="fa fa-id-card"></i>
                                                    </button>
                                                </a>
                                            </td>
                                            <td>Permis de conduire</td>
                                            <td>{{ $membres->date_ajout_permis_conduire }}</td>
                                            <td>
                                                <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center!important;">
                                                <a href="{{url('https://kreditpret.com/'.$membres->path_releve_bancaire)}}">
                                                    <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">
                                                        <i class="fa fa-id-card"></i>
                                                    </button>
                                                </a>
                                            </td>
                                            <td>Relevé bancaire</td>
                                            <td>{{ $membres->date_ajout_releve_bancaire }}</td>
                                            <td>
                                                <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center!important;">
                                                <a href="{{url('https://kreditpret.com/'.$membres->path_specimen_cheque)}}">
                                                    <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">
                                                        <i class="fa fa-id-card"></i>
                                                    </button>
                                                </a>
                                            </td>
                                            <td>Spécimen chèque</td>
                                            <td>{{ $membres->date_ajout_specimen_cheque }}</td>
                                            <td>
                                                <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center!important;">
                                                <a href="{{url('https://kreditpret.com/'.$membres->path_talon_paie)}}">
                                                    <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">
                                                        <i class="fa fa-id-card"></i>
                                                    </button>
                                                </a>
                                            </td>
                                            <td>Talon de paie</td>
                                            <td>{{ $membres->date_ajout_talon_paie }}</td>
                                            <td>
                                                <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center!important;">
                                                <a href="{{url('https://kreditpret.com/'.$membres->path_preuve_residence)}}">
                                                    <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">
                                                        <i class="fa fa-id-card"></i>
                                                    </button>
                                                </a>
                                            </td>
                                            <td>Preuve de résidence</td>
                                            <td>{{ $membres->date_ajout_preuve_residence }} </td>
                                            <td>
                                                <a href=""><button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 nopadding">
                                            <label class="col-md-12">Approuver les documents</label>
                                            <div class="col-md-12">
                                                <select name="acceptation_documents_par_admin" class="form-control">
                                                    <option value="0" <?= $membres->acceptation_documents_par_admin == 0 ? "selected" : "" ?>>Non</option>
                                                    <option value="1" <?= $membres->acceptation_documents_par_admin == 1 ? "selected" : "" ?>>Oui</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 nopadding">
                                            <label class="col-md-12">Envoi courriel de rappel (documents)</label>
                                            <div class="col-md-12">
                                                <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="2020-08-16 10:00:00">
                                            </div>
                                        </div>
                                        <div class="col-md-4 nopadding">
                                            <label class="col-md-12">Envoi courriel de rappel (contrats)</label>
                                            <div class="col-md-12">
                                                <input type="text" id="" name="example-text" class="form-control text-muted" placeholder="" value="2020-08-16 10:00:00">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    <br>
                    <br>

                </div>

            </div>
        </div>


        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h4>Informations sur le compte</h4>
                        {!! Form::label('title', 'Courriel',['class'=> 'margin']) !!}
                        {!! Form::text('courriel', null,array_merge(['class' => 'padding_left']))!!}<br>


                        {!! Form::label('title', 'Mot de passe',['class'=> 'margin']) !!}
                        {!! Form::text('mdp', null,array_merge(['class' => 'padding_left']))!!}<br>

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        {!! Form::submit('Sauvegarder',['class'=> 'w3-blue-grey']) !!}{!! Form::submit('Annuler',['class'=> 'w3-black']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>




    </div>


@endsection