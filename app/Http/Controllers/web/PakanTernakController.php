<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\PakanTernak;
use App\Models\Ternak;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PakanTernakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Pakan Ternak';
        $data['table'] = PakanTernak::all();
        $data['ternak'] = Ternak::all();
        return view('masterdata.pakan-ternak',$data);

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
        $validate = $request->validate([
            'ternak_id' => 'required',
            'pakan' => 'required'
        ]);
        try {
            PakanTernak::create($request->all());
            return redirect()->back()->with('success','Data Created');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
            
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
        $data = PakanTernak::find($id);
        if(!$data){
            return redirect()->back()->with('error','Update gagal, data tidak ditemukan');
        }
        try {
            $data->update($request->all());
            return redirect()->back()->with('success','Data Updated');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = PakanTernak::find($id);
        if(!$data){
            return redirect()->back()->with('error','Delete gagal, data tidak ditemukan');
        }
        try {
            $data->delete();
            return redirect()->back()->with('success','Data Terhapus');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
            
        }
    }
}
