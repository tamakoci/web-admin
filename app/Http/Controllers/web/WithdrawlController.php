<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
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
        // dd($request->all());
        // dd(sameBankAcc());
        $wallet = UserWallet::getWallet();
        $user_gems = sameBankAcc()?sameBankAcc()['gems']:$wallet->diamon;
        if($user_gems - ($request->amount + wd('charge')) < 0)return redirect()->back()->with('error','Not Enough Gems');
        $user = $request->user();
        // dd($request->all());
        DB::beginTransaction();
        try {
            if($request->samebank == 1){
                $userID = sameBankAcc()['user_id'];
                
                foreach ($userID as $id) {
                    $wallet2 = UserWallet::where('user_id',$id)->orderByDesc('id')->first();
                    UserWallet::Create([
                        'user_id'   => $id,
                        'diamon'    => 0,
                        'pakan'     => $wallet2->pakan,
                        'vaksin'     => $wallet2->vaksin,
                        'tools'     => $wallet2->tools,
                        'hasil_ternak'     => $wallet2->hasil_ternak,
                    ]);
                    Transaction::create([
                        'user_id' => $id,
                        'last_amount' => $wallet2->diamon,
                        'trx_amount' => $wallet2->diamon,
                        'final_amount'=> 0,
                        'trx_type'=>'-',
                        'detail'=>'Deliver All Diamon to main Account: '.auth()->user()->username,
                        'trx_id' => Transaction::trxID('TFWD')
                    ]);
                    Transaction::create([
                        'user_id' => auth()->user()->id,
                        'last_amount' => $wallet->diamon,
                        'trx_amount' => $wallet2->diamon,
                        'final_amount'=> $user_gems,
                        'trx_type'=>'+',
                        'detail'=>'Deliver All Diamon from second account',
                        'trx_id' => Transaction::trxID('TFWD')
                    ]);
                }
                
                Withdrawl::create([
                    'user_id'       => auth()->user()->id,
                    'amount'        => $request->amount,
                    'currency'      => 'IDR',
                    'charge'        => wd('charge'),
                    'final_amount'  => $request->amount + wd('charge'),
                    'status'        => 1
                ]);
                UserWallet::Create([
                    'user_id'   => $user->id,
                    'diamon'    => $user_gems - ($request->amount + wd('charge')),
                    'pakan'     => $wallet->pakan,
                    'vaksin'     => $wallet->vaksin,
                    'tools'     => $wallet->tools,
                    'hasil_ternak'     => $wallet->hasil_ternak,
                ]);
                Transaction::create([
                    'user_id' => auth()->user()->id,
                    'last_amount' => $user_gems,
                    'trx_amount' => $request->amount + wd('charge'),
                    'final_amount'=> $user_gems - ($request->amount + wd('charge')),
                    'trx_type'=>'-',
                    'detail'=>'Withdraw '.$request->amount.' GEMS',
                    'trx_id' => Transaction::trxID('WD')
                ]);

            }else{
                Withdrawl::create([
                    'user_id'       => auth()->user()->id,
                    'amount'        => $request->amount,
                    'currency'      => 'IDR',
                    'charge'        => wd('charge'),
                    'final_amount'  => $request->amount - wd('charge'),
                    'status'        => 1
                ]);
                UserWallet::Create([
                    'user_id'   => $user->id,
                    'diamon'    => $wallet->diamon - ($request->amount + wd('charge')),
                    'pakan'     => $wallet->pakan,
                    'vaksin'    => $wallet->vaksin,
                    'tools'     => $wallet->tools,
                    'hasil_ternak'     => $wallet->hasil_ternak,
                ]);
                Transaction::create([
                    'user_id' => auth()->user()->id,
                    'last_amount' => $wallet->diamon,
                    'trx_amount' =>  ($request->amount + wd('charge')),
                    'final_amount'=> $wallet->diamon -  ($request->amount + wd('charge')),
                    'trx_type'=>'-',
                    'detail'=>'Withdraw '.$request->amount.' GEMS',
                    'trx_id' => Transaction::trxID('WD')
                ]);
            }
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
            if($status == 3){
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
                Transaction::create([
                    'user_id' => auth()->user()->id,
                    'last_amount' => $wallet->diamon,
                    'trx_amount' =>  $wd->amount,
                    'final_amount'=> $wallet->diamon +  $wd->amount,
                    'trx_type'=>'+',
                    'detail'=>'Reject Withdraw '.$request->amount.' GEMS',
                    'trx_id' => Transaction::trxID('RWD')
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success','Withdrawl '.$request->type);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error','Error: '.$th->getMessage());
        }
    }
    public function standby(){
        $data['title'] = 'Standby Withdraw';
        $data['table'] = User::where('gems','>=',120000)->where(['is_demo'=>0,'user_role'=>1])->orderByDesc('gems')->get();
        return view('admin.withdraw-standby',$data);
    }
}
