<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{

    public function index()
    {
    // Memanggil Stored Procedure dari database MySQL
        $barangs = DB::select('CALL sp_tampil_barang_aktif()');

    // Mengirim hasil SP ke halaman daftar barang
        return view('barang.index', compact('barangs'));
    }

    /**
     * Menampilkan form tambah barang
     */
    public function create()
    {
        $satuans = DB::table('satuan')
            ->orderBy('nama_satuan')
            ->get();

        return view('barang.create', compact('satuans'));
    }

    /**
     * Menyimpan data barang baru
     * Data disimpan ke tabel asli: barang
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => [
                'required',
                'string',
                'max:10',
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'idsatuan' => [
                'required',
                'integer',
                Rule::exists('satuan', 'idsatuan'),
            ],
            'status' => [
                'required',
                'integer',
                'in:0,1',
            ],
            'harga' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        DB::table('barang')->insert($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit barang
     */
    public function edit($id)
    {
        $barang = DB::table('barang')
            ->where('idbarang', $id)
            ->first();

        abort_if(!$barang, 404);

        $satuans = DB::table('satuan')
            ->orderBy('nama_satuan')
            ->get();

        return view('barang.edit', compact('barang', 'satuans'));
    }

    /**
     * Memperbarui data barang
     * Data diperbarui pada tabel asli: barang
     */
    public function update(Request $request, $id)
    {
        $barang = DB::table('barang')
            ->where('idbarang', $id)
            ->first();

        abort_if(!$barang, 404);

        $validated = $request->validate([
            'jenis' => [
                'required',
                'string',
                'max:10',
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'idsatuan' => [
                'required',
                'integer',
                Rule::exists('satuan', 'idsatuan'),
            ],
            'status' => [
                'required',
                'integer',
                'in:0,1',
            ],
            'harga' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        DB::table('barang')
            ->where('idbarang', $id)
            ->update($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang
     * Data dihapus dari tabel asli: barang
     */
    public function destroy($id)
    {
        $barang = DB::table('barang')
            ->where('idbarang', $id)
            ->first();

        abort_if(!$barang, 404);

        DB::table('barang')
            ->where('idbarang', $id)
            ->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil dihapus.');
    }
}
