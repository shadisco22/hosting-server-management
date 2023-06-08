<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class hostingPlan extends Model
{

    use HasFactory ,SoftDeletes;

    protected $fillable = [
    'package_type',
    'available',
    'space',
    'bandwidth',
    'email_accounts',
    'mysql_accounts',
    'php_enabled',
    'ssl_certificate',
    'duration',
    'yearly_price',
    'yearly_price_outside_syria'];

    public function customer_hosting_plan():HasMany
    {
        return $this->hasMany(customerHostingPlan::class);
    }
    public function order():HasMany
    {
        return $this->hasMany(order::class);
    }
}
