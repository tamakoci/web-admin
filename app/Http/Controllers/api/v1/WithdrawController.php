<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\TopupDiamon;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBank;
use App\Models\UserWallet;
use App\Models\Withdrawl;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->url      = env('KPAYDEVURL');
        $this->app      = env('KPAYAPP');
        $this->pass     = env('KPAYPASS');
        $this->mail     = env('KPAYEMAIL');
    }

    public function bankList(){
        $data = Bank::all();
        return response()->json(['status'=>200,'message'=>'Bank List','data'=>$data],200);
    }
    
    public function wdLog(){
        $user = Auth::user();
        $data = Payment::where(['mark'=>'WD','user_id'=>$user->id])->orderByDesc('id')->get();
        return response()->json(['status'=>200,'msg'=>'Withdrawal inquiry','data'=>$data]);

    }

    public function userBank(Request $request){
        $validate = Validator::make($request->all(),[
            'bank_id'       => 'required',
            'account_name'  => 'required',
            'account_number'=> 'required',
            'bank_city'     => 'required'   
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
        $user = Auth::user();
        try {
            UserBank::create([
                'user_id'       => $user->id,
                'bank_id'       => $request->bank_id,
                'account_name'  => $request->account_name,
                'account_number'=> (string)$request->account_number,
                'bank_city'     => $request->bank_city
            ]);
            return response()->json(['status'=>200,'message'=>'User Bank Created'],200);
        } catch (QueryException $e) {
            return response()->json(['status'=>500,'message'=>'Created User Bank Failed','errors'=>$e->getMessage()],500);
        }
    }
    public function wdq($id){
         $data=[
            'merchantAppCode' => $this->app,
            'merchantAppPassword' => $this->pass,
            'withdrawalNo'  => $id,
            
        ];
        $res = $this->send($this->url.'merchant-withdrawal-inquiry.php',json_encode($data));
        return response()->json(['status'=>200,'message'=>'Withdrawal in proceess','data'=>json_decode($res,true)]);
    }

    public function userBankFind(Request $request){
        $user = Auth::user();
        $bank = UserBank::with(['bank'])->where('user_id',$user->id)->get();
        $data = [];
        foreach ($bank as $key => $value) {
            $data[] = [
                'id'    => $value->id,
                'bank_id'=> $value->bank_id,
                'bank_code' => $value->bank->name,
                'bank_name' => $value->bank->code,
                'account_name'=> $value->account_name,
                'account_number'=>$value->account_number,
                'bank_city' => $value->bank_city
            ];
        }
        return response()->json(['status'=>200,'message'=>'Data Bank '.$user->username,'data'=>$data],200);
    }
    public function wd(Request $request){
       
        $user = User::find(11);
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        $diamon = 1000;
        // if($request->diamon > $diamon){
        //     return response()->json(['status'=>'401','message'=>'Not Enough Diamonds',],401);
        // }
        $userbanks = UserBank::with('bank')->where('id',1)->first();
        $data=[
            'merchantAppCode' => $this->app,
            'merchantAppPassword' => $this->pass,
            'withdrawalNo'  => Transaction::trxID('WD'),
            'accountName'   => $userbanks->account_name,
            'accountNo'     => $userbanks->account_number,
            'bankCode'      => $userbanks->bank->name,
            'bankName'      => $userbanks->bank->code,
            'bankBranch'    => 'Asia',
            'bankCity'      => $userbanks->bank_city,
            'requestAmount' => $diamon * 100,
            'additionalMsg' => 'Tamakci Wthdrawl ' .$diamon. ' diamon = ' . $diamon * 100 .'IDR',
            'processURL'    => url('proccess')
        ];
        $res = $this->send($this->url.'merchant-withdrawal.php',json_encode($data));
        return response()->json(['status'=>200,'message'=>'Withdrawal in proceess','data'=>json_decode($res,true)]);
    }

    // public function withdraw(Request $request){
    //     $validate = Validator::make($request->all(),[
    //         'diamon'        => 'required|numeric|min:1000',
    //         'user_bank_id'  => 'required',
    //     ]);
    //     if($validate->fails()){
    //         return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
    //     }
    //     $user = Auth::user();
    //     $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
    //     $diamon = $wallet->diamon;
    //     if($request->diamon > $diamon){
    //         return response()->json(['status'=>'401','message'=>'Not Enough Diamonds',],401);
    //     }
    //     $userbanks = UserBank::with('bank')->where('id',$request->user_bank_id)->first();
    //     $data=[
    //         'merchantAppCode' => $this->app,
    //         'merchantAppPassword' => $this->pass,
    //         'withdrawalNo'  => Transaction::trxID('WD'),
    //         'accountName'   => $userbanks->account_name,
    //         'accountNo'     => $userbanks->account_number,
    //         'bankCode'      => $userbanks->bank->name,
    //         'bankName'      => $userbanks->bank->code,
    //         'bankBranch'    => 'Asia',
    //         'bankCity'      => $userbanks->bank_city,
    //         'requestAmount' => $request->diamon * 100,
    //         'additionalMsg' => 'Tamakci Wthdrawl ' .$request->diamon. ' diamon = ' . $request->diamon * 100 .'IDR',
    //         'processURL'    => url('proccess')
    //     ];
    //     $res = $this->send($this->url.'merchant-withdrawal.php',json_encode($data));
    //     $arr = json_decode($res,true);
    //     if($arr['success'] == 1){
    //         Payment::create([
    //             'user_id'   => $user->id,
    //             'mark'      => 'WD',
    //             'order_no'  => $arr['result']['withdrawalNo'],
    //             'amount'    => $arr['result']['transferAmount'],
    //             'diamon'    => $request->diamon,
    //             'fee'       => $arr['result']['feeAmount'],
    //             'desc'      => 'Withdrawal '.$request->diamon.' Diamon',
    //             'expired'   => null,
    //             'checkout_url'=>'-',
    //             'pay_method'=> 'Transfer Bank '. $arr['result']['bankName'] .' A/N : '. $arr['result']['accountName'],     
    //             'status'    => 2
    //         ]);
    //         UserWallet::create([
    //                 'user_id'=>$user->id,
    //                 'diamon'=>$wallet->diamon - $request->diamon,
    //                 'pakan'=>$wallet->pakan,
    //                 'hasil_ternak'=>$wallet->hasil_ternak
    //         ]);
    //         Transaction::create([
    //             'user_id' => $user->id,
    //             'last_amount' => $wallet->diamon,
    //             'trx_amount' => $request->diamon,
    //             'final_amount'=> $wallet->diamon - $request->diamon,
    //             'trx_type'=>'-',
    //             'detail'=>'Withdrawal Diamon',
    //             'trx_id' => $arr['result']['withdrawalNo']
    //         ]);
    //         return response()->json([
    //             'status'=> 200,
    //             'msg'   => 'Withdrawl Success',
    //             'data'  =>  [
    //                 'trxID'     => $arr['result']['withdrawalNo'],
    //                 'amount'    => $arr['result']['transferAmount'],
    //                 'diamon'    => $request->diamon,
    //                 'fee'       => $arr['result']['feeAmount'],
    //                 'desc'      => 'Withdrawal '.$request->diamon.' Diamon',
    //                 'method'=> 'Transfer Bank '. $arr['result']['bankName'] .' A/N : '. $arr['result']['accountName'],
    //             ]
    //         ],200);
    //     }else{
    //         return response()->json(['status'=>500,'msg'=>'Transaction Failed','errors'=>$arr],500);
    //     }     
    // }
    public function withdraw(Request $request){
         $validate = Validator::make($request->all(),[
            'amount'        => 'required|numeric|min:1000',
        ]);
        if($validate->fails()){
            return response()->json(['status'=>'401','message'=>'Validation Error','errors'=>$validate->getMessageBag()],401);
        }
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

    public function send($url,$data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_POSTFIELDS      => $data,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    
}
