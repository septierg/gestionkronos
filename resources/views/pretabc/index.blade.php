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

        function changedResponsableAjax(value){
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
                url: "pretabc/getResponsable",
                data: { 'id_responsable': id_responsable, 'id_pret' : id_pret},
                success: function(result){
                }
            });
        }

        function changedEtatAjax(value){
            var etat= value;
            //alert(etat);

            var res= etat.split("/");

            var id_pret=res[0];
            etat=res[1];

            //alert(id_pret);

            $.ajax({
                type: "GET",
                url: "pretabc/getEtat",
                data: { 'etat': etat, 'id_pret' : id_pret},
                success: function(result){
                }
            });
        }

        function changedStatutNewAjax(value){
            var statut= value;
            //alert(statut);

            var res= statut.split("-");

            var id_pret=res[0];
            statut=res[1];

            //alert(id_pret);

            $.ajax({
                type: "GET",
                url: "pretabc/getStatut",
                data: { 'statut': statut, 'id_pret' : id_pret},
                success: function(result){
                }
            });
        }
        function changedCreditBook(id){
            var id_pret= id;

            //alert(id_pret);
            var res= id_pret.split("-");

            var id_pret=res[0];
            id_pret=res[1];

            //alert(id_pret);

            $.ajax({
                type: "GET",
                url: "pretabc/creditBook",
                data: { 'id_pret': id_pret},
                success: function(result){
                }
            });
        }

        function searchAjax(value){


        }
        function filtreAjax(value){
            var search_Membre= document.getElementById("search_Membre").value;
            var date_debut= document.getElementById("date_debut").value;
            var date_fin= document.getElementById("date_fin").value;
            var statut= document.getElementById("statut").value;

            $("#submit").click(function(event){

                $.ajax({
                    type: "GET",
                    url: "pretabc/searchAjax",
                    data: { 'search_Membre': search_Membre,
                        'date_debut': date_debut,
                        'date_fin': date_fin,
                        'statut': statut,},
                    success: function(data){
                        if (data !== ""){
                           // window.location.reload(); // This is not jQuery but simple plain ol' JS
                            //window.location= "pretabc/searchAjax";
                            //alert(location);
                        }
                    }
                });
                /*setInterval(function(){
                    $("#div1").load('{{ action('PretabcPretController@searchAjax') }}');
                }, 5000);*/
                /*$.ajax({
                    type: "POST",
                    url: '/storeresearch',
                    data: {selectedMembers: arr},
                    success: function( msg ) {
                        console.log(msg);
                    }
                });*/
            });



           /* if(search_Membre !== "")
                alert(search_Membre);
            if(date_debut !== "" && date_fin != "")
                alert(date_debut + date_fin);
            if(statut !== "")
                alert(statut);*/
        }

        $(document).ready(function(){
            /* $( ".target" ).change(function() {
                 alert($(".target" ).val());
             });*/

            /*setInterval(function(){
                $("#div1").load('{{ action('PretabcPretController@searchAjax') }}');
            }, 5000);*/
            /*$("#div1").load("demo_test.txt", function(responseTxt, statusTxt, xhr){
                if(statusTxt == "success")
                    alert("External content loaded successfully!");
                if(statusTxt == "error")
                    alert("Error: " + xhr.status + ": " + xhr.statusText);
            });*/


        });
    </script>

    <div class="container-fluid" >
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
            <div class="col-12"   >
                <div class="card" style="background-color: rgba(0,0,0,0.08);">
                    <div class="card-body">
                        <h4 class="card-title"><i class="header-icon-card fa fa-filter"></i>Filtrer vos résultats</h4>
                        <h6 class="card-subtitle">Veuillez sélectionner une option pour filtrer les données</h6>
                        <div class="table-responsive m-t-40">

                            {!! Form::open(['action' => 'PretabcPretController@index','method' => 'GET', 'class'=> 'search']) !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Rechercher par nom</label>
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
                                            {!! Form::select('durée', ['' => 'Veuillez sélectionner','Dernier mois' => 'Dernier mois','Aujourd\'hui' => 'Aujourd\'hui', 'Derniere semaine' => 'Derniere semaine', '2 dernieres semaines' => '2 dernieres semaines', '3 dernieres semaines' => '3 dernieres semaines', '3 derniers mois' => '3 derniers mois','6 derniers mois' => '6 derniers mois', 'Année en cours' => 'Année en cours','Année précédente' => 'Année précédente','Voir tout' => 'Voir tout'], null, array('class' => 'form-control'));!!}<br>
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
                                        {!! Form::select('responsable', ['' => 'Veuillez sélectionner',1 => 'Maxime', 63 => 'Alexandre', 304 => 'Alicia', 1617 => 'Khalida' , 3305 => 'Félixe', 3306 => 'Nancy', 3310 => 'Francois',5239 => 'Roxanne',5635 => 'Mathieu',6134 => 'Emilie', 6774 => 'Patrick', 9062 => 'Math L', 9063 => 'Marc-Antoine', 9398 => 'Roseline' , 9399 => 'Emily S'], null, array('class' => 'form-control'));!!}<br>

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
    <div class="container-fluid" style="background-color:white; padding-left:10px; padding-right:10px;" id="div1">
        <div class="row">
            <div class="col-12" style="padding-top:10px;">
            <h3 style="padding-bottom:15px;">Liste des demandes de prêts</h3>
                @if($loansRappelCount)
                    {!! Form::open(['action' => 'PretabcPretController@email_Rappel','method' => 'GET', 'class'=> 'search']) !!}
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
                    <th>Documents</th>
                    <th>Flinks</th>
                    <th>Note</th>
                    <th>Ratio</th>
                    <th>Actions</th>
                </tr>

                    @if($prets)

                    @foreach($prets as $pret)

                <tr>
                    <td><img src="images/icon_pretabc.png"  style="width:40px;margin-left:16px ;border-radius: 100%;" alt="pretabc" class=""></td>
                    <td>{{ $pret->prenom}}-{{ $pret->nom}} <br>{{ $pret->date_creation}}</td>
                    <td>{{ $pret->montant}}</td>
                    <td>{{ $pret->frequence_remboursement}}</td>
                    <td>
                        <form action="" method="post" id="1-{{ $pret->id }}" class="creditbook" onchange="changedCreditBook(this.id)">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="checkbox" id="{{ $pret->id }}" name="creditbook" value="{{ $pret->id }}"  {{ $pret->ibv == 1 ? "checked" : null}}>

                        </form>
                    </td>
                    <td>
                        @if($pret->responsable ==9398)
                            <select class="" onChange="changedResponsableAjax(this.value)">
                                <option value="{{ $pret->responsable }}-9398">Roseline</option>
                                <option value="{{ $pret->id }}-304">Alicia</option>
                                <option value="{{ $pret->id }}-3305">Felixe</option>
                                <option value="{{ $pret->id }}-1617">Khalida</option>
                                <option value="{{ $pret->id }}-1">Maxime</option>
                                <option value="{{ $pret->id }}-63">Alexandre</option>
                                <option value="{{ $pret->id }}-3306">Nancy</option>
                                <option value="{{ $pret->id }}-3310">Francois</option>
                                <option value="{{ $pret->id }}-5239">Roxanne</option>
                                <option value="{{ $pret->id }}-5636">Mathieu</option>
                                <option value="{{ $pret->id }}-6134">Emilie</option>
                                <option value="{{ $pret->id }}-6774">Patrick</option>
                                <option value="{{ $pret->id }}-9062">Math L</option>
                                <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                <option value="{{ $pret->id }}-9399">Emily S</option>
                            </select>
                        @endif
                            @if($pret->responsable ==9399)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-9399">Emily S</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                </select>
                            @endif


                            @if($pret->responsable ==304 OR $pret->responsable == "Alicia")
                            <select class="" onChange="changedResponsableAjax(this.value)">
                                <option value="{{ $pret->responsable }}-304">Alicia</option>
                                <option value="{{ $pret->id }}-3305">Felixe</option>
                                <option value="{{ $pret->id }}-1617">Khalida</option>
                                <option value="{{ $pret->id }}-1">Maxime</option>
                                <option value="{{ $pret->id }}-63">Alexandre</option>
                                <option value="{{ $pret->id }}-3306">Nancy</option>
                                <option value="{{ $pret->id }}-3310">Francois</option>
                                <option value="{{ $pret->id }}-5239">Roxanne</option>
                                <option value="{{ $pret->id }}-5636">Mathieu</option>
                                <option value="{{ $pret->id }}-6134">Emilie</option>
                                <option value="{{ $pret->id }}-6774">Patrick</option>
                                <option value="{{ $pret->id }}-9062">Math L</option>
                                <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                <option value="{{ $pret->id }}-9398">Roseline</option>
                                <option value="{{ $pret->id }}-9399">Emily S</option>
                            </select>
                        @endif
                            @if($pret->responsable ==3305)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                        @endif
                            @if($pret->responsable == 1617)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                            @if($pret->responsable == 63)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>

                                </select>
                            @endif
                            @if($pret->responsable == 1)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>

                                </select>
                            @endif
                            @if($pret->responsable == 5239)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                            @if($pret->responsable == 5636)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                            @if($pret->responsable == 6134)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                            @if($pret->responsable == 3306)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                            @if($pret->responsable == 3310)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                            @if($pret->responsable == 6774)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                            @if($pret->responsable == 9062)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif

                            @if($pret->responsable == 9063)
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="{{ $pret->responsable }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif

                            @if($pret->responsable == "0" OR $pret->responsable == "")
                                <select class="" onChange="changedResponsableAjax(this.value)">
                                    <option value="">Aucun</option>
                                    <option value="{{ $pret->id }}-304">Alicia</option>
                                    <option value="{{ $pret->id }}-3305">Felixe</option>
                                    <option value="{{ $pret->id }}-1617">Khalida</option>
                                    <option value="{{ $pret->id }}-1">Maxime</option>
                                    <option value="{{ $pret->id }}-63">Alexandre</option>
                                    <option value="{{ $pret->id }}-3306">Nancy</option>
                                    <option value="{{ $pret->id }}-3310">Francois</option>
                                    <option value="{{ $pret->id }}-5239">Roxanne</option>
                                    <option value="{{ $pret->id }}-5636">Mathieu</option>
                                    <option value="{{ $pret->id }}-6134">Emilie</option>
                                    <option value="{{ $pret->id }}-6774">Patrick</option>
                                    <option value="{{ $pret->id }}-9062">Math L</option>
                                    <option value="{{ $pret->id }}-9063">Marc-Antoine</option>
                                    <option value="{{ $pret->id }}-9398">Roseline</option>
                                    <option value="{{ $pret->id }}-9399">Emily S</option>
                                </select>
                            @endif
                    </td>
                    <td>
                        @if($pret->statut == "Non-Traité")
                            <select class="" onChange="changedEtatAjax(this.value)">
                                <option value="{{ $pret->id }}/Non-Traité">{{ $pret->statut}}</option>
                                <option value="{{ $pret->id }}/Commencé">Commencé</option>
                                <option value="{{ $pret->id }}/Accepté">Accepté</option>
                                <option value="{{ $pret->id }}/Refusé">Refusé</option>
                            </select>
                        @endif
                        @if($pret->statut == "Commencé")
                                <select class="" onChange="changedEtatAjax(this.value)">
                                    <option value="{{ $pret->id }}/Commencé">{{ $pret->statut}}</option>
                                    <option value="{{ $pret->id }}/Non-Traité">Non-Traité</option>
                                    <option value="{{ $pret->id }}/Accepté">Accepté</option>
                                    <option value="{{ $pret->id }}/Refusé">Refusé</option>
                                </select>
                            @endif
                            @if($pret->statut == "Accepté")
                                <select class="" onChange="changedEtatAjax(this.value)">
                                    <option value="{{ $pret->id }}/Accepté">{{ $pret->statut}}</option>
                                    <option value="{{ $pret->id }}/Commencé">Commencé</option>
                                    <option value="{{ $pret->id }}/Non-Traité">Non-Traité</option>
                                    <option value="{{ $pret->id }}/Refusé">Refusé</option>
                                </select>
                            @endif
                            @if($pret->statut == "Refusé")
                                <select class="" onChange="changedEtatAjax(this.value)">
                                    <option value="{{ $pret->id }}/Refusé">{{ $pret->statut}}</option>
                                    <option value="{{ $pret->id }}/Accepté">Accepté</option>
                                    <option value="{{ $pret->id }}/Commencé">Commencé</option>
                                    <option value="{{ $pret->id }}/Non-Traité">Non-Traité</option>
                                </select>
                            @endif
                            </td>
                    <td>
                        @if($pret->statutnew == "Offre")
                            <select class="" onChange="changedStatutNewAjax(this.value)">
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
                            <select class="" onChange="changedStatutNewAjax(this.value)">
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
                            <select class="" onChange="changedStatutNewAjax(this.value)">
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
                            <select class="" onChange="changedStatutNewAjax(this.value)">
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
                            <select class="" onChange="changedResponsableAjax(this.value)">
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
                            <select class="" onChange="changedStatutNewAjax(this.value)">
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
                            <select class="" onChange="changedStatutNewAjax(this.value)">
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
                            <select class="" onChange="changedStatutNewAjax(this.value)">
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
                                <select class="" onChange="changedStatutNewAjax(this.value)">
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

                    <td>
                        @if($pret->path_permis_conduire)
                            <a href="{!! url("https://pretabc.com/{$pret->path_permis_conduire}") !!}" style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">PC</button></a>
                        @else
                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">PC</button>
                        @endif


                        @if($pret->path_talon_paie)
                            <a href="{!! url("https://pretabc.com/{$pret->path_talon_paie}") !!}" style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">TP</button> </a>
                        @else
                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">TP</button>
                        @endif


                        @if($pret->path_specimen_cheque)
                            <a href="{!! url("https://pretabc.com/{$pret->path_specimen_cheque}") !!} " style="color:green;"><button type="button" class="btn btn-success btn-circle" id="sa-documents-1">SC</button> </a>
                        @else
                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">SC</button>
                        @endif

                        @if($pret->path_releve_bancaire)
                            <a href="{!! url("https://pretabc.com/{$pret->path_releve_bancaire}") !!}" style="color:green;"> <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">RB</button></a>
                        @else
                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">RB</button>
                        @endif


                        @if($pret->path_preuve_residence)
                            <a href="{!! url("https://pretabc.com/{$pret->path_preuve_residence}") !!}" style="color:green;"> <button type="button" class="btn btn-success btn-circle" id="sa-documents-1">PR</button></a>
                        @else
                            <button type="button" class="btn btn-danger btn-circle" id="sa-documents-3">PR </button>
                        @endif
                    </td>

                    <td>

                        {!! Form::open(['action' => 'PretabcPretController@flinksdownload','method' => 'GET']) !!}
                        @if($pret->flinksLoginId == '')
                            <button type="submit" name="id" id="id" value="{{ $pret->id}}" class="btn btn-light_blue btn-circle" disabled></button>
                        @else
                            <button type="submit" name="id" id="id" value="{{ $pret->id}}"  class="btn btn-bluebutton btn-circle"></button>
                        @endif

                        {!! Form::close() !!}

                    </td>
                    <th>
                        <!-- Trigger/Open the Modal -->
                        <button onclick="document.getElementById('id01-{{ $pret->id }}').style.display='block'" type="button" class="btn btn-danger btn-circle"><i class="fa fa-sticky-note"></i></button>
                    {!! Form::open(['action' => 'PretabcPretController@store','method' => 'POST']) !!}

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
                                $montly_payment = floatval($mm_matrice[8]);
                        if($loan_amount == 2250)
                                $montly_payment = floatval($mm_matrice[9]);
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
                        <a href="{{ route('pretabc.edit', $pret->id) }}"><button type="button" title="Modification du prêt" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                        <a href="{{ route('pretabc.show', $pret->id) }}"><button type="button" title="PDF contenant les informations du prêt" class="btn btn-dark btn-circle"><i class="fa fa-file-pdf-o" style="color:white" aria-hidden="true"></i> </button></a>
                        <a href="{{ route('pret_email', $pret->id) }}"><button type="button" title="Rappel des documents" class="btn btn-warning btn-circle"><i class="fa fa-envelope"></i> </button></a>
                        <a href="{{ route('pret_contrat', $pret->id) }}"><button type="button" title="Rappel des contrats" class="btn btn-warning btn-circle" style="background-color:green;border-color: green"><i class="fa fa-envelope" ></i> </button></a>


                        {!! Form::open(['action' => 'PretabcPretController@jsonDownload','method' => 'GET']) !!}

                        <button type="submit" title="Exportation du prêt vers margil" name="id" id="id" value="{{ $pret->id}}" class="btn btn-warning btn-circle" style="background-color:#343a40;border-color: #343a40"><i class="fa fa-download"></i> </button>

                        {!! Form::close() !!}

                    </td>
                </tr>

                    @endforeach

                @endif

            </table><div style="float:right;"></div>

            </div>
        </div>
    </div>

@endsection
