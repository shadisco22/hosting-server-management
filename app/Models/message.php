<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class message extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['support_ticket_id', 'sender', 'message'];

    public function support_ticket(): BelongsTo
    {
        return $this->belongsTo(supportTicket::class,'support_ticket_id');
    }
}
