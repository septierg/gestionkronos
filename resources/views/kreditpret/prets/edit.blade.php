@extends('layouts.demandes_edit')

@section('content')
    <style>

        h3, .h3 {
            font-size: 1.5rem;
        }
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            margin-bottom: 0.5rem;
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            line-height: 1.2;
            color: inherit;
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
        .card {
            margin-bottom: 20px;
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0px solid transparent;
            border-radius: 0px;
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
    </style>
    <div class="container-fluid" >
        <div class="row page-titles" style="position: relative;top: -43px; ">
            <div class="col-md-5 align-self-center">
                <h4 class="text-white">Modifier une demande de prêt</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item active">Ajouter ou modifier un membre</li>
                    </ol>
                    {!! Form::model($prets, ['method' => 'PATCH','action' => ['KreditpretPretController@update', $prets->id],'files' =>true]) !!}
                    <a href="{{ route('kreditpret-membre.edit', $prets->id_demandeur) }}">
                        <button onclick="" type="button" class="btn btn-info d-none d-lg-block m-l-15">
                            <i class="fa fa-plus-circle"></i>  Voir le membre lié à ce prêt
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h3 style="padding-bottom:15px;">Informations de la demande</h3>

                        <img src="../../images/icon_kreditpret.png" style="width:50px; margin:10px;" alt="kreditpret" class=""><br><br>

                        <label>No d'entente</label><br>
                        <input type="text" id="id" name="id" disabled value="{{$prets->id}}"><br>

                        <label>Date inscription au prêt</label><br>
                        <input type="text" id="id" name="id" disabled value="{{$prets->date_creation}}"><br>

                        {!! Form::label('title', 'État',['class'=> 'margin']) !!}<br>
                        <select id="ddlStatus" name="statut" class="form-control">
                            <option value="Non-Traité" <?php if($prets->statut == 'Non-Traité') echo"selected"; use Illuminate\Support\Facades\DB;?>>Non-Traité</option>
                            <option value="Commencé" <?php if($prets->statut == 'Commencé') echo"selected"; ?>>Commencé</option>
                            <option value="Accepté" <?php if($prets->statut == 'Accepté') echo"selected"; ?>>Accepté</option>
                            <option value="Refusé" <?php if($prets->statut == 'Refusé') echo"selected"; ?>>Refusé</option>
                        </select>
                        <br><br>
                        {!! Form::label('title', 'Montrer le contrat au client',['class'=> 'margin']) !!}<br>
                        <select name="montrer_contrat" class="form-control">
                            <option value="0" <?= $prets->montrer_contrat == 0 ? "selected" : "" ?>>Non</option>
                            <option value="1" <?= $prets->montrer_contrat == 1 ? "selected" : "" ?>>Oui</option>
                        </select>
                        <br><br>
                        {!! Form::label('title', 'Responsable',['class'=> 'margin']) !!}<br>
                        <select id="ddlStatus" name="responsable" class="form-control">
                            <option value=4633 <?php if($prets->responsable == 4633) echo"selected"; ?>>Alicia</option>
                            <option value=4456 <?php if($prets->responsable == 4456) echo"selected"; ?>>Félixe</option>
                            <option value=4626 <?php if($prets->responsable == 4626) echo"selected"; ?>>Khalida</option>
                            <option value=4389 <?php if($prets->responsable == 4389) echo"selected"; ?>>Maxime</option>
                            <option value=4394 <?php if($prets->responsable == 4394) echo"selected"; ?>>Alexandre</option>
                            <option value=4388 <?php if($prets->responsable == 4388) echo"selected"; ?>>Nancy</option>
                            <option value=4804 <?php if($prets->responsable == 4804) echo"selected"; ?>>Francois</option>
                            <option value=4535 <?php if($prets->responsable == 4535) echo"selected"; ?>>Roxannne</option>
                            <option value=4457 <?php if($prets->responsable == 4457) echo"selected"; ?>>Mathieu</option>
                            <option value=4711 <?php if($prets->responsable == 4711) echo"selected"; ?>>Émilie</option>
                            <option value=4403 <?php if($prets->responsable == 4403) echo"selected"; ?>>Patrick</option>
                            <option value=5130 <?php if($prets->responsable == 5130) echo"selected"; ?>>Math L</option>
                            <option value=5131 <?php if($prets->responsable == 5131) echo"selected"; ?>>Marc-Antoine</option>
                            <option value=5229 <?php if($prets->responsable == 5229) echo"selected"; ?>>Roseline</option>
                            <option value=5230 <?php if($prets->responsable == 5230) echo"selected"; ?>>Émily S</option>
                        </select><br>
                        <br>
                        {!! Form::label('title', 'Statut',['class'=> 'margin']) !!}<br>
                        <select id="ddlStatus" name="statutnew" class="form-control" >
                            <option value="">Veuillez sélectionner</option>
                            <option value="Manque Informations" <?php if($prets->statutnew == 'Manque Informations') echo"selected"; ?>>Manque Informations</option>
                            <option value="IBV Manquante" <?php if($prets->statutnew == 'IBV Manquante') echo"selected"; ?>>IBV Manquante</option>
                            <option value="Documents Manquants" <?php if($prets->statutnew == 'Documents Manquants') echo"selected"; ?>>Documents Manquants</option>
                            <option value="Renouvellement" <?php if($prets->statutnew == 'Renouvellement') echo"selected"; ?>>Renouvellement</option>
                            <option value="Appeler Employeur" <?php if($prets->statutnew == 'Appeler Employeur') echo"selected"; ?>>Appeler Employeur</option>
                            <option value="À Regarder" <?php if($prets->statutnew == 'À Regarder') echo"selected"; ?>>À Regarder</option>
                            <option value="Offre" <?php if($prets->statutnew == 'Offre') echo"selected"; ?>>Offre</option>
                            <option value="Contrat à Préparer" <?php if($prets->statutnew == 'Contrat à Préparer') echo"selected"; ?>>Contrat à Préparer</option>
                        </select>
                        <br><br>
                        {!! Form::label('title', 'Montant',['class'=> 'margin']) !!}<br>
                        {!! Form::text('montant', null, array_merge(['class' => 'padding_left']))!!}<br><br>


                        {!! Form::label('title', 'Fréquence des remboursements',['class'=> 'margin']) !!}<br>

                        <input type="text" id="frequence_remboursement" name="frequence_remboursement" disabled value="{{$prets->frequence_remboursement}}"><br><br>

                        {!! Form::label('title', 'Faillite au cours des 3 prochains mois',['class'=> 'margin']) !!}<br>
                        <input type="text" id="faillite" name="faillite" disabled value="{{$prets->faillite}}"><br><br>


                        {!! Form::label('title', 'Date prochaine paie',['class'=> 'margin']) !!}<br>
                        <input type="text" id="date_prochaine_paie" name="date_prochaine_paie" disabled value="{{$prets->date_prochaine_paie}}"><br><br>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h3 style="padding-bottom:15px;">Informations sur les contrats</h3>
                        {!! Form::label('file', 'Création des contrats :') !!}<br>
                        {!! Form::file('file', null )!!}<br><br>

                        {!! Form::label('title', 'Date importation des données',['class'=> 'margin']) !!}<br>
                        <input type="text" id="date_importation_contrat" name="date_importation_contrat" disabled value="{{$prets->date_importation_contrat}}"><br><br>

                    </div>
                </div>

            </div>
        </div>

        <div class="container-fluid" style="padding-bottom:15px;">
            <div class="row" style="background-color:white">
                <div class="col-12" style="padding-top:10px;">
                    <div class="card">
                        <h3 style="padding-bottom:15px;">Acceptation du contrat</h3>
                        {!! Form::label('title', 'Date acceptation des contrats',['class'=> 'margin']) !!}<br>
                        <input type="text" id="date_acceptation" name="date_acceptation" disabled value="{{$prets->date_acceptation}}"><br><br>

                        {!! Form::label('title', 'Note privée :') !!}<br>
                        {!! Form::text('message_admin_private', null, array_merge(['class' => 'padding_left']))!!}<br><br>


                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {!! Form::submit('Sauvegarder',['class'=> 'w3-blue-grey']) !!}{!! Form::submit('Annuler',['class'=> 'w3-black']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection