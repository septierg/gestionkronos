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

        .table th, .table td{
            padding: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script>

function responsable_Kreditpret(value){

    var id_responsable;

    id_responsable= value;
    //alert(responsable);

    var res= id_responsable.split("-");

    var id_pret=res[0];
    id_responsable=res[1];

    //alert(id_pret);
    //alert(id_responsable);
    $.ajax({
        type: "GET",
        url: "kreditpret/responsable",
        data: { 'id_responsable': id_responsable, 'id_pret' : id_pret},
        success: function(result){
        }
    });
}

function etat_Kreditpret(value){
    var id_pret;
    var etat;

    id_pret= value;
    //alert(responsable);

    var res= id_pret.split("/");
    id_pret=res[0];
    etat=res[1];
    //alert(id_pret);
    //alert(etat);
    $.ajax({
        type: "GET",
        url: "kreditpret/etat",
        data: { 'id_pret': id_pret, 'etat' : etat},
        success: function(result){
        }
    });
}

function statut_Kreditpret(value){
    var id_pret;
    id_pret= value;
    var statut;

    var res= id_pret.split("-");
    id_pret=res[0];
    statut=res[1];
    //alert(id_pret);
    //alert(statut);

    $.ajax({
        type: "GET",
        url: "kreditpret/statut",
        data: { 'id_pret': id_pret, 'statut' : statut},
        success: function(result){
        }
    });
}

function cb_Kreditpret(id){
    var nbr= id;
    var id_pret;

    var res= nbr.split("-");

    nbr=res[0];
    id_pret=res[1];

    //alert(id_pret);

    $.ajax({
        type: "GET",
        url: "kreditpret/cb",
        data: { 'id_pret': id_pret},
        success: function(result){
        }
    });

}
    </script>

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-white">Liste des demandes de prêts</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                        <li class="breadcrumb-item active">Liste des demandes de prêts</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" >
                <div class="card" style="background-color: rgba(0,0,0,0.08);">
                    <div class="card-body">
                        <h4 class="card-title"><i class="header-icon-card fa fa-filter"></i>Filtrer vos résultats</h4>
                        <h6 class="card-subtitle">Veuillez sélectionner une option pour filtrer les données</h6>
                        <div class="table-responsive m-t-40">

                            {!! Form::open(['action' => 'KreditpretPretController@index','method' => 'GET', 'class'=> 'search']) !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Rechercher un membre par nom</label>
                                        <div class="form-group">
                                            {!! Form::text('search_Membre', null,array('class' => 'form-control'))!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Par date de début</label>
                                        {!! Form::date('date_debut', null,array('class' => 'form-control'))!!}

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Par date de fin</label>
                                        {!! Form::date('date_fin', null,array('class' => 'form-control'))!!}

                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Rechercher par courriel</label>
                                        <div class="form-group">
                                            {!! Form::email('search_Email', null,array('class' => 'form-control'))!!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Par durée</label>
                                        {!! Form::select('durée', ['' => 'Veuillez sélectionner', 'Dernier mois' => 'Dernier mois','Aujourd\'hui' => 'Aujourd\'hui', 'Derniere semaine' => 'Derniere semaine', '2 dernieres semaines' => '2 dernieres semaines', '3 dernieres semaines' => '3 dernieres semaines', '3 derniers mois' => '3 derniers mois','6 derniers mois' => '6 derniers mois', 'Année en cours' => 'Année en cours','Année précédente' => 'Année précédente','Voir tout' => 'Voir tout'], null, array('class' => 'form-control'));!!}<br>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Par état du prêt</label>
                                        {!! Form::select('statut', ['' => 'Veuille choisir','Non-traité' => 'Non-traité', 'Accepté' => 'Accepté', 'Refusé' => 'Refusé', 'Commencé' => 'Commencé' ], null, array('class' => 'form-control','placeholder' => 'Veuillez sélectionner'));!!}<br>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Par responsable</label>
                                        {!! Form::select('responsable', ['' => 'Veuillez sélectionner',4389 => 'Maxime', 4394 => 'Alexandre', 4633 => 'Alicia', 4626 => 'Khalida' , 4456 => 'Félixe', 4388 => 'Nancy', 4804 => 'Francois',4535 => 'Roxanne',4457 => 'Mathieu',4711 => 'Emilie', 4403 => 'Patrick',  5130 => 'Math L', 5131 => 'Marc-Antoine', 5229 => 'Roseline', 5230 => 'Émily S'], null, array('class' => 'form-control'));!!}<br>

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
        <div class="container-fluid"  style="background-color:white; padding-left:10px; padding-right:10px;"id="div1">
            <div class="row">
                <div class="col-12" style="padding-top:10px;">
                    <h3 style="padding-bottom:15px;">Liste des demandes de prêts</h3>
                        @if($loansRappelCount)
                            {!! Form::open(['action' => 'KreditpretPretController@email_Rappel','method' => 'GET', 'class'=> 'search']) !!}
                            <input type="hidden" name="nbrRappel" id="nbrRappel" value="{{ $loansRappelCount}}">
                            <button type="submit" name="submitRappels" class="btn btn-dark d-none d-lg-block m-l-15"  style="float: right;margin-top: -28px;">Envoyer un courriel de rappel à tous les membres correspondants ({{ $loansRappelCount}} prêts)
                            </button>
                            {!! Form::close() !!}
                        @endif

                    <table class="display nowrap table table-hover table-striped table-bordered">
                        <tr>
                            <th>Compagnie</th>
                            <th>Demande</th>
                            <th>Montant</th>
                            <th>Fréquence</th>
                            <th>CB</th>
                            <th>Responsable</th>
                            <th>État</th>
                            <th>Statut</th>
                            <th>Note</th>
                            <th>Documents</th>
                            <th>Flinks</th>
                            <th>Ratio</th>
                            <th>Actions</th>
                        </tr>

                        @if($prets)

                            @foreach($prets as $pret)
                                <tr>
                                    <td><img src="images/icon_kreditpret.png" style="width:40px;margin-left:16px ;border-radius: 100%;" alt="kreditpret"></td>
                                    <td>{{ $pret->prenom}}-{{ $pret->nom}} <br>{{ $pret->date_creation}}</td>
                                    <td>{{ $pret->montant}}</td>
                                    <td>{{ $pret->frequence_remboursement}}</td>
                                    <td>
                                        <form action="" method="post" id="1-{{ $pret->id }}" class="creditbook" onchange="cb_Kreditpret(this.id)">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="checkbox" id="{{ $pret->id }}" name="creditbook" value="{{ $pret->id }}"  {{ $pret->ibv == 1 ? "checked" : null}}>

                                        </form>
                                    </td>
                                    <td>
                                        @if($pret->responsable == 4633)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable ==4456)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4626)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4394)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4389)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4535)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4457)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4711)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4388)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4804)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                        @if($pret->responsable == 4403)
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="{{ $pret->responsable }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                            @if($pret->responsable == 5130)
                                                <select class="" onChange="responsable_Kreditpret(this.value)">
                                                    <option value="{{ $pret->responsable }}-5130">Math L</option>
                                                    <option value="{{ $pret->id }}-4633">Alicia</option>
                                                    <option value="{{ $pret->id }}-4456">Felixe</option>
                                                    <option value="{{ $pret->id }}-4626">Khalida</option>
                                                    <option value="{{ $pret->id }}-4389">Maxime</option>
                                                    <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                    <option value="{{ $pret->id }}-4388">Nancy</option>
                                                    <option value="{{ $pret->id }}-4804">Francois</option>
                                                    <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                    <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                    <option value="{{ $pret->id }}-4711">Emilie</option>
                                                    <option value="{{ $pret->id }}-4403">Patrick</option>
                                                    <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                    <option value="{{ $pret->id }}-5229">Roseline</option>
                                                    <option value="{{ $pret->id }}-5230">Émily S</option>
                                                </select>
                                            @endif
                                            @if($pret->responsable == 5230)
                                                <select class="" onChange="responsable_Kreditpret(this.value)">
                                                    <option value="{{ $pret->responsable }}-5230">Émily S</option>
                                                    <option value="{{ $pret->id }}-4633">Alicia</option>
                                                    <option value="{{ $pret->id }}-4456">Felixe</option>
                                                    <option value="{{ $pret->id }}-4626">Khalida</option>
                                                    <option value="{{ $pret->id }}-4389">Maxime</option>
                                                    <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                    <option value="{{ $pret->id }}-4388">Nancy</option>
                                                    <option value="{{ $pret->id }}-4804">Francois</option>
                                                    <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                    <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                    <option value="{{ $pret->id }}-4711">Emilie</option>
                                                    <option value="{{ $pret->id }}-4403">Patrick</option>
                                                    <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                    <option value="{{ $pret->id }}-5229">Roseline</option>
                                                </select>
                                            @endif
                                            @if($pret->responsable == 5131)
                                                <select class="" onChange="responsable_Kreditpret(this.value)">
                                                    <option value="{{ $pret->responsable }}-5131">Marc-Antoine</option>
                                                    <option value="{{ $pret->id }}-4633">Alicia</option>
                                                    <option value="{{ $pret->id }}-4456">Felixe</option>
                                                    <option value="{{ $pret->id }}-4626">Khalida</option>
                                                    <option value="{{ $pret->id }}-4389">Maxime</option>
                                                    <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                    <option value="{{ $pret->id }}-4388">Nancy</option>
                                                    <option value="{{ $pret->id }}-4804">Francois</option>
                                                    <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                    <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                    <option value="{{ $pret->id }}-4711">Emilie</option>
                                                    <option value="{{ $pret->id }}-4403">Patrick</option>
                                                    <option value="{{ $pret->id }}-5130">Math L</option>
                                                    <option value="{{ $pret->id }}-5229">Roseline</option>
                                                    <option value="{{ $pret->id }}-5230">Émily S</option>
                                                </select>
                                            @endif
                                            @if($pret->responsable == 5229)
                                                <select class="" onChange="responsable_Kreditpret(this.value)">
                                                    <option value="{{ $pret->responsable }}-5229">Roseline</option>
                                                    <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                    <option value="{{ $pret->id }}-4633">Alicia</option>
                                                    <option value="{{ $pret->id }}-4456">Felixe</option>
                                                    <option value="{{ $pret->id }}-4626">Khalida</option>
                                                    <option value="{{ $pret->id }}-4389">Maxime</option>
                                                    <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                    <option value="{{ $pret->id }}-4388">Nancy</option>
                                                    <option value="{{ $pret->id }}-4804">Francois</option>
                                                    <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                    <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                    <option value="{{ $pret->id }}-4711">Emilie</option>
                                                    <option value="{{ $pret->id }}-4403">Patrick</option>
                                                    <option value="{{ $pret->id }}-5130">Math L</option>
                                                    <option value="{{ $pret->id }}-5229">Roseline</option>
                                                    <option value="{{ $pret->id }}-5230">Émily S</option>
                                                </select>
                                            @endif
                                        @if($pret->responsable == "" OR $pret->responsable == "0")
                                            <select class="" onChange="responsable_Kreditpret(this.value)">
                                                <option value="">Aucun</option>
                                                <option value="{{ $pret->id }}-4633">Alicia</option>
                                                <option value="{{ $pret->id }}-4456">Felixe</option>
                                                <option value="{{ $pret->id }}-4626">Khalida</option>
                                                <option value="{{ $pret->id }}-4389">Maxime</option>
                                                <option value="{{ $pret->id }}-4394">Alexandre</option>
                                                <option value="{{ $pret->id }}-4388">Nancy</option>
                                                <option value="{{ $pret->id }}-4804">Francois</option>
                                                <option value="{{ $pret->id }}-4535">Roxanne</option>
                                                <option value="{{ $pret->id }}-4457">Mathieu</option>
                                                <option value="{{ $pret->id }}-4711">Emilie</option>
                                                <option value="{{ $pret->id }}-4403">Patrick</option>
                                                <option value="{{ $pret->id }}-5130">Math L</option>
                                                <option value="{{ $pret->id }}-5131">Marc-Antoine</option>
                                                <option value="{{ $pret->id }}-5229">Roseline</option>
                                                <option value="{{ $pret->id }}-5230">Émily S</option>
                                            </select>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pret->statut == "Non-Traité")
                                            <select class="" onChange="etat_Kreditpret(this.value)">
                                                <option value="{{ $pret->id }}/Non-Traité">{{ $pret->statut}}</option>
                                                <option value="{{ $pret->id }}/Commencé">Commencé</option>
                                                <option value="{{ $pret->id }}/Accepté">Accepté</option>
                                                <option value="{{ $pret->id }}/Refusé">Refusé</option>
                                            </select>
                                        @endif
                                        @if($pret->statut == "Commencé")
                                            <select class="" onChange="etat_Kreditpret(this.value)">
                                                <option value="{{ $pret->id }}/Commencé">{{ $pret->statut}}</option>
                                                <option value="{{ $pret->id }}/Non-Traité">Non-Traité</option>
                                                <option value="{{ $pret->id }}/Accepté">Accepté</option>
                                                <option value="{{ $pret->id }}/Refusé">Refusé</option>
                                            </select>
                                        @endif
                                        @if($pret->statut == "Accepté")
                                            <select class="" onChange="etat_Kreditpret(this.value)">
                                                <option value="{{ $pret->id }}/Accepté">{{ $pret->statut}}</option>
                                                <option value="{{ $pret->id }}/Commencé">Commencé</option>
                                                <option value="{{ $pret->id }}/Non-Traité">Non-Traité</option>
                                                <option value="{{ $pret->id }}/Refusé">Refusé</option>
                                            </select>
                                        @endif
                                        @if($pret->statut == "Refusé")
                                            <select class="" onChange="etat_Kreditpret(this.value)">
                                                <option value="{{ $pret->id }}/Refusé">{{ $pret->statut}}</option>
                                                <option value="{{ $pret->id }}/Accepté">Accepté</option>
                                                <option value="{{ $pret->id }}/Commencé">Commencé</option>
                                                <option value="{{ $pret->id }}/Non-Traité">Non-Traité</option>
                                            </select>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pret->statutnew == "Offre")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                            </select>
                                        @endif
                                        @if($pret->statutnew == "Manque Informations")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                            </select>
                                        @endif
                                        @if($pret->statutnew == "IBV Manquante")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                            </select>
                                        @endif
                                        @if($pret->statutnew == "Documents Manquants")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                            </select>
                                        @endif
                                        @if($pret->statutnew == "Renouvellement")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                            </select>
                                        @endif
                                        @if($pret->statutnew == "Appeler Employeur")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                            </select>
                                        @endif
                                        @if($pret->statutnew == "À Regarder")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                            </select>
                                        @endif
                                        @if($pret->statutnew == "Contrat à Préparer")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="{{ $pret->statutnew}}">{{ $pret->statutnew}}</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                            </select>
                                        @endif

                                        @if($pret->statutnew == "")
                                            <select class="" onChange="statut_Kreditpret(this.value)">
                                                <option value="">Veuillez sélectionner</option>
                                                <option value="{{ $pret->id }}-Manque Informations">Manque Informations</option>
                                                <option value="{{ $pret->id }}-IBV Manquante">IBV Manquante</option>
                                                <option value="{{ $pret->id }}-Documents Manquants">Documents Manquants</option>
                                                <option value="{{ $pret->id }}-Renouvellement">Renouvellement</option>
                                                <option value="{{ $pret->id }}-Appeler Employeur">Appeler Employeur</option>
                                                <option value="{{ $pret->id }}-Offre">Offre</option>
                                                <option value="{{ $pret->id }}-Contrat à Préparer">Contrat à Préparer</option>
                                                <option value="{{ $pret->id }}-À Regarder">À Regarder</option>
                                            </select>
                                        @endif
                                    </td>

                                    <th>
                                        <!-- Trigger/Open the Modal -->

                                        <button onclick="document.getElementById('id01-{{ $pret->id }}').style.display='block'" type="button" class="btn btn-danger btn-circle"><i class="fa fa-sticky-note"></i></button>
                                    {!! Form::open(['action' => 'KreditpretPretController@store','method' => 'POST']) !!}
                                    <!-- The Modal -->
                                        <div id="id01-{{ $pret->id }}" class="w3-modal">
                                            <div class="w3-modal-content">
                                                <div class="w3-container">
                                                    <span onclick="document.getElementById('id01-{{ $pret->id }}').style.display='none'" class="w3-button w3-display-topright">&times;</span>
                                                    <p>Prêts de ({{ $pret->prenom}}-{{ $pret->nom }})</p>


                                                    {!! Form::textarea('body', $pret->message_admin_private  )!!}<br>
                                                    <input type="hidden" name="id" id="id" value="{{ $pret->id}}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                    {!! Form::submit('Enregistrer la note',['class'=> 'w3-blue-gray']) !!}
                                                    {!! Form::close() !!}


                                                </div>
                                            </div>
                                        </div></th>
                                    <td>
                                        @if($pret->path_permis_conduire)
                                            <a href="{!! url("https://kreditpret.com/{$pret->path_permis_conduire}") !!}" style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">PC</button></a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">PC</button>
                                        @endif


                                        @if($pret->path_talon_paie)
                                            <a href="{!! url("https://kreditpret.com/{$pret->path_talon_paie}") !!}" style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">TP</button> </a>
                                        @else
                                                <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">TP</button>
                                        @endif


                                        @if($pret->path_specimen_cheque)
                                            <a href="{!! url("https://kreditpret.com/{$pret->path_specimen_cheque}") !!} " style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">SC</button> </a>
                                        @else
                                                <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">SC</button>
                                        @endif

                                        @if($pret->path_releve_bancaire)
                                            <a href="{!! url("https://dev.kreditpret.com/{$pret->path_releve_bancaire}") !!}" style="color:green;"> <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">RB</button></a>
                                        @else
                                                <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">RB</button>
                                        @endif


                                        @if($pret->path_preuve_residence)
                                            <a href="{!! url("https://kreditpret.com/{$pret->path_preuve_residence}") !!}" style="color:green;"> <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">PR</button></a>
                                        @else
                                                <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">PR </button>
                                        @endif
                                    </td>
                                    <td>

                                        {!! Form::open(['action' => 'KreditpretPretController@flinksdownload','method' => 'GET']) !!}
                                        @if($pret->flinksLoginId == '')
                                            <button type="submit" name="id" id="id" value="{{ $pret->id}}" class="btn btn-light_blue btn-circle" disabled></button>
                                        @else
                                            <button type="submit" name="id" id="id" value="{{ $pret->id}}"  class="btn btn-bluebutton btn-circle"></button>
                                        @endif
                                        {!! Form::close() !!}
                                    </td>

                                    <td>
                                        <?php
                                        // Calculate ratio
                                        $loan_amount = floatval( $pret->montant);
                                        $mm_matrice = explode(";", $pret->montant_mensuel_matrice);

                                        if ($loan_amount == 100)
                                            $montly_payment = floatval($mm_matrice[0]);
                                        if($loan_amount == 250 )
                                            $montly_payment = floatval($mm_matrice[1]);
                                        if($loan_amount == 500)
                                            $montly_payment = floatval($mm_matrice[2]);
                                        if($loan_amount == 750)
                                            $montly_payment = floatval($mm_matrice[3]);
                                        if($loan_amount == 1000)
                                            $montly_payment = floatval($mm_matrice[4]);
                                        if($loan_amount == 1250)
                                            $montly_payment = floatval($mm_matrice[5]);
                                        if($loan_amount == 1500)
                                            $montly_payment = floatval($mm_matrice[6]);
                                        if($loan_amount == 1750)
                                            $montly_payment = floatval($mm_matrice[7]);
                                        if($loan_amount == 2000)
                                            $montly_payment = floatval(573.50);
                                        if($loan_amount == 2500)
                                            $montly_payment = floatval(573.50);




                                        //var_dump($mm_matrice[5]);
                                        //var_dump(is_string($mm_matrice[0]));

                                        $revenu_mensuel_brut = floatval($pret->revenu_mensuel_brut);
                                        $loyer_mensuel = floatval($pret->loyer_mensuel);
                                        $montant_electricite_mensuel = floatval($pret->montant_electricite_mensuel);
                                        $autre_montant_mensuel_habit = floatval($pret->autre_montant_mensuel_habit);
                                        $montant_loc_auto = floatval($pret->montant_loc_auto);
                                        $montant_achat_meuble = floatval($pret->montant_achat_meuble);
                                        $montant_autre_oblig = floatval($pret->montant_autre_oblig);

                                        $obligations_mensuels = $loyer_mensuel + $montant_electricite_mensuel + $autre_montant_mensuel_habit + $montant_loc_auto + $montant_achat_meuble + $montant_autre_oblig;
                                        if ($pret->compte_conjoint == "OUI") {
                                            $obligations_mensuels = $obligations_mensuels / 2;
                                        }

                                        if ($revenu_mensuel_brut > 0) {
                                            $ratio = ($obligations_mensuels + $montly_payment) / $revenu_mensuel_brut;
                                        }
                                        else {
                                            $ratio = 0;
                                        }
                                        //echo $montly_payment . "<br>";
                                        echo round($ratio*100, 2) . " %";

                                        ?>

                                    </td>
                                    <td>
                                        <a href="{{ route('kreditpret.edit', $pret->id) }}"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                        <a href="{{ route('kreditpret.show', $pret->id) }}"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-file-pdf-o" style="color:white" aria-hidden="true"></i> </button></a>
                                        <a href="{{ route('kp_pret_email', $pret->id) }}"><button type="button" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                                        <a href="{{ route('kp_pret_contrat', $pret->id) }}"><button type="button" class="btn btn-warning btn-circle" style="background-color:green;border-color: green"> <i class="fa fa-envelope" ></i> </button></a>


                                        {!! Form::open(['action' => 'KreditpretPretController@jsonDownload_kreditpret','method' => 'GET']) !!}

                                        <button type="submit" title="Exportation du prêt vers margil" name="id" id="id" value="{{ $pret->id}}" class="btn btn-warning btn-circle" style="background-color:#343a40;border-color: #343a40"><i class="fa fa-download"></i> </button>

                                        {!! Form::close() !!}
                                    </td>
                                </tr>

                            @endforeach

                        @endif


                    </table>
                </div>
            </div>
        </div>

@endsection
