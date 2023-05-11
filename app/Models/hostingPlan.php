<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class hostingPlan extends Model
{
    use HasFactory;
    protected $fillable = ['details_id','package_type','available','price'];

    public function customer_hosting_plan():HasMany
    {
        return $this->hasMany(customerHostingPlan::class);
    }
    public function order():HasMany
    {
        return $this->hasMany(order::class);
    }
    public function detail():BelongsTo
    {
        return $this->belongsTo(details::class);
    }
}
