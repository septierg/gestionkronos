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
    <div class="container">
        <div class="w3-row-padding">
            <h3 style="padding-top:20px;"> TACHE JOURNALIERE</h3>
            <h5>Modification de la tâche</h5>


            <div class="w3-col m6">
                {!! Form::model($tasks, ['method' => 'PATCH','action' => ['TaskAdminController@update', $tasks->id],'files' =>true]) !!}

                <br><br>
                {!! Form::label('title', 'Pret id',['class'=> 'margin']) !!}
                {!! Form::text('pret_id', null, ['class' => 'padding_left', 'disabled' => 'disabled'])!!}

                <br><br>


                {!! Form::label('title', 'Responsable',['class'=> 'margin']) !!}
                <select name="employe" id="employe">
                    <option value="Aucun" >Aucun</option>
                    <option value="Alicia" <?php if($tasks->responsable == 304) echo"selected"; ?>>Alicia</option>
                    <option value="Felixe" <?php if($tasks->responsable == 3305) echo"selected"; ?>>Felixe</option>
                    <option value="Nancy" <?php if($tasks->responsable == 3306) echo"selected"; ?>>Nancy</option>
                    <option value="Khalida" <?php if($tasks->responsable == 1617) echo"selected"; ?>>Khalida</option>
                    <option value="Francois" <?php if($tasks->responsable == 3310) echo"selected"; ?>>Francois</option>
                    <option value="Mathieu" <?php if($tasks->responsable == 'Mathieu') echo"selected"; ?>>Mathieu</option>
                    <option value="Roxanne" <?php if($tasks->responsable == 'Roxanne') echo"selected"; ?>>Roxanne</option>
                    <option value="Emilie" <?php if($tasks->responsable == 'Emilie') echo"selected"; ?>>Emilie</option>
                    <option value="shurik'n" <?php if($tasks->responsable == "shurik'n") echo"selected"; ?>>shurik'n</option>
                    <option value="dominique" <?php if($tasks->responsable == 'dominique') echo"selected"; ?>>dominique</option>
                </select>
                <br><br>

                {!! Form::select('id', $users)!!}<br>


                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                {!! Form::submit('Modifier la tâche',['class'=> 'w3-blue-grey']) !!}
                {!! Form::close() !!}


            </div>
            <div class="w3-col m6">

            </div>
        </div>
    </div>

@endsection

