<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Product';
        $data['table'] = Product::all();
        return view('masterdata.product',$data);
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
            'name'=>'required',
            'satuan'=>'required',
            'status'=>'required'
        ]);
        try {
            Product::create($request->all());
            return redirect()->back()->with('success','Porduct created!');
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
        $cek = Product::find($id);
        if(!$cek){
            return redirect()->back()->with('error','Product not found');
        }
        try {
            $cek->update($request->all());
            return redirect()->back()->with('success','Product Updated');
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
        $cek = Product::find($id);
        if(!$cek){
            return redirect()->back()->with('error','Product not found');
        }
        try {
            $cek->delete();
            return redirect()->back()->with('success','Product Deleted');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
        }
    }
}
