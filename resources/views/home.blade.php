@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Bienvenue dans l'admin de Gestion kronos!</div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Veuillez choisir la compagnie dans laquel vous voulez travaillez</div>
                <div class="panel-body">
                    <a href="/pretabc"><img src="images/icon_pretabc.png"  style="width:40px;margin-left:16px ;" alt="pretabc" class=""></a>
                    <a href="/kreditpret"><img src="images/icon_kreditpret.png" style="width:40px;margin-left:16px ;" alt="kreditpret"></a>

            </div>
        </div>
    </div>
</div>
@endsection
