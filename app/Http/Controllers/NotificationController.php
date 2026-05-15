<?php

namespace App\Http\Controllers;

use App\Services\RentalNotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(private readonly RentalNotificationService $rentalNotifications) {}

    public function index()
    {
        $this->rentalNotifications->createDueNotificationsFor(Auth::user());

        $notifications = Auth::user()->notifications()->with('related')->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back();
    }
}
