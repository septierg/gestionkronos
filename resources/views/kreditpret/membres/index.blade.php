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
    </style>



    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-white">Membres</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                        <li class="breadcrumb-item active">Membres</li>
                    </ol>
                    <button onclick="" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Ajouter un nouveau membre</button>
                    <button onclick="" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Télécharger tous les courriels</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card" style="background-color: rgba(0,0,0,0.08);">
                    <div class="card-body">
                        <h4 class="card-title"><i class="header-icon-card fa fa-filter"></i>Filtrer vos résultats</h4>
                        <h6 class="card-subtitle">Veuillez sélectionner une option pour filtrer les données</h6>
                        <div class="table-responsive m-t-40">

                            {!! Form::open(['action' => 'KreditpretMembreController@index','method' => 'GET', 'class'=> 'search']) !!}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Rechercher un membre par nom</label>
                                        <div class="form-group">
                                            {!! Form::text('search_Membre', null,array('class' => 'form-control'))!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Rechercher un membre par courriel</label>
                                        <div class="form-group">
                                            {!! Form::text('search_Courriel', null,array('class' => 'form-control'))!!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <button type="submit"  class="btn btn-dark d-none d-lg-block m-l-15" style="width:100%;margin-left:4px;"><i class="fa fa-plus-circle"></i> Filtrer les résultats</button>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="container-fluid">
            <div class="row">
                <div class="col-12" style="padding-top:10px;">
                    <h3 style="padding-bottom:15px;">Liste des membres</h3>

                    <table>
                        <tr>
                            <th>Compagnie</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Courriel</th>
                            <th>Date inscription</th>
                            <th>Documents</th>
                            <th>Action</th>
                        </tr>
                        @if($membres)

                            @foreach($membres as $membre)
                                <tr>
                                    <td><img src="images/icon_kreditpret.png"  style="width:40px;margin-left:16px ;border-radius: 100%;" alt="pretabc" class=""></td>
                                    <td>{{ $membre->prenom}}</td>
                                    <td>{{ $membre->nom}}</td>
                                    <td>{{ $membre->courriel}}</td>
                                    <td>{{ $membre->date_inscription}}</td>
                                    <td>
                                        @if($membre->path_permis_conduire)
                                            <a href="{!! url("https://kreditpret.com/{$membre->path_permis_conduire}") !!}" style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">PC</button></a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">PC</button>
                                        @endif


                                        @if($membre->path_talon_paie)
                                            <a href="{!! url("https://kreditpret.com/{$membre->path_talon_paie}") !!}" style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">TP</button> </a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">TP</button>
                                        @endif


                                        @if($membre->path_specimen_cheque)
                                            <a href="{!! url("https://kreditpret.com/{$membre->path_specimen_cheque}") !!} " style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">SC</button> </a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">SC</button>
                                        @endif

                                        @if($membre->path_releve_bancaire)
                                            <a href="{!! url("https://kreditpret.com/{$membre->path_releve_bancaire}") !!}" style="color:green;"> <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">RB</button></a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">RB</button>
                                        @endif


                                        @if($membre->path_preuve_residence)
                                            <a href="{!! url("https://kreditpret.com/{$membre->path_preuve_residence}") !!}" style="color:green;"> <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">PR</button></a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">PR </button>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('kreditpret-membre.edit', $membre->id_demandeur) }}"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                        <a href="{{ route('kp_membre_email', $membre->id_demandeur) }}"><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                        <a href="{{ route('kp_membre_contrat', $membre->id_demandeur) }}"><button type="button" class="btn btn-warning btn-circle" style="background-color:green;border-color: green"> <i class="fa fa-envelope" ></i> </button></a>

                                    </td>
                            @endforeach

                        @endif


                    </table>
                    <div style="float: right;">{{ $membres->links() }}</div>
                </div>
            </div>
            </div>
@endsection