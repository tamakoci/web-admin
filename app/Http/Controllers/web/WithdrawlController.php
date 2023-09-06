<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use App\Models\Withdrawl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
