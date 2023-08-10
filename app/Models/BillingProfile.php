<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingProfile extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
       'user_id', 'company_name','billing_email', 'first_name', 'last_name'
    ];

    public function safelinks(){
        return $this->hasMany(Safelink::class, 'billing_profile_id', 'id');
    }

    public function newsafelinks(){
        return $this->hasMany(Safelink::class, 'billing_profile_id', 'id')
            ->where('status', 0);
    }
}
