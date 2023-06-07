<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class activities_log extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['user_id', 'activity_type', 'activity_type', 'record_id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
