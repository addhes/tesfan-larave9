<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Epresence extends Model
{
    use HasFactory;

    protected $table = 'epresences';
    protected $fillable = [
        'id_users', 'type', 'waktu', 'is_approve'
    ];

    public function users()
    {
        return $this->hasOne('id_users');
    }

    // public function getWaktu($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    // }   
}
