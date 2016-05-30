<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{

    protected $pusher;

    protected $user;

    protected $channel;

    const DEFAULT_CHAT_CHANNEL = 'private-chat';


    function __construct()
    {
        $this->pusher  = App::make('pusher');
        $this->user    = Session::get('user');
        $this->channel = self::DEFAULT_CHAT_CHANNEL;
    }


    public function getIndex()
    {
        if ( ! $this->user) {
            return redirect('auth/github?redirect=/chat');
        }

        return view('chat', [ 'chatChannel' => $this->channel ]);
    }


    public function postMessage(Request $request)
    {
        $message = [
            'text'      => $request->get('chat_text'),
            'username'  => $this->user->getNickname(),
            'avatar'    => $this->user->getAvatar(),
            'timestamp' => (time()*1000)
        ];

        $this->pusher->trigger($this->channel, 'new-message', $message);
    }

    public function postAuth(Request $request)
    {
        $channel = $request->get('channel_name');
        $socket_id = $request->get('socket_id');


        $auth = $this->pusher->socket_auth($channel, $socket_id);

        return $auth;
    }

}
