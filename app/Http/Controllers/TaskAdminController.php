<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $tasks= Task::latest()->get();
        $users =User::pluck('name', 'id')->all();
        return view('admin.task.index',compact('tasks','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $tasks= Task::findOrFail($id);
        $users =User::pluck('name', 'id')->all();
        //dd($tasks);
        return view('admin.task.edit',compact('tasks','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //receiving data from the request
        $input= $request->all();
        dd($input);

        $id_user= $request->input('id');
        //dd($id_user);

        //check in the employe/user model
        $users= DB::table('users')->where('id', $id_user)->first();
        //dd($users);

        $t= Task::find($id);
        $t->name= $users->name;
        $t->save();

        /*$tasks= DB::table('tasks')->where('id', $id_user)->update(
            [
                'employe_id' =>$request->input('id'),
                'name' => $users->name
            ]);*/
        //dd($tasks);

        return redirect('task');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
