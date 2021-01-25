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
    <div class="container-fluid">

        <div class="container" style="">
        <h1 style="padding-top:0px;"> Création d'une nouvelle compagnie </h1>

        {!! Form::open(['action' => 'CompanyController@store','method' => 'POST','files' =>true]) !!}


        {!! Form::label('nom', 'Nom:') !!}<br>
        {!! Form::text('nom', null)!!}<br>

        {!! Form::label('file', 'Logo:') !!}<br>
        {!! Form::file('logo', null ) !!}<br>

        {!! Form::label('responsable', 'Responsable:') !!}<br>
        {!! Form::text('responsable', null)!!}<br>

        {!! Form::submit('Ajouter une nouvelle compagnie',['class'=> 'w3-blue-grey']) !!}
        {!! Form::close() !!}

    </div>

    </div>
@endsection
