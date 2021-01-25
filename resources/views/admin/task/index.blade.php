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
            <h3 style="padding-top:20px;"> TÂCHES</h3>
            <h5>Liste de tâches pour les employes de PRETABC</h5>


            <div class="w3-col m6">
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Pret id</th>
                        <th>Nom</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Actions</th>

                    </tr>
                    @if($tasks)

                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->id}}</td>
                                <td>{{ $task->pret_id}}</td>
                                <td>{{ $task->name}}</td>
                                <td>{{ $task->created_at->diffForHumans()}}</td>
                                <td>{{ $task->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{ route('task.edit', $task->id) }}"><i class="fa fa-floppy-o" aria-hidden="true" style="font-size: 20px"></i></a></td>
                            </tr>

                        @endforeach

                    @endif

                </table>
            </div>
            <div class="w3-col m6">

            </div>
        </div>
    </div>

@endsection

