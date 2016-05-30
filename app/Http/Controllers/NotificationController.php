<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;

class NotificationController extends Controller
{
    public function getIndex(){
        return view('notification');
    }

    public function postNotify(Request $request){
        $text = $request->get('notify_text');

        $pusher = App::make('pusher');

        $pusher->trigger(
            'notifications',
            'new-notification',
            ['text' => $text ],
            $request->get('socket_id')
        );

        return $text;
    }
}
