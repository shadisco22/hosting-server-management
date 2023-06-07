<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class customerHostingPlan extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['customer_id', 'hostingplan_id', 'price', 'expiry_date'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(customer::class);
    }
    public function hosting_plan(): BelongsTo
    {
        return $this->belongsTo(hostingPlan::class, 'hostingplan_id');
    }
}
