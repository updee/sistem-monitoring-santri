<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($x) use ($term) {
                    $x->where('name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%');
                });
            })
            ->when($request->filled('role'), fn($q) => $q->where('role', $request->role))
            ->latest()
            ->paginate(15);

        return view('admin.users.index', ['users' => $users->withQueryString()]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,ustadz,wali_santri'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $data['email'] = strtolower(trim($data['email']));
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id],
            'role'     => ['required', 'in:admin,ustadz,wali_santri'],
            'is_active'=> ['sometimes', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $data['email'] = strtolower(trim($data['email']));
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => ! (bool) $user->is_active]);
        return redirect()->back()->with('success', 'Status user berhasil diubah.');
    }
}

