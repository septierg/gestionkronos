@extends('layouts.demandes_edit')

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

    <div class="container-fluid" style="margin-top:-44px;">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-white">Ajouter ou modifier un employé</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                        <li class="breadcrumb-item active">Ajouter ou modifier un employé</li>
                    </ol>
                    <button onclick="" type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Ajouter un nouvel employé</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fa fa-user header-icon-card"></i>Informations personnelles</h4>
                        <br>

                        {!! Form::model($employes,  ['method' => 'PATCH','action' => ['EmployesController@update', $employes->id],'files' =>true]) !!}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 nopadding">
                                    <label class="col-md-12" for="example-text">Nom</label>
                                    <div class="col-md-12">
                                        {!! Form::text('nom', null,array_merge(['class' => 'form-control text-muted']))!!}
                                    </div>
                                </div>
                                <div class="col-md-6 nopadding">
                                    <label class="col-md-12" for="example-text">Prénom</label>
                                    <div class="col-md-12">
                                        {!! Form::text('prenom', null, array_merge(['class' => 'form-control text-muted']))!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2 nopadding">
                                    <label class="col-md-12" for="">Adresse</label>
                                    <div class="col-md-12">
                                        {!! Form::text('adresse', null,array_merge(['class' => 'form-control text-muted']))!!}

                                    </div>
                                </div>

                                <div class="col-md-1 nopadding">
                                    <label class="col-md-12" for="">No App.</label>
                                    <div class="col-md-12">
                                        {!! Form::text('appartement', null,array_merge(['class' => 'form-control text-muted']))!!}
                                    </div>
                                </div>

                                <div class="col-md-3 nopadding">
                                    <label class="col-md-12" for="">Ville</label>
                                    <div class="col-md-12">
                                        {!! Form::text('ville', null,array_merge(['class' => 'form-control text-muted']))!!}
                                    </div>
                                </div>

                                <div class="col-md-3 nopadding">
                                    <label class="col-md-12" for="">Province</label>
                                    <div class="col-md-12">
                                        {!! Form::text('province', null,array_merge(['class' => 'form-control text-muted']))!!}
                                    </div>
                                </div>

                                <div class="col-md-3 nopadding">
                                    <label class="col-md-12" for="">Code postal</label>
                                    <div class="col-md-12">
                                        {!! Form::text('code_postal', null,array_merge(['class' => 'form-control text-muted']))!!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 nopadding">
                                    <label class="col-md-12" for="example-text">Numéro de téléphone</label>
                                    <div class="col-md-12">
                                        {!! Form::text('telephone', null,array_merge(['class' => 'form-control text-muted']))!!}<br>
                                    </div>
                                </div>
                                <div class="col-md-6 nopadding">
                                    <label class="col-md-12" for="bdate">Date de naissance</label>
                                    <div class="col-md-12">
                                        <input type="date" id="" name="bdate" class="form-control text-muted mydatepicker" placeholder="enter your birth date" value="1983-01-01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 nopadding">
                                    <label class="col-md-12" for="example-text">Date d'embauche</label>
                                    <div class="col-md-12">
                                        <input type="date" id="example-text" name="" class="form-control text-muted" placeholder="" value="2020-08-01">
                                    </div>
                                </div>
                                <div class="col-md-6 nopadding">
                                    <label class="col-md-12" for="example-text">Date de fin</label>
                                    <div class="col-md-12">
                                        <input type="date" id="example-text" name="" class="form-control text-muted" placeholder="" value="2020-08-01">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-12" for="dept">Statut</label>
                                <div class="col-sm-12">
                                    {!! Form::select('is_active', ['0' => 'Inactif', '1' => 'Actif'], null, array_merge(['class' => 'form-control text-muted','placeholder' => 'Selectionner un statut']));!!}

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fa fa-lock header-icon-card"></i>Informations sur le compte</h4>
                        <br>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-12" for="example-email">Courriel (pour la connexion)</label>
                                <div class="col-md-12">
                                    {!! Form::text('courriel', null,array_merge(['class' => 'form-control text-muted']))!!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-12" for="pwd">Mot de passe
                                </label>
                                <div class="col-md-12">
                                    {!! Form::text('mdp', null,array_merge(['class' => 'form-control text-muted']))!!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fa fa-unlock header-icon-card"></i>Niveaux d'accès</h4>
                        <br>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-12" for="dept">Veuillez sélectionner un niveau d'accès</label>
                                <div class="col-sm-12">
                                    {!! Form::select('acces', ['1' => 'Niveau d\' accès 1(Administrateur)', '2' => 'Niveau d\' accès 2(Employe)'], null, array_merge(['class' => 'form-control text-muted','placeholder' => 'Selectionner un niveau d\'accès']));!!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--<div class="row">
          <div class="col-sm-12">
              <div class="card">
                  <div class="card-body">
                      <h4 class="card-title"><i class="fa fa-tasks header-icon-card"></i>Attitration en tant que responsable</h4>
                      <br>
                      <div class="form-group">
                          <div class="row">
                              <label class="col-sm-12" for="dept">Veuillez sélectionner un niveau d'accès</label>
                              <div class="col-sm-12">
                                  <select class="form-control text-muted" id="dept">
                                      <option>Veuillez sélectionner</option>
                                      <option selected="selected">Oui</option>
                                      <option>Non</option>
                                  </select>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

         <div class="row">
             <div class="col-sm-12">
                 <div class="card">
                     <div class="card-body">
                         <h4 class="card-title"><i class="fa fa-building header-icon-card"></i>Attitration aux compagnies de Gestion Kronos</h4>
                         <br>
                         <label class="col-md-12 nopadding" for="inurl">Veuillez sélectionner des compagnies</label>
                         <br><br>
                         <select class="select2 m-b-10 select2-multiple select2-hidden-accessible" style="width: 100%" multiple="" data-placeholder="Choose" data-select2-id="4" tabindex="-1" aria-hidden="true">
                             <optgroup label="Liste des centres HAMFIT" data-select2-id="7">
                                 <option value="1" data-select2-id="8">Prêt ABC</option>
                                 <option value="2" data-select2-id="9">Krédit Prêt</option>
                                 <option value="3" data-select2-id="10">Prêt MWM</option>
                             </optgroup>
                         </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="1" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                     </div>
                 </div>
             </div>
         </div>-->


        <div class="row">
            <div class="col-sm-12">
                <div class="card" style="background-color: #000;">
                    <div class="card-body">

                        {!! Form::submit('Modifier un employé',['class'=> 'btn btn-info']) !!}
                        <button type="submit" class="btn btn-dark waves-effect waves-light">Annuler</button>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection
