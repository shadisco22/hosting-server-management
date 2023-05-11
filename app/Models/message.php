<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class message extends Model
{
    use HasFactory;
    protected $fillable = ['supportticket_id','sender','message'];

    public function support_ticket():BelongsTo
    {
        return $this->belongsTo(supportTicket::class);
    }
}
