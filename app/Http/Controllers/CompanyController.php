<?php

namespace App\Http\Controllers;

use App\Company;
use App\Photo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $companies= Company::all();
        return view('company.index',compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('company.create');
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
        $input=$request->all();
//dd($input);
        //check the file
        if($file = $request->file('logo')){

            //get the name of the logo and add the time to it
            $name = time().$file->getClientOriginalName();

            //move it to images folder if not create one
            $file->move('images', $name);

            $company_logo= Company::create(array_merge($input,['logo' =>$name]));

            return redirect('company');

        }

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
       // $input= $id;

        $company= Company::find($id)->first();
        return view('company.edit', compact('company'));
        dd($company);
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
        $input= $request->all();
        //dd($input);
        if($file= $request->file('logo')){

            $name= time() .$file->getClientOriginalName();
            $file->move('images', $name);

            $company= DB::table('companies')->where('id', $id)->update(
                [
                    'nom' => $request->input('nom'),
                    'logo' => $name,
                    'responsable' => $request->input('responsable')

                ]);

            //dd($company);

            return redirect('company');

        }
        $company= DB::table('company')->where('id', $id)->update(
            [
                'nom' => $request->input('nom'),
                'logo' => $request->input('logo'),
                'responsable' => $request->input('responsable')

            ]);
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
