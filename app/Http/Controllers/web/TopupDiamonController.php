<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\TopupDiamon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TopupDiamonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Topup Diamon';
        $data['table'] = TopupDiamon::all();
        return view('masterdata.topup-diamon',$data);
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
            'diamon' => 'required',
            'price' => 'required',
            'status'=>'required'
        ]);
        try {
            TopupDiamon::create($request->all());
            return redirect()->back()->with('success','Topup Diamon created!');
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
        $cek = TopupDiamon::find($id);
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
        $cek = TopupDiamon::find($id);
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
