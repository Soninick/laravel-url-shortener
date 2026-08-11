<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShortUrlController extends Controller
{
    public function create(Request $request): View
    {
        abort_if($request->user()->isSuperAdmin(), 403);

        return view('short_urls.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user->isSuperAdmin(), 403);

        $data = $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $data['original_url'],
            'code' => $this->makeCode(),
        ]);

        return redirect()->route('dashboard')->with('status', 'Short URL created successfully.');
    }

    public function show(Request $request, ShortUrl $shortUrl): View
    {
        $user = $request->user();

        abort_if($user->isAdmin() && $shortUrl->company_id !== $user->company_id, 403);
        abort_if($user->isMember() && $shortUrl->user_id !== $user->id, 403);

        return view('short_urls.show', ['shortUrl' => $shortUrl->load(['company', 'user'])]);
    }

    public function redirect(string $code): RedirectResponse
    {
        $shortUrl = ShortUrl::where('code', $code)->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }

    private function makeCode(): string
    {
        do {
            $code = Str::random(6);
        } while (ShortUrl::where('code', $code)->exists());

        return $code;
    }
}
