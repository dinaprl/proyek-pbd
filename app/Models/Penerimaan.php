<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $table = 'penerimaan';

    protected $primaryKey = 'idpenerimaan';

    public $timestamps = false;

    protected $fillable = [
        'status',
        'idpengadaan',
        'iduser',
    ];

    public function pengadaan()
    {
        return $this->belongsTo(
            Pengadaan::class,
            'idpengadaan',
            'idpengadaan'
        );
    }

    public function detail()
    {
        return $this->hasMany(
            DetailPenerimaan::class,
            'idpenerimaan',
            'idpenerimaan'
        );
    }
}
