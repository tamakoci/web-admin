<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBank;
use App\Models\UserRole;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *20523800
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Manage user';
        $data['table'] = User::with(['wallet'])->where(['user_role'=>1,'is_demo'=>0])->orderByDesc('id')->get();
        $data['role'] = UserRole::all();
        return view('admin.manage-user',$data);
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
       
        $data = User::find($id);
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
        //
    }

    public function groupUser(){
        $userBanks = DB::table('user_banks')
            ->select('nama_bank', 'account_name', 'account_number', DB::raw('COUNT(account_name) as COUNT'))
            ->where('nama_bank','!=','BANK DUMMY')
            ->groupBy('nama_bank', 'account_name', 'account_number')
            ->havingRaw('COUNT(account_name) > 1')
            ->get();
        $table = [];
        foreach ($userBanks as $key => $value) {
            $checkSame = UserBank::join('users','user_banks.user_id','=','users.id')->where(['nama_bank'=>$value->nama_bank,'account_name'=>$value->account_name])->orderByDesc('id')->get();
            $table[$key+1] =[];
            foreach ($checkSame as $v) {
                    $table[$key] = [
                        'username'  => $v->username,
                        'avatar'    => $v->avatar,
                        'jml_ternak'=> $v->jml_ternak,
                        'nama_bank' => $v->nama_bank,
                        'account_name'=> $v->account_name,
                        'account_number'=> $v->account_number
                    ];
            }
        }
        dd($table);
    }
}
