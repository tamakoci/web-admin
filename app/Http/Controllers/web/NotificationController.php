<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notif;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Notification';
        $data['table'] = Notif::with(['user'])->get();
        $data['user'] = User::where('user_role',1)->get();
        // dd($data);
        return view('masterdata.notification',$data);
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
}
