<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class order extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','hostingplan_id','receipt','status','final_price'];

    public function customer():BelongsTo
    {
        return $this->belongsTo(customer::class);
    }
    public function hosting_plan():BelongsTo
    {
        return $this->belongsTo(hostingPlan::class);
    }
}
