<?php

namespace App\Http\Controllers;

use Auth;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        Auth::user()->hasRole(['manager', 'mechanic']);

        $notifications = Notification::where([
            'target_user_id' => Auth::id(),
            'is_read' => 0
        ])->get();

        $content = '';
        if(count($notifications) > 0) {
            foreach($notifications as $notification){
                $content .=
                '<a class="dropdown-item border-bottom" href="/notification/'.$notification->id.'/read">
                    '.Str::words($notification->message, 10, '...').'
                </a>';
            }
            $content .=
            '<a class="d-block text-center text-primary btn btn-sm" onclick="read_all()">Tandai semua telah dibaca</a>';
        } else {
            $content .= '<span class="dropdown-item">Tidak ada pemberitahuan baru</span>';
        }

        $output = array(
            'content' => $content,
            'count' => count($notifications) > 0 ? count($notifications) : ''
        );
        return $output;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function read(Notification $notification)
    {
        Auth::user()->hasRole(['manager', 'mechanic']);

        $notification->is_read = 1;
        $notification->save();

        return redirect($notification->redirect);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function read_all()
    {
        Auth::user()->hasRole(['manager', 'mechanic']);

        Notification::where('target_user_id', Auth::id())->update(['is_read' => 1]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function delete(Notification $notification)
    {
        //
    }
}
