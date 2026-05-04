<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Skip this middleware for Livewire and Filament requests.
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $path = $request->path();

        if (
            str($path)->startsWith('admin') ||
            str($path)->contains('livewire') ||
            $request->hasHeader('X-Livewire')
        ) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return array_merge(parent::share($request), [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
                'reader' => Auth::guard('reader')->user(),
            ],
            'categories' => fn () => Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
            'csrf_token' => csrf_token(),
            'isSubscribed' => fn () => Auth::guard('reader')->check()
                ? Subscription::where('email', Auth::guard('reader')->user()->email)
                    ->where('is_active', true)
                    ->exists()
                : false,
        ]);
    }
}
