<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TopupPakan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TopupPakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $data['title'] = 'Topup Pakan';
        $data['table'] = TopupPakan::all();
        return view('masterdata.topup-pangan',$data);
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
            'pakan' => 'required',
            'diamon' => 'required',
            'status'=>'required'
        ]);
        try {
            TopupPakan::create($request->all());
            return redirect()->back()->with('success','Data created!');
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
        $cek = TopupPakan::find($id);
        if(!$cek){
            return redirect()->back()->with('error','Data not found!');
        }
        try {
            $cek->update($request->all());
            return redirect()->back()->with('success','Data Updated');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server error');
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
        $cek = TopupPakan::find($id);
        if(!$cek){
            return redirect()->back()->with('error','Data not found');
        }
        try {
            $cek->delete();
            return redirect()->back()->with('success','Data Deleted');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
        }
    }
}
