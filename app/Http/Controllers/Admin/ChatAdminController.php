<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\Chat;
use App\Traits\CustomLogTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ChatAdminController extends Controller
{
    use CustomLogTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unanswereds = Chat::where('status', '0')->orderBy('id', 'desc')->paginate(9);
        $answereds   = Chat::where('status', '1')->orderBy('id', 'desc')->paginate(9);
        $closeds     = Chat::where('status', '2')->orderBy('id', 'desc')->paginate(9);

        return view('admin.support.support', compact('unanswereds', 'answereds', 'closeds'));
    }

    public function indexChange()
    {
        $unanswereds = ChatMessage::where('status', '0')->where('title', 'Wallet Change Request')->orderBy('id', 'desc')->paginate(9);

        $answereds = ChatMessage::where('status', '1')->where('title', 'Wallet Change Request')->orderBy('id', 'desc')->paginate(9);

        $closeds = ChatMessage::where('status', '2')->where('title', 'Wallet Change Request')->orderBy('id', 'desc')->paginate(9);

        return view('admin.support.changeWallet', compact('unanswereds', 'answereds', 'closeds'));
    }

    public function answerChat($id)
    {
        $messages = Chat::where('id', $id)->where('status', '!=', '2')->get();

        return view('admin.support.answerChat', compact('messages'));
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

    public function createMessage(Request $request)
    {
        DB::table('message')->insert([
            "chat_id" => $request->chat_id,
            "user_id" => auth()->user()->id,
            "text" => $request->text,
            "date" => date('Y-m-d H:i:s')
        ]);

        DB::table('chat')->where("id", $request->chat_id)->update(['status' => 1]);

        return redirect()->route('admin.support');
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

    public function closeChat($id)
    {
        DB::table('chat')->where("id", $id)->update(['status' => 2]);

        return redirect()->route('admin.support');
    }

    public function reopenChat($id)
    {
        DB::table('chat')->where("id", $id)->update(['status' => 0]);

        return redirect()->route('admin.support');
    }

    public function changeWallet($id)
    {
        try {
            DB::table('chat')->where("id", $id)->update(['status' => 2]);

            $user = DB::table('chat')->where("id", $id)->first();

            $wallets = DB::table('wallets')->where("user_id", $user->user_id)->get();

            foreach ($wallets as $wallet) {
                $this->createLog('Wallet deleted successfully -> ' . $wallet->wallet . ' From user -> ' . $wallet->user_id, 200, 'success', auth()->user()->id);
                DB::table('wallets')->where("id", $wallet->id)->delete();
            }

            return redirect()->route('admin.indexChange');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            Alert::error($e->getMessage());
            return redirect()->route('admin.indexChange');
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
}
