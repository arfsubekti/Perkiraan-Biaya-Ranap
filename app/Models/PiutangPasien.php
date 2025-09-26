<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangPasien extends Model
{
    use HasFactory;

    protected $table = 'piutang_pasien';
    public $timestamps = false;
}
