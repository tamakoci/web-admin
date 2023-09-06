<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Notif;
use App\Models\Ternak;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserTernak;
use App\Models\UserWallet;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] =  'Trasaction';
        $data['table'] = Transaction::with('user')->orderByDesc('id')->get();
        // dd($data);
        return view('admin.transaction',$data);
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
        //
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

    public function notifIndex(){
        $data['title'] = 'Notification';
        $data['table'] = Notif::with(['user'])->orderByDesc('id')->get();
        $data['user'] = User::where('user_role',1)->get();
        // dd($data);
        return view('masterdata.notification',$data);
    }
    public function notifStore(Request $request){
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'user_id'=>'required'
        ]);
        // dd($request->user_id == '000'?null:$request->user_id);
        $title = $request->title;
        $msg = $request->message;
        $user_id = $request->user_id === '000'?null:$request->user_id;
        $alluser = $request->user_id === '000'?1:0;
        try {
            Notif::create([
                'title'=>$title,
                'message'   => $msg,
                'user_id'   => $user_id,
                'all_user'   => $alluser
            ]);
            return redirect()->back()->with('success','Notification created!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
            
        }
    }

    public function notifUpdate(Request $request,$id){
        $notif = Notif::find($id);
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'user_id'=>'required'
        ]);
        // dd($request->user_id == '000'?null:$request->user_id);
        $title = $request->title;
        $msg = $request->message;
        $user_id = $request->user_id === '000'?null:$request->user_id;
        $alluser = $request->user_id === '000'?1:0;
        try {
            $notif->update([
                'title'=>$title,
                'message'   => $msg,
                'user_id'   => $user_id,
                'all_user'   => $alluser
            ]);
            return redirect()->back()->with('success','Notification Updated!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
            
        }
    }

    public function notifDel($id)
    {
        $cek = Notif::find($id);
        if(!$cek){
            return redirect()->back()->with('error','Notification not found');
        }
        try {
            $cek->delete();
            return redirect()->back()->with('success','Notification Deleted');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Server Error');
        }
    }

    public function ternakUser(){
        $data['title'] = 'Ternak User';
        $data['table'] = User::with(['wallet'])->where(['user_role'=>1,'is_demo'=>0])->get();
        // $data['table'] = UserTernak::with(['user','ternak'])->orderByDesc('id')->get();
        // dd($data);
        $data['user']  = User::with(['wallet'])->where('user_role',1)->get();
        $data['ternak']  = Ternak::where('status',1)->get();
        return view('admin.ternakuser',$data);
    }
    public function beliAyamPost(Request $request){
        $request->validate([
            'ternak_id' => 'required',
            'user_id' => 'required',
        ]);
       
        // if($request->ternak_id == 1){
        //     return response()->json(['status'=>'405','message'=>'Cannot Buy Free Ternak'],Response::HTTP_METHOD_NOT_ALLOWED);
        // }
        $user = User::find($request->user_id);
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        if(!$wallet){
            return redirect()->back()->with('error','Wallet Not Found');
        }
        $ternak = Ternak::find($request->ternak_id);
        if(!$ternak){
            return redirect()->back()->with('error','Ternak Not Found');
        }
        $dm = $wallet->diamon;
        if($dm < $ternak->price){
            return redirect()->back()->with('error','Gems Tidak Cukup');
        }
        DB::beginTransaction();
        try {
            
            $trxID = Transaction::trxID('BT');
            Transaction::create([
                'user_id' => $user->id,
                'last_amount' => $dm,
                'trx_amount'   => $ternak->price,
                'final_amount'=> $dm - $ternak->price,
                'trx_type'=>'-',
                'detail'=>'Buy Ternak By Gems',
                'trx_id' => $trxID
            ]);
            UserWallet::create([
                'user_id'=>$user->id,
                'diamon'=>$dm - $ternak->price,
                'pakan'=>$wallet->pakan,
                'hasil_ternak' => $wallet->hasil_ternak
            ]);
            UserTernak::create([
                'user_id'=>$user->id,
                'ternak_id'=>$request->ternak_id,
                'buy_date'=> date('Y-m-d H:i:s'),
                'status'=>1
            ]);
            DB::commit();
            return redirect()->back()->with('success','Beli Ternak Sukses');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
            // throw $e;
        }
    }
}
