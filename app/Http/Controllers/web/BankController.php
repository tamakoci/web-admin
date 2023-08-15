<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Bank';
        $data['table'] = Bank::all();
        return view('masterdata.bank',$data);
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
            'bank'=>'required',
            'code'=>'required',
            'status'=>'required'
        ]);
        try {
            Bank::create([
                'name' => $request->bank,
                'code'=>$request->code,
                'status'=>$request->status
            ]);
            return redirect()->back()->with('success','New Bank Created!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Created Failed ' . $th->getMessage());
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
        $bank = Bank::find($id);
        if(!$bank){
            return redirect()->back()->with('error','Data Tidak ditemukan!');
        }
        $validate = $request->validate([
            'bank'=>'required',
            'code'=>'required',
            'status'=>'required'
        ]);
        try {
            $bank->update([
                'name'=>$request->bank,
                'code'=>$request->code,
                'status'=>$request->status
            ]);
            return redirect()->back()->with('success','Bank Updated!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Update Failed ' . $th->getMessage());
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
        $bank = Bank::find($id);
        if(!$bank){
            return redirect()->back()->with('error','Data Tidak ditemukan!');
        }
        try {
            $bank->delete();
            return redirect()->back()->with('success','Bank Deleted!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Delete Failed ' . $th->getMessage());
        }
    }
}
