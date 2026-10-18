<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = DB::table('view_master_satuan')
            ->select([
                'idsatuan',
                'nama_satuan',
                'status_deskripsi',
            ])
            ->orderBy('idsatuan')
            ->get();

        return view('satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_satuan' => ['required', 'string', 'max:100'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        DB::table('satuan')->insert($validated);

        return redirect()
            ->route('satuan.index')
            ->with('success', 'Data satuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $satuan = DB::table('satuan')
            ->where('idsatuan', $id)
            ->first();

        abort_if(!$satuan, 404);

        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $satuan = DB::table('satuan')
            ->where('idsatuan', $id)
            ->first();

        abort_if(!$satuan, 404);

        $validated = $request->validate([
            'nama_satuan' => ['required', 'string', 'max:100'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        DB::table('satuan')
            ->where('idsatuan', $id)
            ->update($validated);

        return redirect()
            ->route('satuan.index')
            ->with('success', 'Data satuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $satuan = DB::table('satuan')
            ->where('idsatuan', $id)
            ->first();

        abort_if(!$satuan, 404);

        DB::table('satuan')
            ->where('idsatuan', $id)
            ->delete();

        return redirect()
            ->route('satuan.index')
            ->with('success', 'Data satuan berhasil dihapus.');
    }
}
