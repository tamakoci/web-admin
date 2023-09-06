<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Product;
use App\Models\ReferalTree;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(){
        $data['title'] = 'Profile';
        $data['user'] = Auth::user();
        $data['referrals'] = ReferalTree::referalGroup();
        $wallet = UserWallet::where('user_id',auth()->user()->id)->orderByDesc('id')->first();
        $produk = Product::produk();
        // return $produk;
        if($wallet){
            $userWallet = [
                'diamond'=>$wallet->diamon,
                'pakan'=>$wallet->pakan,
                'hasil_ternak'=>$wallet->hasilTernak()
            ];
        }else{
            $userWallet = [
                'diamond'=>0,
                'pakan'=>0,
                'hasil_ternak'=> $produk
                ];
        }
        $data['wallet']= $userWallet;
        $data['table'] = Transaction::where('user_id',auth()->user()->id)->orderByDesc('id')->get();
        return view('user.user-profile',$data);
    }
    public function getUser($id){
        $user= User::find($id);
        $data['title'] = 'Profile '.ucwords($user->username);
        $data['user'] = $user;
        $data['mark'] = true;
        $data['referrals'] = ReferalTree::referalGroupDetail($id);
        $wallet = UserWallet::where('user_id',$user->id)->orderByDesc('id')->first();
        $produk = Product::produk();
        // return $produk;
        if($wallet){
            $userWallet = [
                'diamond'=>$wallet->diamon,
                'pakan'=>$wallet->pakan,
                'hasil_ternak'=>$wallet->hasilTernak()
            ];
        }else{
            $userWallet = [
                'diamond'=>0,
                'pakan'=>0,
                'hasil_ternak'=> $produk
                ];
        }
        $data['wallet']= $userWallet;
        $hasil_ternak = json_decode($wallet->hasil_ternak);
        $array = (array)$hasil_ternak;
        $productInWallet = $array[1]->qty;
        $data['diamon'] = $wallet->diamon;
        $data['pakan'] = $wallet->pakan;
        $data['vaksin'] = $wallet->vaksin;
        $data['tools'] = $wallet->tools;
        $data['telur'] = $productInWallet;
        $data['table'] = Transaction::where('user_id',$user->id)->orderByDesc('id')->get();
        return view('user.user-detail',$data);
    }
    public function refGenerate(){
        $ref = User::makeReferal();
        $user = Auth::user();
        $user->user_ref = $ref;
        $user->save();
        return redirect()->back()->with('success','User Referal Genarate');
    }

    public function updateUser(Request $request,$id){
        $user = Auth::user();
        if(isset($request->image)){
                $file = $request->file('image');
                $filename = time(). "." . $file->getClientOriginalExtension();
                $location = "public/files/images/";
                
                $file->move($location,$filename);
            
                $filepath =  asset('')  . "files/images/".$filename;
                $user->avatar = $filepath;
        }
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        return redirect()->back()->with('success','User Updated');

    }
    public function test2(){
        $tgl1 = time(); //1233131231231
        $tgl2 = "2022-10-1 12:10:32";
        $tgl3 = strtotime($tgl2); //132321321212
        // var_dump();
        var_dump('tgl_hbs ='. date('d-m-y h:i:s',time()));
        var_dump('tgl_beli='. date('d-m-y h:i:s',$tgl3));
        // dd($tgl1);
        // dd($tgl2);
    }
    public function bankAcc(){
        $user = Auth::user();
        $data['title'] = 'Bank Account';
        $data['user']= $user;
        $apiUrl = 'https://masterplan.co.id/api/rekening-info/'.$user->username;
        $response = Http::get($apiUrl);
        if ($response->successful()) {
            $rs = $response->json();
            $data['acc'] = $rs['data'];
        }else{
            $data['acc'] =[
                "nama_bank" => null,
                "nama_akun" => null,
                "no_rek" => null,
                "kota_cabang" => null,
            ];
        }
        return view('user.bank-acc',$data);
    }
    
    
}
