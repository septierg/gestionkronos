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
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-8">
                                <h5 class="card-title m-b-0">PRETABC</h5>
                                <a class="header-icon"  href="{{ route('membre.index') }}"><img src="images/icon_pretabc.png"  style="width:100%;" alt="" class=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-8">
                                <h5 class="card-title m-b-0">KREDITPRET</h5>
                                <a class="header-icon" href="{{ route('kreditpret-membre.index') }}"><img src="images/icon_kreditpret.png" style="width:100%;" alt="" class=""></a>
                            </div>
                        </div>
                    </div></div>
            </div>


        </div>




    </div>
@endsection
