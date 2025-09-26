<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MasterMasalahMPP extends Model
{
    protected $table = 'master_masalah_mpp';
    protected $primaryKey = 'kode_masalah';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['kode_masalah', 'nama_masalah'];
}
