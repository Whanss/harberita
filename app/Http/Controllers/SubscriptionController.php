<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmedMail;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.email' => 'Format email tidak valid.',
        ]);

        $existing = Subscription::query()
            ->where('email', $validated['email'])
            ->first();

        if ($existing && $existing->is_active) {
            return back()->withErrors([
                'email' => 'Email ini sudah terdaftar sebagai pelanggan.',
            ]);
        }

        $subscription = Subscription::query()->updateOrCreate(
            ['email' => $validated['email']],
            [
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ],
        );

        Mail::to($subscription->email)->send(new SubscriptionConfirmedMail($subscription));

        return back()->with('success', 'Langganan berhasil. Cek email Anda untuk konfirmasi.');
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
