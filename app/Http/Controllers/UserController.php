<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('view_master_user')
            ->select([
                'iduser',
                'username',
                'nama_role',
            ])
            ->orderBy('iduser')
            ->get();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                'unique:user,username',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
            'idrole' => [
                'required',
                'integer',
                'exists:role,idrole',
            ],
        ]);

        $validated['password'] = Hash::make(
            $validated['password']
        );

        User::create($validated);

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('user', 'username')
                    ->ignore($user->iduser, 'iduser'),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
            'idrole' => [
                'required',
                'integer',
                'exists:role,idrole',
            ],
        ]);

        $user->username = $validated['username'];
        $user->idrole = $validated['idrole'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make(
                $validated['password']
            );
        }

        $user->save();

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
