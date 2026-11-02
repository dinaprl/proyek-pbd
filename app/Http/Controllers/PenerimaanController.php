<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PenerimaanController extends Controller
{
    /**
     * Menampilkan seluruh data penerimaan.
     */
    public function index()
    {
        $penerimaan = Penerimaan::with([
            'pengadaan.vendor',
            'detail.barang',
        ])
            ->orderByDesc('idpenerimaan')
            ->get();

        return view('penerimaan.index', compact('penerimaan'));
    }

    /**
     * Menampilkan formulir tambah penerimaan.
     */
    public function create()
    {
        $pengadaan = Pengadaan::with('detail.barang')
            ->where('status', 'P')
            ->orderByDesc('idpengadaan')
            ->get();

        $barang = Barang::where('status', 1)
            ->orderBy('nama')
            ->get();

        return view(
            'penerimaan.create',
            compact('pengadaan', 'barang')
        );
    }

    /**
     * Menyimpan penerimaan baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'idpengadaan' => [
                'required',
                'integer',
                Rule::exists('pengadaan', 'idpengadaan')
                    ->where('status', 'P'),
            ],

            'barang_idbarang' => [
                'required',
                'array',
                'min:1',
            ],

            'barang_idbarang.*' => [
                'required',
                'integer',
                Rule::exists('barang', 'idbarang'),
            ],

            'jumlah_terima' => [
                'required',
                'array',
                'min:1',
            ],

            'jumlah_terima.*' => [
                'required',
                'integer',
                'min:1',
            ],

            'harga_satuan_terima' => [
                'required',
                'array',
                'min:1',
            ],

            'harga_satuan_terima.*' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        if (
            count($data['barang_idbarang']) !== count($data['jumlah_terima']) ||
            count($data['barang_idbarang']) !== count($data['harga_satuan_terima'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'barang_idbarang' =>
                        'Data detail penerimaan tidak lengkap.',
                ]);
        }

        DB::transaction(function () use ($data) {
            /*
             * Periksa pengadaan dan kunci baris selama transaksi.
             */
            $pengadaan = DB::table('pengadaan')
                ->where('idpengadaan', $data['idpengadaan'])
                ->lockForUpdate()
                ->first();

            if (!$pengadaan || $pengadaan->status !== 'P') {
                throw ValidationException::withMessages([
                    'idpengadaan' =>
                        'Pengadaan tidak ditemukan atau sudah diterima.',
                ]);
            }

            /*
             * Simpan data utama penerimaan.
             * Status awal P akan diubah menjadi D setelah
             * semua detail barang berhasil disimpan.
             */
            $idpenerimaan = DB::table('penerimaan')->insertGetId([
                'idpengadaan' => $data['idpengadaan'],
                'iduser' => 1,
                'status' => 'P',
            ], 'idpenerimaan');

            /*
             * Simpan seluruh detail barang.
             * Trigger database dapat mencatat barang masuk
             * ke kartu stok ketika detail di-insert.
             */
            foreach ($data['barang_idbarang'] as $i => $barangId) {
                $jumlah = (int) $data['jumlah_terima'][$i];
                $harga = (float) $data['harga_satuan_terima'][$i];

                DB::table('detail_penerimaan')->insert([
                    'idpenerimaan' => $idpenerimaan,
                    'barang_idbarang' => (int) $barangId,
                    'jumlah_terima' => $jumlah,
                    'harga_satuan_terima' => $harga,
                    'sub_total_terima' => $jumlah * $harga,
                ]);
            }

            /*
             * Semua detail berhasil disimpan.
             * Ubah status penerimaan menjadi D (Done).
             */
            DB::table('penerimaan')
                ->where('idpenerimaan', $idpenerimaan)
                ->update([
                    'status' => 'D',
                ]);

            /*
             * Tandai pengadaan sudah selesai diterima.
             */
            DB::table('pengadaan')
                ->where('idpengadaan', $data['idpengadaan'])
                ->update([
                    'status' => 'D',
                ]);
        });

        return redirect()
            ->route('penerimaan.index')
            ->with(
                'success',
                'Penerimaan berhasil disimpan dan statusnya sudah selesai.'
            );
    }

    /**
     * Menampilkan formulir edit penerimaan.
     */
    public function edit($id)
    {
        $penerimaan = DB::table('penerimaan')
            ->where('idpenerimaan', $id)
            ->first();

        if (!$penerimaan) {
            abort(404);
        }

        $details = DB::table('detail_penerimaan as dp')
            ->join(
                'barang as b',
                'b.idbarang',
                '=',
                'dp.barang_idbarang'
            )
            ->where('dp.idpenerimaan', $id)
            ->select('dp.*', 'b.nama')
            ->get();

        $pengadaans = DB::table('pengadaan as p')
            ->join(
                'vendor as v',
                'v.idvendor',
                '=',
                'p.vendor_idvendor'
            )
            ->where(function ($query) use ($penerimaan) {
                $query->where('p.status', 'P')
                    ->orWhere(
                        'p.idpengadaan',
                        $penerimaan->idpengadaan
                    );
            })
            ->select(
                'p.idpengadaan',
                'p.timestamp',
                'p.status',
                'v.nama_vendor'
            )
            ->orderByDesc('p.idpengadaan')
            ->get();

        $barangs = DB::table('barang')
            ->where('status', 1)
            ->orderBy('nama')
            ->get();

        return view(
            'penerimaan.edit',
            compact(
                'penerimaan',
                'details',
                'pengadaans',
                'barangs'
            )
        );
    }

    /**
     * Memperbarui data penerimaan.
     *
     * Catatan:
     * Kartu stok disinkronkan secara manual di sini.
     * Pastikan trigger INSERT tidak membuat pencatatan stok
     * ganda ketika detail baru ditambahkan.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'idpengadaan' => [
                'required',
                'integer',
                Rule::exists('pengadaan', 'idpengadaan'),
            ],

            'iddetail_penerimaan' => [
                'nullable',
                'array',
            ],

            'iddetail_penerimaan.*' => [
                'nullable',
                'integer',
            ],

            'barang_idbarang' => [
                'required',
                'array',
                'min:1',
            ],

            'barang_idbarang.*' => [
                'required',
                'integer',
                Rule::exists('barang', 'idbarang'),
            ],

            'jumlah_terima' => [
                'required',
                'array',
                'min:1',
            ],

            'jumlah_terima.*' => [
                'required',
                'integer',
                'min:1',
            ],

            'harga_satuan_terima' => [
                'required',
                'array',
                'min:1',
            ],

            'harga_satuan_terima.*' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        if (
            count($data['barang_idbarang']) !== count($data['jumlah_terima']) ||
            count($data['barang_idbarang']) !== count($data['harga_satuan_terima'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'barang_idbarang' =>
                        'Data detail penerimaan tidak lengkap.',
                ]);
        }

        DB::transaction(function () use ($data, $id) {
            $penerimaan = DB::table('penerimaan')
                ->where('idpenerimaan', $id)
                ->lockForUpdate()
                ->first();

            if (!$penerimaan) {
                abort(404);
            }

            $existingDetails = DB::table('detail_penerimaan')
                ->where('idpenerimaan', $id)
                ->get()
                ->keyBy('iddetail_penerimaan');

            $submittedIds = array_filter(
                $data['iddetail_penerimaan'] ?? [],
                fn ($value) => $value !== null && $value !== ''
            );

            $submittedIds = array_map('intval', $submittedIds);

            /*
             * Pastikan semua detail yang dikirim benar-benar
             * milik penerimaan yang sedang diedit.
             */
            foreach ($submittedIds as $detailId) {
                if (!$existingDetails->has($detailId)) {
                    throw ValidationException::withMessages([
                        'iddetail_penerimaan' =>
                            'Detail penerimaan tidak valid.',
                    ]);
                }
            }

            $affectedBarang = [];

            /*
             * Perbarui detail lama atau tambahkan detail baru.
             */
            foreach ($data['barang_idbarang'] as $i => $barangId) {
                $detailId = $data['iddetail_penerimaan'][$i] ?? null;

                $jumlah = (int) $data['jumlah_terima'][$i];
                $harga = (float) $data['harga_satuan_terima'][$i];

                $detailData = [
                    'barang_idbarang' => (int) $barangId,
                    'jumlah_terima' => $jumlah,
                    'harga_satuan_terima' => $harga,
                    'sub_total_terima' => $jumlah * $harga,
                ];

                if ($detailId !== null && $detailId !== '') {
                    $detailId = (int) $detailId;
                    $oldDetail = $existingDetails->get($detailId);

                    $affectedBarang[] =
                        (int) $oldDetail->barang_idbarang;

                    $affectedBarang[] = (int) $barangId;

                    DB::table('detail_penerimaan')
                        ->where('iddetail_penerimaan', $detailId)
                        ->where('idpenerimaan', $id)
                        ->update($detailData);

                    /*
                     * Perbarui catatan kartu stok yang dibuat trigger.
                     */
                    $kartu = DB::table('kartu_stok')
                        ->where('idtransaksi', $detailId)
                        ->where('jenis_transaksi', 'M')
                        ->first();

                    if ($kartu) {
                        DB::table('kartu_stok')
                            ->where(
                                'idkartu_stok',
                                $kartu->idkartu_stok
                            )
                            ->update([
                                'idbarang' => (int) $barangId,
                                'masuk' => $jumlah,
                                'keluar' => 0,
                            ]);
                    } else {
                        throw new \RuntimeException(
                            "Catatan kartu stok untuk detail {$detailId} tidak ditemukan."
                        );
                    }
                } else {
                    $affectedBarang[] = (int) $barangId;

                    /*
                     * Detail baru akan menjalankan trigger INSERT.
                     */
                    DB::table('detail_penerimaan')->insert([
                        'idpenerimaan' => $id,
                        'barang_idbarang' => (int) $barangId,
                        'jumlah_terima' => $jumlah,
                        'harga_satuan_terima' => $harga,
                        'sub_total_terima' => $jumlah * $harga,
                    ]);
                }
            }

            /*
             * Hapus detail lama yang tidak lagi ada di formulir.
             */
            foreach ($existingDetails as $detailId => $oldDetail) {
                if (!in_array((int) $detailId, $submittedIds, true)) {
                    $affectedBarang[] =
                        (int) $oldDetail->barang_idbarang;

                    DB::table('kartu_stok')
                        ->where('idtransaksi', $detailId)
                        ->where('jenis_transaksi', 'M')
                        ->delete();

                    DB::table('detail_penerimaan')
                        ->where('iddetail_penerimaan', $detailId)
                        ->where('idpenerimaan', $id)
                        ->delete();
                }
            }

            /*
             * Perbarui relasi pengadaan pada penerimaan.
             */
            DB::table('penerimaan')
                ->where('idpenerimaan', $id)
                ->update([
                    'idpengadaan' => $data['idpengadaan'],
                ]);

            /*
             * Hitung ulang saldo kartu stok untuk barang terdampak.
             */
            foreach (array_unique($affectedBarang) as $barangId) {
                $kartuStok = DB::table('kartu_stok')
                    ->where('idbarang', $barangId)
                    ->orderBy('idkartu_stok')
                    ->get();

                $saldo = 0;

                foreach ($kartuStok as $kartu) {
                    $saldo +=
                        (int) $kartu->masuk -
                        (int) $kartu->keluar;

                    DB::table('kartu_stok')
                        ->where(
                            'idkartu_stok',
                            $kartu->idkartu_stok
                        )
                        ->update([
                            'stock' => $saldo,
                        ]);
                }

                /*
                 * Perbarui harga barang dari penerimaan terbaru.
                 */
                $hargaTerbaru = DB::table('detail_penerimaan')
                    ->join(
                        'penerimaan',
                        'penerimaan.idpenerimaan',
                        '=',
                        'detail_penerimaan.idpenerimaan'
                    )
                    ->where(
                        'detail_penerimaan.barang_idbarang',
                        $barangId
                    )
                    ->orderByDesc(
                        'detail_penerimaan.iddetail_penerimaan'
                    )
                    ->value('detail_penerimaan.harga_satuan_terima');

                if ($hargaTerbaru !== null) {
                    DB::table('barang')
                        ->where('idbarang', $barangId)
                        ->update([
                            'harga' => $hargaTerbaru,
                        ]);
                }
            }
        });

        return redirect()
            ->route('penerimaan.index')
            ->with(
                'success',
                'Penerimaan berhasil diperbarui dan kartu stok diselaraskan.'
            );
    }
}
