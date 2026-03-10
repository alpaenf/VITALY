<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminKaderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kaders = User::where('role', 'kader')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Kaders', [
            'kaders'  => $kaders,
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        // No password — kader authenticates via Google OAuth
        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make(\Illuminate\Support\Str::random(32)),
            'role'     => 'kader',
        ]);

        return back()->with('success', 'Akun kader berhasil didaftarkan. Kader dapat login menggunakan Google.');
    }

    public function destroy(User $user)
    {
        if (!$user->isKader()) {
            return back()->withErrors(['error' => 'Bukan akun kader.']);
        }

        $user->delete();
        return back()->with('success', 'Akun kader berhasil dihapus.');
    }
}
