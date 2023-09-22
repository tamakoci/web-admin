<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ProdukTelurDaily;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TelurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Telur';
        $data['table'] = ProdukTelurDaily::orderByDesc('id')->limit(30)->get();
        return view('masterdata.telur',$data);
    }

    public function lastHarga(){
        $telur = ProdukTelurDaily::orderByDesc('id')->first();
        return response()->json(['data'=>$telur]);
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
        // dd($request->all());
        $request->validate([
            'date'=>'required',
            'harga'=>'required',
            'percent'=>'required',
        ]);
        try {
            ProdukTelurDaily::create($request->all());
            return redirect()->back()->with('success','New Record Telur created!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Error: '.$th->getMessage());
            
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
        $request->validate([
            'date'=>'required',
            'harga'=>'required',
            'percent'=>'required',
        ]);
        $produk  = ProdukTelurDaily::find($id);
        if(!$produk){
            return redirect()->back()->with('error','Produk Telur Not Found!');
        }
        try {
           $produk->update($request->all());
            return redirect()->back()->with('success','New Record Telur Updated!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Error: '.$th->getMessage());
            
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
        $produk  = ProdukTelurDaily::find($id);
        if(!$produk){
            return redirect()->back()->with('error','Produk Telur Not Found!');
        }
        try {
           $produk->delete();
            return redirect()->back()->with('success','New Record Telur Updated!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Error: '.$th->getMessage());
            
        }
    }
}
