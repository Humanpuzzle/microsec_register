<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // dd($request->user()->password);
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Adatok frissitese a fájlrendszerbe
        $user = User::where('email', $request->user()->email)->first();
        $fileName = 'users/' . $user->id . '.json';
        $userData = json_decode(Storage::get($fileName), true);
        $userData['password'] = $user->password;
        Storage::put($fileName, json_encode($userData, JSON_PRETTY_PRINT));

        return back();
    }
}
