<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class notification extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['customer_id','user_id','customer_hosting_plan_id','notification_type','content','seen_by_customer','seen_by_user','receiver'];
    public function customer():BelongsTo
    {
        return $this->belongsTo(customer::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function customer_hosting_plan():BelongsTo
    {
        return $this->belongsTo(customerHostingPlan::class);
    }
}
