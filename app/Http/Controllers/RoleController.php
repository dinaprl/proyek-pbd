<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
public function index()
{
    $roles = DB::table('view_master_role')
        ->select('idrole', 'nama_role')
        ->orderBy('idrole')
        ->get();

    return view('role.index', compact('roles'));
}

    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_role' => ['required', 'string', 'max:100'],
        ]);

        DB::table('role')->insert($validated);

        return redirect()
            ->route('role.index')
            ->with('success', 'Data role berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $role = DB::table('role')
            ->where('idrole', $id)
            ->first();

        abort_if(!$role, 404);

        return view('role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = DB::table('role')
            ->where('idrole', $id)
            ->first();

        abort_if(!$role, 404);

        $validated = $request->validate([
            'nama_role' => ['required', 'string', 'max:100'],
        ]);

        DB::table('role')
            ->where('idrole', $id)
            ->update($validated);

        return redirect()
            ->route('role.index')
            ->with('success', 'Data role berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $role = DB::table('role')
            ->where('idrole', $id)
            ->first();

        abort_if(!$role, 404);

        DB::table('role')
            ->where('idrole', $id)
            ->delete();

        return redirect()
            ->route('role.index')
            ->with('success', 'Data role berhasil dihapus.');
    }
}
