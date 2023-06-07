<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class supportTicket extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['customer_id','user_id','open_time','close_time','status'];

    public function user():BelongsTo
    {
        return $this->belongsTo(user::class);
    }
    public function customer():BelongsTo
    {
        return $this->belongsTo(customer::class);
    }
    public function message():HasMany
    {
        return $this->hasMany(message::class);
    }
}
