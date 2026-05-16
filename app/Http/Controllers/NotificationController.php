<?php

namespace App\Http\Controllers;

use App\Services\RentalNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function __construct(private readonly RentalNotificationService $rentalNotifications) {}

    public function index(Request $request)
    {
        $this->rentalNotifications->createDueNotificationsFor(Auth::user());

        $validated = $request->validate([
            'filter' => ['nullable', Rule::in(['all', 'unread', 'requests', 'approved', 'pending', 'rejected', 'ratings'])],
        ]);

        $filter = $validated['filter'] ?? 'all';
        $query = Auth::user()->notifications()->with('related')->latest();

        match ($filter) {
            'unread' => $query->where('is_read', false),
            'requests' => $query->whereIn('type', ['request', 'message', 'meetup']),
            'approved' => $query->whereIn('type', ['approval', 'completion']),
            'pending' => $query->whereIn('type', ['request', 'rental_due_soon', 'rental_due', 'payment_proof']),
            'rejected' => $query->whereIn('type', ['rejection', 'rental_overdue']),
            'ratings' => $query->where('type', 'rating'),
            default => null,
        };

        $notifications = $query->get();
        $groupedNotifications = [
            'Today' => $notifications->filter(fn ($notification) => $notification->created_at->isToday()),
            'Earlier' => $notifications->reject(fn ($notification) => $notification->created_at->isToday()),
        ];

        return view('notifications.index', compact('notifications', 'groupedNotifications', 'filter'));
    }

    public function markAllRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return back();
    }
}
