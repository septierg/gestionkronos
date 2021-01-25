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
                <h4 class="text-white">Compagnies</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-white" href="javascript:void(0)">Admin</a></li>
                        <li class="breadcrumb-item active">Compagnies</li>
                    </ol>
                    <a href="{{ route('company.create') }}"> <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Ajouter une nouvelle compagnie</button></a>
                </div>
            </div>
        </div>


        <div class="row">

            @if($companies)
                @foreach($companies as $company)
                <div class="col-md-4">



                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-12 text-center">
                                <a href=""><img src="images/{{ $company->logo }}" alt="user" class="img-circle img-responsive" style="max-height: 210px;"></a>
                            </div>
                            <div class="col-md-8">
                                <h5 class="card-title m-b-0">{{ $company->nom }}</h5> <small>Responsable : <b>{{ $company->responsable }}</b><br>maxime@gestionkronos.com</small>
                                <p>
                                </p><address>
                                    4490 ch. de Chambly, Saint-Hubert, J3Y 3M8
                                    <br>
                                    <br>
                                    <abbr title="Phone"></abbr> 450-486-3404
                                    <br>
                                    <br>
                                    <a href="{{ route('company.edit', $company->id) }}"><button type="button" class="btn btn-dark btn-circle"><i class="fa fa-edit"></i> </button></a>
                                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times-circle"></i> </button>
                                </address>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                @endforeach

            @endif

        </div>




        </div>
@endsection
