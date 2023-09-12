<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBank;
use App\Models\UserWallet;
use App\Models\Withdrawl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class WithdrawlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallet = UserWallet::where('user_id',auth()->user()->id)->orderByDesc('id')->first();
        $hasil_ternak = json_decode($wallet->hasil_ternak);
        $array = (array)$hasil_ternak;
        $productInWallet = $array[1]->qty;
        $data['title'] = 'Withdrawl';
        $data['diamon'] = $wallet->diamon;
        $data['pakan'] = $wallet->pakan;
        $data['vaksin'] = $wallet->vaksin;
        $data['tools'] = $wallet->tools;
        $data['telur'] = $productInWallet;
        // dd($data);
        return view('user.req-wd',$data);
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
        $wallet = UserWallet::getWallet();
        if($wallet->diamon - $request->amount <0)return redirect()->back()->with('error','Not Enough Gems');
        $user = $request->user();
        // dd($request->all());
        DB::beginTransaction();
        try {
            Withdrawl::create([
                'user_id'       => $user->id,
                'amount'        => $request->amount,
                'currency'      => 'IDR',
                'charge'        => 0,
                'final_amount'  => $request->amount,
                'status'        => 1
            ]);
          
            UserWallet::Create([
                'user_id'   => $user->id,
                'diamon'    => $wallet->diamon - $request->amount,
                'pakan'     => $wallet->pakan,
                'vaksin'     => $wallet->vaksin,
                'tools'     => $wallet->tools,
                'hasil_ternak'     => $wallet->hasil_ternak,
            ]);
            DB::commit();
            return redirect()->back()->with('success','Withdrawl Request Send');
        } catch (\Throwable $th) {
            DB::rollBack();
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
    public function adminCheck($type){
       
        if($type == 'pending'){
            $status = 1;
        }else if($type == 'success'){
            $status = 2;
        }else{
            $status = 3 ;
        }
        $data['title'] = 'Request Withdraw';
        $data['table'] = Withdrawl::join('users','withdrawls.user_id','=','users.id')
                        ->select('withdrawls.*','users.username','users.avatar')
                        ->where('users.is_demo',0)
                        ->where('withdrawls.status',$status)
                        ->orderBy('status', 'asc')
                        ->orderBy('id', 'desc')
                        ->get();
        $data['status'] = $status;
        return view('admin.withdraw',$data);
    }
    public function checkBank(Request $request){
        if($request->ajax()){
            $rekening = UserBank::where('user_id',$request->user_id)->first();

            return ['status'=>'success','message'=>'rekening user','data'=>$rekening];
        }
    }
    public function adminCheckCommit(Request $request){
        if($request->type == 'Reject'){
            $status = 3;
        }else{
            $status = 2;
        }
        $wd = Withdrawl::findOrFail($request->wd_id);
        DB::beginTransaction();
        try {
            $wd->withdraw_information = $request->withdraw_information;
            $wd->status = $status;
            $wd->save();
            if($status == 2){
                $user = User::find($wd->user_id);
                $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
                UserWallet::create([
                    'user_id'=>$wd->user_id,
                    'diamon'=>$wallet->diamon + $wd->amount,
                    'pakan'=>$wallet->pakan,
                    'vaksin'=>$wallet->vaksin,
                    'tools'=>$wallet->tools,
                    'hasil_ternak' => $wallet->hasil_ternak
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success','Withdrawl '.$request->type);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error','Error: '.$th->getMessage());
        }
    }
}
