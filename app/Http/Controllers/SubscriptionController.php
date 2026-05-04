<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmedMail;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        // Must be logged in as reader
        $reader = Auth::guard('reader')->user();
        if (! $reader) {
            return redirect()->route('login');
        }

        $email = $reader->email;

        $existing = Subscription::query()
            ->where('email', $email)
            ->first();

        if ($existing && $existing->is_active) {
            return back()->with('success', 'Kamu sudah berlangganan newsletter kami.');
        }

        $subscription = Subscription::query()->updateOrCreate(
            ['email' => $email],
            [
                'is_active'       => true,
                'subscribed_at'   => now(),
                'unsubscribed_at' => null,
            ],
        );

        Mail::to($subscription->email)->queue(new SubscriptionConfirmedMail($subscription));

        return back()->with('success', 'Langganan berhasil! Cek email kamu untuk konfirmasi.');
    }

    public function unsubscribe(string $token): Response
    {
        $subscription = Subscription::query()
            ->where('token', $token)
            ->firstOrFail();

        $subscription->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);

        return Inertia::render('Portal/UnsubscribeConfirmation');
    }
}
