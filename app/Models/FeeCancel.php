<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCancel extends Model
{
    use HasFactory;
    protected $table = 'fee_cancels';
    protected $primaryKey = 'id_fee_cancels';
    protected $fillable = [
        'user_id',
        'fee',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'iduser');
    }
}
