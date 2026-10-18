<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = DB::table('view_master_vendor')
            ->select([
                'idvendor',
                'nama_vendor',
                'badan_hukum_deskripsi',
                'status_deskripsi',
            ])
            ->orderBy('idvendor')
            ->get();

        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => ['required', 'string', 'max:255'],
            'badan_hukum' => [
                'required',
                Rule::in(['P', 'C', 'U']),
            ],
            'status' => ['required', 'string', 'max:1'],
        ]);

        Vendor::create($validated);

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Data vendor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);

        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'nama_vendor' => ['required', 'string', 'max:255'],
            'badan_hukum' => [
                'required',
                Rule::in(['P', 'C', 'U']),
            ],
            'status' => ['required', 'string', 'max:1'],
        ]);

        $vendor->update($validated);

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Data vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        $vendor->delete();

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Data vendor berhasil dihapus.');
    }
}

