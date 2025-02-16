<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileCompleteRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Rules\CepRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function completeProfile(Request $request)
    {
        $user_id = auth()->user()->id;
        $user =auth()->user();
        return view('profile.complete-profile', [
            'user' => $user,
        ]);
    }

    public function updateCpfCnpj(ProfileCompleteRequest $request)
    {
        $user = User::find(auth()->user()->id);
        $user->cpf_cnpj = $request->get('cpf_cnpj');
        $user->save();

        if (!$user->address) {
            $user->address()->create([
                'postal_code' => $request->get('postalCode'),
                'address_number' => $request->get('addressNumber'),
                'address_complement' => $request->get('addressComplement'),
                'phone' => $request->get('phone')
            ]);
        } else {
            $user->address->postal_code = $request->get('postalCode');
            $user->address->address_number = $request->get('addressNumber');
            $user->address->address_complement = $request->get('addressComplement');
            $user->address->phone = $request->get('phone');
            $user->address->save();
        }

        return redirect(route('cart'));
    }
}
