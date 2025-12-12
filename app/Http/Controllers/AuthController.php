<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required',
        'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    // Try to authenticate with email first
    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        return redirect()->intended('/home');
    }

    // If email login fails, try username
    $credentials = ['username' => $request->email, 'password' => $request->password];
    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->withInput();
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister()
    {
        $organizations = Organization::where('status', 1)->get();
        return view('register', compact('organizations'));
    }

    public function register(Request $request)
    {
        // Create validator with conditional rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'icno' => 'required|string|max:20|unique:users',
            'telno' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'postcode' => 'required|string|max:10',
            'state' => 'required|string|max:100',
            'password' => 'required|min:6|confirmed',
            'organization_option' => 'required|in:skip,join',
            'terms' => 'required|accepted',
        ]);

        // Add conditional rule for organization_code only when joining
        if ($request->organization_option === 'join') {
            $validator->addRules([
                'organization_code' => 'required|string|size:6|exists:organizations,code',
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle organization assignment
        $organization_id = null;
        $is_admin = false;

        if ($request->organization_option === 'join') {
            $organization = Organization::where('code', $request->organization_code)->first();

            if (!$organization) {
                return back()->withErrors(['organization_code' => 'Invalid organization code.'])->withInput();
            }

            $organization_id = $organization->id;
            $is_admin = false;
        }
        // If skip, organization_id remains null and is_admin remains false

        // Create user with all required fields (no role column)
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'icno' => $request->icno,
            'telno' => $request->telno,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'state' => $request->state,
            'password' => Hash::make($request->password),
            'organization_id' => $organization_id,
            'is_admin' => $is_admin,
            'created_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/home')->with('success', 'Account created successfully!');
    }

    /**
     * Show form for creating first organization (optional - for initial setup)
     */
    public function showFirstTimeSetup()
    {
        return view('setup');
    }

    /**
     * Handle first organization creation (optional - for initial setup)
     */
    public function firstTimeSetup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'icno' => 'required|string|max:20|unique:users',
            'telno' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'postcode' => 'required|string|max:10',
            'state' => 'required|string|max:100',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create organization
        $organization = Organization::create([
            'name' => $request->organization_name,
            'code' => $this->generateUniqueOrganizationCode(),
            'status' => 1,
        ]);

        // Create admin user with all fields (no role)
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'icno' => $request->icno,
            'telno' => $request->telno,
            'address' => $request->address,
            'postcode' => $request->postcode,
            'state' => $request->state,
            'password' => Hash::make($request->password),
            'organization_id' => $organization->id,
            'is_admin' => true,
            'created_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Organization setup completed successfully!');
    }

    /**
     * Generate unique organization code
     */
    private function generateUniqueOrganizationCode()
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (Organization::where('code', $code)->exists());

        return $code;
    }
}
