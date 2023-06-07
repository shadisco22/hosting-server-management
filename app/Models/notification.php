<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class notification extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['customer_id','content','seen_by_admin','seen_by_customer'];
    public function customer():BelongsTo
    {
        return $this->belongsTo(customer::class);
    }
}
