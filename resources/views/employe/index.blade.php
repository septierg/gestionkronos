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
    </style>
    <div class="container-fluid">
            <div class="row page-titles">
            <div class="col-md-5 align-self-center">
            <h4 class="text-white">Employé</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                    <li class="breadcrumb-item active">Employé</li>
                </ol>
                <a href="{{route('employes.create')}}"> <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Ajouter un employé</button></a>
            </div>
            </div>
            </div>


            @if($employes)

                @foreach($employes as $employe)
            <div class="col-md-4" style="float:right;">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 text-center">
                                <a href="contact-detail.html"><img src="../assets/images/users/admin1.jpg" alt="user" class="img-circle img-responsive" style="max-height: 210px;"></a>
                            </div>
                            <div class="col-md-8">
                                <h5 class="card-title m-b-0" style="line-height: 20px;margin-top:8px;">{{ $employe->name}} {{ $employe->prenom}} <br></h5> <small>{{ $employe->email}}</small>
                                <h5 class="card-title m-b-0" style="line-height: 20px;margin-top:8px;">{{ $employe->created_at}} <br></h5>
                                <br>
                                @if($employe->role_id == 1)
                                <button type="button" class="btn waves-effect waves-light btn-sm btn-dark" style="margin-top:10px;">Administrateur</button>
                                @else
                                <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" style="margin-top:10px;">Niveau d'accès 2</button>
                                @endif

                                    <abbr title="Phone"></abbr>{{ $employe->telephone}}
                                    <br>
                                    <br>
                                    <a href="{{route('employes.edit', $employe->id)}}"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @endforeach
                @endif

        </div>
@endsection
