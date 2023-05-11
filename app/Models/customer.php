<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class customer extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','company_name'];

    public function user():BelongsTo
    {
        return $this->belongsTo(user::class);
    }
    public function order():HasMany
    {
        return $this->hasMany(order::class);
    }
    public function customerHostingPlan():HasMany
    {
        return $this->hasMany(customerHostingPlan::class);
    }
    public function support_ticket():HasMany
    {
        return $this->hasMany(supportTicket::class);
    }
}
