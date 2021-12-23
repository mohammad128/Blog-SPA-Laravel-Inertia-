<?php
namespace App\FlashMessage;

use Illuminate\Support\Facades\Session;

class FlashMessage {

    public function getAllMessages() {
        $msg = Session::get('messages');
        Session::forget('messages');
        return $msg;
    }

    /*
     * type= notification, alert
     * position = bottom-right, top-right, top-center, top-left, bottom-left, bottom-center
     * color = primary, success, danger, warning, dark, RGB, HEX
     * */

    public function custom($title, $msg, $color= 'primary', $icon='bx bx-bell', $time_out='', $position='top-right', $type ='notification') {

        $messages = collect(Session::get('messages'));
        $messages->push([
            'title'=>$title,
            'msg'=>$msg,
            'color'=>$color,
            'icon'=>$icon,
            'time_out'=>$time_out,
            'position'=>$position,
            'type'=>$type,
            'message_time'=>now()
        ]);
        Session::put("messages", ( $messages ) );

//        $messages = json_decode( Session::get('messages','[]') );
//        if(!$messages) $messages = [];
//        array_push($messages, [
//            'title'=>$title,
//            'msg'=>$msg,
//            'color'=>$color,
//            'icon'=>$icon,
//            'time_out'=>$time_out,
//            'position'=>$position,
//            'type'=>$type
//        ]);
//        Session::flash("messages", json_encode( $messages ));
        return $this;
    }

    public function error( $title, $message ) {
        self::custom( $title, $message, 'danger', 'bxs-bug' );
        return $this;
    }

    public function success( $title, $message ) {
        $this->custom( $title, $message, 'success', 'bxs-message-square-check' );
        return $this;
    }

    public function warning( $title, $message ) {
        $this->custom( $title, $message, 'warn', 'bxs-message-square-error' );
        return $this;
    }

    public function normal( $title, $message ) {
        $this->custom( $title, $message, 'bx-bell', 'bxs-bell' );
        return $this;
    }


}
