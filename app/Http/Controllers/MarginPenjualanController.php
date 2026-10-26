<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MarginPenjualanController extends Controller
{

    public function index()
    {
    // Memanggil Stored Procedure dari database MySQL
        $margins = DB::select('CALL sp_tampil_margin_penjualan()');

    // Mengirim hasil SP ke halaman daftar margin
        return view('margin_penjualan.index', compact('margins'));
    }


    public function create()
    {
        $users = DB::table('user')
            ->orderBy('username')
            ->get();

        return view('margin_penjualan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'persen' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'integer', 'in:0,1'],
            'iduser' => ['required', 'integer', 'exists:user,iduser'],
        ]);

        $validated['created_at'] = Carbon::now();
        $validated['updated_at'] = null;

        DB::table('margin_penjualan')->insert($validated);

        return redirect()
            ->route('margin_penjualan.index')
            ->with('success', 'Data margin penjualan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $margin = DB::table('margin_penjualan')
            ->where('idmargin_penjualan', $id)
            ->first();

        abort_if(!$margin, 404);

        $users = DB::table('user')
            ->orderBy('username')
            ->get();

        return view('margin_penjualan.edit', compact('margin', 'users'));
    }

    public function update(Request $request, $id)
    {
        $margin = DB::table('margin_penjualan')
            ->where('idmargin_penjualan', $id)
            ->first();

        abort_if(!$margin, 404);

        $validated = $request->validate([
            'persen' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'integer', 'in:0,1'],
            'iduser' => ['required', 'integer', 'exists:user,iduser'],
        ]);

        $validated['updated_at'] = Carbon::now();

        DB::table('margin_penjualan')
            ->where('idmargin_penjualan', $id)
            ->update($validated);

        return redirect()
            ->route('margin_penjualan.index')
            ->with('success', 'Data margin penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $margin = DB::table('margin_penjualan')
            ->where('idmargin_penjualan', $id)
            ->first();

        abort_if(!$margin, 404);

        DB::table('margin_penjualan')
            ->where('idmargin_penjualan', $id)
            ->delete();

        return redirect()
            ->route('margin_penjualan.index')
            ->with('success', 'Data margin penjualan berhasil dihapus.');
    }
}

