<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Engineer;
use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function login()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();

        if ($this->canLogin($githubUser)) {
            $user = User::firstWhere('email', $githubUser->email);

            if ($user) {
                $user->update([
                    'avatar' => $githubUser->avatar,
                ]);
            } else {
                $user = User::create([
                    'email' => $githubUser->email,
                    'name' => $githubUser->name,
                    'avatar' => $githubUser->avatar,
                    'role' => UserRole::Engineer,
                ]);

                $identity = [
                    'source' => 'github',
                    'source_id' => $githubUser->id,
                    'source_email' => $githubUser->email,
                    'context' => [
                        'username' => $githubUser->nickname,
                    ],
                ];

                $user->identities()->create($identity);

                if ($user->hasEngineer()) {
                    $user->engineer->identities()->create($identity);
                }
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        }

        return redirect('/')->with('error', __('No es posible iniciar tu sesión. Contacta a tu manager.'));
    }

    protected function canLogin($githubUser)
    {
        if (User::where('email', $githubUser->email)->exists()) {
            return true;
        }

        if (Engineer::where('email', $githubUser->email)->exists()) {
            return true;
        }

        return false;
    }
}
