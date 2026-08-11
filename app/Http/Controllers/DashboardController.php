<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $urls = ShortUrl::with(['company', 'user'])->latest();
        $invitations = Invitation::with(['company', 'inviter'])->latest();

        if ($user->isAdmin()) {
            $urls->where('company_id', $user->company_id);
            $invitations->where('company_id', $user->company_id);
        }

        if ($user->isMember()) {
            $urls->where('user_id', $user->id);
            $invitations->whereRaw('1 = 0');
        }

        return view('dashboard', [
            'urls' => $urls->get(),
            'invitations' => $invitations->get(),
        ]);
    }
}
