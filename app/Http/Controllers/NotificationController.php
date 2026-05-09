<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back();
    }
}
