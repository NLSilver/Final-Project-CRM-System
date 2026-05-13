<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_id', 'name', 'email', 'phone',
        'source', 'status', 'priority', 'expected_value', 'notes',
        'assigned_user_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($lead) {
            $lead->leads()->delete();
            $lead->activities()->delete();
            $lead->followUps()->delete();
        });

        static::restoring(function ($lead) {
            $lead->leads()->withTrashed()->restore();
            $lead->activities()->withTrashed()->restore();
            $lead->followUps()->withTrashed()->restore();
        });
    }

    public static function getStatuses()
    {
        return [
            'New'           => 'blue',
            'Contacted'     => 'orange',
            'Qualified'     => 'teal',
            'Proposal Sent' => 'indigo',
            'Negotiation'   => 'purple',
            'Won'           => 'green',
            'Lost'          => 'red',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
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
