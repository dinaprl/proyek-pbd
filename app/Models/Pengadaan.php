<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    protected $table = 'pengadaan';

    protected $primaryKey = 'idpengadaan';

    public $timestamps = false;

    protected $fillable = [
        'user_iduser',
        'status',
        'vendor_idvendor',
        'subtotal_nilai',
        'ppn',
        'total_nilai',
    ];

    public function vendor()
    {
        return $this->belongsTo(
            Vendor::class,
            'vendor_idvendor',
            'idvendor'
        );
    }

    public function detail()
    {
        return $this->hasMany(
            DetailPengadaan::class,
            'idpengadaan',
            'idpengadaan'
        );
    }
}
