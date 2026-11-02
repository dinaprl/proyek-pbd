<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPengadaan;
use App\Models\Pengadaan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PengadaanController extends Controller
{
    /**
     * Menampilkan seluruh data pengadaan.
     */
    public function index()
    {
        $pengadaan = Pengadaan::with([
            'vendor',
            'detail.barang',
        ])
            ->orderByDesc('idpengadaan')
            ->get();

        return view('pengadaan.index', compact('pengadaan'));
    }

    /**
     * Menampilkan formulir tambah pengadaan.
     */
    public function create()
    {
        $vendor = Vendor::where('status', 'A')
            ->orderBy('nama_vendor')
            ->get();

        $barang = Barang::where('status', 1)
            ->orderBy('nama')
            ->get();

        return view(
            'pengadaan.create',
            compact('vendor', 'barang')
        );
    }

    /**
     * Menyimpan pengadaan baru.
     * Mendukung beberapa barang dalam satu transaksi.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'vendor_idvendor' => [
                'required',
                'integer',
                Rule::exists('vendor', 'idvendor')
                    ->where('status', 'A'),
            ],

            'barang_id' => [
                'required',
                'array',
                'min:1',
            ],

            'barang_id.*' => [
                'required',
                'integer',
                Rule::exists('barang', 'idbarang')
                    ->where('status', 1),
            ],

            'jumlah' => [
                'required',
                'array',
                'min:1',
            ],

            'jumlah.*' => [
                'required',
                'integer',
                'min:1',
            ],

            'harga_satuan' => [
                'required',
                'array',
                'min:1',
            ],

            'harga_satuan.*' => [
                'required',
                'numeric',
                'min:0',
            ],

            'ppn_persen' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);

        if (
            count($data['barang_id']) !== count($data['jumlah']) ||
            count($data['barang_id']) !== count($data['harga_satuan'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'barang_id' => 'Data detail pengadaan tidak lengkap.',
                ]);
        }

        DB::transaction(function () use ($data) {
            $subtotal = 0;
            $details = [];

            foreach ($data['barang_id'] as $i => $idbarang) {
                $jumlah = (int) $data['jumlah'][$i];
                $harga = (float) $data['harga_satuan'][$i];

                $subTotalDetail = $jumlah * $harga;
                $subtotal += $subTotalDetail;

                $details[] = [
                    'idbarang' => (int) $idbarang,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'sub_total' => $subTotalDetail,
                ];
            }

            $ppnPersen = (float) ($data['ppn_persen'] ?? 11);
            $ppn = (int) round($subtotal * $ppnPersen / 100);
            $total = $subtotal + $ppn;

            $pengadaan = Pengadaan::create([
                'user_iduser' => 1,
                'status' => 'P',
                'vendor_idvendor' => $data['vendor_idvendor'],
                'subtotal_nilai' => $subtotal,
                'ppn' => $ppn,
                'total_nilai' => $total,
            ]);

            foreach ($details as $detail) {
                $detail['idpengadaan'] = $pengadaan->idpengadaan;

                DetailPengadaan::create($detail);
            }
        });

        return redirect()
            ->route('pengadaan.index')
            ->with('success', 'Data pengadaan berhasil disimpan.');
    }

    /**
     * Menampilkan formulir edit pengadaan.
     */
    public function edit($id)
    {
        $pengadaan = DB::table('pengadaan')
            ->where('idpengadaan', $id)
            ->first();

        if (!$pengadaan) {
            abort(404);
        }

        if ($pengadaan->status === 'D') {
            return redirect()
                ->route('pengadaan.index')
                ->with(
                    'error',
                    'Pengadaan yang sudah diterima tidak dapat diedit.'
                );
        }

        $details = DB::table('detail_pengadaan')
            ->join(
                'barang',
                'barang.idbarang',
                '=',
                'detail_pengadaan.idbarang'
            )
            ->where('detail_pengadaan.idpengadaan', $id)
            ->select(
                'detail_pengadaan.*',
                'barang.nama'
            )
            ->get();

        $vendors = DB::table('vendor')
            ->where('status', 'A')
            ->orderBy('nama_vendor')
            ->get();

        $barangs = DB::table('barang')
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        $ppnPersen = $pengadaan->subtotal_nilai > 0
            ? round(
                $pengadaan->ppn / $pengadaan->subtotal_nilai * 100,
                2
            )
            : 11;

        return view(
            'pengadaan.edit',
            compact(
                'pengadaan',
                'details',
                'vendors',
                'barangs',
                'ppnPersen'
            )
        );
    }

    /**
     * Memperbarui data pengadaan.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'vendor_idvendor' => [
                'required',
                'integer',
                Rule::exists('vendor', 'idvendor')
                    ->where('status', 'A'),
            ],

            'barang_id' => [
                'required',
                'array',
                'min:1',
            ],

            'barang_id.*' => [
                'required',
                'integer',
                Rule::exists('barang', 'idbarang')
                    ->where('status', 1),
            ],

            'jumlah' => [
                'required',
                'array',
                'min:1',
            ],

            'jumlah.*' => [
                'required',
                'integer',
                'min:1',
            ],

            'harga_satuan' => [
                'required',
                'array',
                'min:1',
            ],

            'harga_satuan.*' => [
                'required',
                'numeric',
                'min:0',
            ],

            'ppn_persen' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);

        if (
            count($data['barang_id']) !== count($data['jumlah']) ||
            count($data['barang_id']) !== count($data['harga_satuan'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'barang_id' => 'Data detail pengadaan tidak lengkap.',
                ]);
        }

        DB::transaction(function () use ($data, $id) {
            /*
             * Periksa ulang status di dalam transaksi
             * agar pengadaan yang sudah diterima tidak diedit.
             */
            $pengadaan = DB::table('pengadaan')
                ->where('idpengadaan', $id)
                ->lockForUpdate()
                ->first();

            if (!$pengadaan) {
                abort(404);
            }

            if ($pengadaan->status === 'D') {
                throw ValidationException::withMessages([
                    'status' => 'Pengadaan yang sudah diterima tidak dapat diedit.',
                ]);
            }

            $subtotal = 0;
            $details = [];

            foreach ($data['barang_id'] as $i => $idbarang) {
                $jumlah = (int) $data['jumlah'][$i];
                $harga = (float) $data['harga_satuan'][$i];

                $subTotalDetail = $jumlah * $harga;
                $subtotal += $subTotalDetail;

                $details[] = [
                    'idbarang' => (int) $idbarang,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'sub_total' => $subTotalDetail,
                ];
            }

            $ppnPersen = (float) ($data['ppn_persen'] ?? 11);
            $ppn = (int) round($subtotal * $ppnPersen / 100);
            $total = $subtotal + $ppn;

            DB::table('pengadaan')
                ->where('idpengadaan', $id)
                ->update([
                    'vendor_idvendor' => $data['vendor_idvendor'],
                    'subtotal_nilai' => $subtotal,
                    'ppn' => $ppn,
                    'total_nilai' => $total,
                ]);

            /*
             * Ganti detail pengadaan lama dengan detail terbaru.
             * Pengadaan yang sudah diterima telah dicegah di atas.
             */
            DB::table('detail_pengadaan')
                ->where('idpengadaan', $id)
                ->delete();

            foreach ($details as $detail) {
                $detail['idpengadaan'] = $id;

                DB::table('detail_pengadaan')
                    ->insert($detail);
            }
        });

        return redirect()
            ->route('pengadaan.index')
            ->with('success', 'Pengadaan berhasil diperbarui.');
    }
}
