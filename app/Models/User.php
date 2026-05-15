<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
        'points_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function loyaltyPoints() {
        return $this->hasMany(LoyaltyPoint::class, 'user_id');
    }
    //hàm lấy số dư điểm hiện tại của người dùng
    public function getPointsBalanceAttribute(){
        $lastTransaction = $this->loyaltyPoints()->orderBy('id', 'desc')->first();
        return $lastTransaction ? $lastTransaction->balance_after : 0;
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }
}
