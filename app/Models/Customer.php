<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 
        'company_name', 'address', 'status', 'assigned_user_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($customer) {
            $customer->leads()->delete();
            $customer->activities()->delete();
            $customer->followUps()->delete();
        });

        static::restoring(function ($customer) {
            $customer->leads()->withTrashed()->restore();
            $customer->activities()->withTrashed()->restore();
            $customer->followUps()->withTrashed()->restore();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function updateLastActivity() 
    {
        $this->updated_at = now();
        $this->saveQuietly(); 
    }
}
