<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\FollowUp;


#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isSales()
    {
        return $this->role === 'sales';
    }


    public function customers()
    {
        return $this->hasMany(Customer::class, 'assigned_user_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
    
}
