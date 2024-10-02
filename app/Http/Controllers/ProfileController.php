<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Adatbázis frissítése
        $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
        ]);    

        $request->user()->fill($request->validated());

        
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        $user = User::where('email', $request->email)->first();

        // Adatok mentése a fájlrendszerbe (Storage)
        $fileName = 'users/' . $user->id . '.json'; // Egyedi fájlnév felhasználó azonosító alapján
        $row = [
            'name' =>  $user->name,
            'email' => $user->email,
            'birthdate' => $user->birthdate,
            'password' => $user->password,
            'email_verified_at' => $user->email_verified_at,
            'remember_token' => $user->remember_token,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
        Storage::disk('local')->put($fileName, json_encode($row, JSON_PRETTY_PRINT));        

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        // Fájl törlése felhasználói ID alapján
        $fileName = 'users/' . $user->id . '.json';
        if (Storage::exists($fileName)) {
            Storage::delete($fileName);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
