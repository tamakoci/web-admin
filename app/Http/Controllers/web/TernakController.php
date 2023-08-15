<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Ternak;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TernakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Ternak';
        $data['table'] = Ternak::with('produk')->get();
        $data['produk'] = Product::all();
        return view('masterdata.ternak',$data);
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
         $validator = $request->validate([
            'image' => 'required',
            'name' => 'required',
            'price' => 'required',
            'duration' => 'required',
            'produk_id' => 'required',
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
            $filepath =  asset('') . "files/images/".$filename;
            $request['avatar'] = $filepath;
            Ternak::create($request->all());
            return redirect()->back()->with('success','Ternak created');
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
        $data = Ternak::find($id);
        if(!$data){
            return redirect()->back()->with('error','Update gagal, data tidak ditemukan!');
        }
        try {
            if(isset($request->image)){
                $file = $request->file('image');
                $filename = time(). "." . $file->getClientOriginalExtension();
                $location = "public/files/images/";
                
                $file->move($location,$filename);
            
                $filepath =  asset('')  . "files/images/".$filename;
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
        $find = Ternak::find($id);
        if ($find) {
            $find->delete();
           return redirect()->back()->with('success','Ternak Deleted');
        } else {
           return redirect()->back()->with('error','Data not found');
        }
    }
}
