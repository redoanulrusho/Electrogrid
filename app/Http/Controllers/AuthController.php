<?php

namespace App\Http\Controllers;

use App\Models\Feeder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Consumer Login ───────────────────────────────────────────────────────

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login for both consumer (web guard) and admin (admin guard).
     * The login form sends a hidden 'role' field = 'consumer' | 'admin'.
     */
    public function login(Request $request)
    {
        $role = $request->input('role', 'consumer');

        if ($role === 'admin') {
            return $this->loginAdmin($request);
        }

        return $this->loginConsumer($request);
    }

    private function loginConsumer(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ], [
            'identity.required' => 'Email or Meter Number is required.',
            'password.required' => 'Pass-Key is required.',
        ]);

        $identity = $request->input('identity');

        // Find user by email OR meter_number
        $user = User::where('email', $identity)
                    ->orWhere('meter_number', $identity)
                    ->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'identity' => 'Invalid credentials. Access denied.',
            ])->withInput($request->only('identity'));
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('consumer.dashboard');
    }

    private function loginAdmin(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->input('identity'),
            'password' => $request->input('password'),
        ];

        if (!Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'identity' => 'Grid Operator credentials invalid.',
            ])->withInput($request->only('identity'));
        }

        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        // Log out whichever guard is active
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Session terminated. Node disconnected.');
    }

    // ─── Consumer Registration ────────────────────────────────────────────────

    public function showRegisterForm()
    {
        $feeders = Feeder::where('status', 'Active')
                         ->orderBy('feeder_name')
                         ->get(['id', 'feeder_name', 'substation_code']);

        return view('auth.register', compact('feeders'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'username'              => 'required|string|max:50|unique:users,username',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'required|string|max:20',
            'password'              => ['required', 'confirmed', Password::min(8)],
            'meter_number'          => 'required|string|unique:users,meter_number',
            'consumer_class'        => 'required|in:Residential,Commercial,Industrial',
            'feeder_id'             => 'required|exists:feeders,id',
            'division'              => 'required|string|max:60',
            'district'              => 'required|string|max:60',
            'upazila'               => 'required|string|max:60',
            'area'                  => 'required|string|max:100',
        ], [
            'username.unique'       => 'This username is already taken.',
            'email.unique'          => 'This email is already registered.',
            'meter_number.unique'   => 'This meter number is already mapped to another consumer.',
            'feeder_id.exists'      => 'Selected feeder is not valid.',
        ]);

        User::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'password'       => Hash::make($request->password),
            'meter_number'   => $request->meter_number,
            'consumer_class' => $request->consumer_class,
            'feeder_id'      => $request->feeder_id,
            'division'       => $request->division,
            'district'       => $request->district,
            'upazila'        => $request->upazila,
            'area'           => $request->area,
            'role'           => 'consumer',
        ]);

        return redirect()->route('login')
                         ->with('success', 'Terminal Node mapped successfully to Feeder Node! You may now authenticate.');
    }
}
