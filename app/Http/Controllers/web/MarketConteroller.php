<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Product;
use App\Models\RequestMarket;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MarketConteroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['title'] = 'Request Market';
        $data['table'] = Market::with('product')->get();
        // dd($data);
        $data['product'] = Product::where('status',true)->get();
        return view('market.index',$data);
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
        $validator = $request->validate([
            'image' => 'required',
            'customer' => 'required|unique:markets,customer',
            'product_id' => 'required',
            'qty' => 'required',
            'status'=>'required'
        ]);
        if (!isset($request->image)) {
            return redirect()->back()->with('error','Gambar wajib ditambahakan');
        }
        try {
            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();

            // upload file location
            $location = "public/files/images/";
            $file->move($location,$filename);
            $filepath = "files/images/".$filename;
            $request['avatar'] = $filepath;
            Market::create([
                "avatar" => $filepath,
                "customer"=>$request->customer,
                "product_id"=>$request->product_id,
                "qty"=>$request->qty,
                "status"=>$request->status
            ]);
            return redirect()->back()->with('success','Request Market created');
        } catch (QueryException $e) {
            return redirect()->back()->with('error','Server error!');
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
        // dd($request->all());
        $data = Market::find($id);
        if(!$data){
            return redirect()->back()->with('error','Update gagal, data tidak ditemukan!');
        }
        try {
            if(isset($request->image)){
                $file = $request->file('image');
                $filename = time(). "." . $file->getClientOriginalExtension();
                $location = "public/files/images/";
                
                $file->move($location,$filename);
            
                $filepath = "files/images/".$filename;
                $request['avatar'] = $filepath;
            }
            $data->update($request->all());
            return redirect()->back()->with('success','Data diupdate!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error','Server Error!');
            
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
        $find = Market::find($id);
        if ($find) {
            $find->delete();
           return redirect()->back()->with('success','Request Market Deleted');
        } else {
           return redirect()->back()->with('error','Data not found');
        }
    }
}
