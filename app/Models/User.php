<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'profile_photo_path',
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

    public function friendships(): HasMany
    {
        return $this->hasMany(Friendship::class);
    }

    /**
     * Get all friendships where the user was the recipient.
     */
    public function friendsOf(): HasMany
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    /**
 * Get all accepted friendships.
 */
public function getFriends()
{
    $friends = $this->friendships()->where('status', 'accepted')->get();
    $friendsOf = $this->friendsOf()->where('status', 'accepted')->get();

    return $friends->map(function ($friendship) {
        return $friendship->recipient;
    })->merge($friendsOf->map(function ($friendship) {
        return $friendship->sender;
    }));
}

/**
 * Get the friendship status with another user.
 */
public function friendshipStatusWith(User $user): ?string
{
    $friendship = $this->friendships()->where('friend_id', $user->id)->first()
        ?? $this->friendsOf()->where('user_id', $user->id)->first();

    return $friendship ? $friendship->status : null;
}

/**
 * Get the friendship record with another user.
 */
public function friendshipWith(User $user): ?Friendship
{
    return $this->friendships()->where('friend_id', $user->id)->first()
        ?? $this->friendsOf()->where('user_id', $user->id)->first();
}


public function conversations(): BelongsToMany
{
    return $this->belongsToMany(Conversation::class);
}

public function messages(): HasMany
{
    return $this->hasMany(Message::class);
}
}
