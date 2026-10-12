<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Http\Controllers\CartController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    public function login(): View
    {
        return view('frontend.storefront-auth', ['mode' => 'login']);
    }

    public function register(): View
    {
        return view('frontend.storefront-auth', ['mode' => 'register']);
    }

    public function storeLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $guestSessionId = $request->session()->getId();

        if (! Auth::attempt($credentials) || $request->user()?->role !== 'customer') {
            Auth::logout();

            return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
        }

        CartController::mergeGuestCart($guestSessionId, $request->user()->id);
        $request->session()->regenerate();

        return redirect()->intended(route('account'));
    }

    public function storeRegistration(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:12|confirmed',
        ]);
        $guestSessionId = $request->session()->getId();

        $user = DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'password' => $data['password'],
                'role' => 'customer',
            ]);

            Customer::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $user->email,
                'phone' => $data['phone'],
            ]);

            return $user;
        });

        Auth::login($user);
        CartController::mergeGuestCart($guestSessionId, $user->id);
        $request->session()->regenerate();

        return redirect()->route('account');
    }

    public function account(Request $request): View
    {
        $user = $request->user();
        $customer = Customer::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name, 'email' => $user->email],
        );
        $orders = $customer->orders()->with('items')->latest()->paginate(10);

        return view('frontend.storefront-account', compact('customer', 'orders'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user?->role === 'customer') {
            CartController::mergeGuestCart($request->session()->getId(), $user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}