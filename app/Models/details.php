<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class details extends Model
{
    use HasFactory;
    protected $fillable = [
    'space',
    'bandwidth',
    'email_accounts',
    'mysql_accounts',
    'php_enabled',
    'ssl_certificate',
    'duration',
    'yearly_price',
    'yearly_price_outside_syria'];

    public function hosting_plan():HasOne
    {
        return $this->hasOne(hostingPlan::class);
    }
}
