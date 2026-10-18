<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarginPenjualan extends Model
{
    protected $table = 'margin_penjualan';
    protected $primaryKey = 'idmargin_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'persen',
        'status',
        'iduser',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'persen' => 'float',
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'iduser', 'iduser');
}
}
