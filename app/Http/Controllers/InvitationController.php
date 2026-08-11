<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function create(Request $request): View
    {
        abort_if($request->user()->isMember(), 403);

        return view('invitations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user->isMember(), 403);

        $roles = $user->isSuperAdmin()
            ? [User::ROLE_ADMIN]
            : [User::ROLE_ADMIN, User::ROLE_MEMBER];

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:invitations,email'],
            'role' => ['required', Rule::in($roles)],
            'password' => ['required', 'string', 'min:8'],
        ];

        if ($user->isSuperAdmin()) {
            $rules['company_name'] = ['required', 'string', 'max:255', 'unique:companies,name'];
        }

        $data = $request->validate($rules);

        $company = $user->isSuperAdmin()
            ? Company::create(['name' => $data['company_name']])
            : $user->company;

        Invitation::create([
            'company_id' => $company->id,
            'invited_by' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => $data['password'],
        ]);

        return redirect()->route('dashboard')->with('status', 'User invited successfully.');
    }
}
