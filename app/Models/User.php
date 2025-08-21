<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

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
    'status',
    'profile_image',
    'username',
    'document'
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
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

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function votes()
{
    return $this->hasMany(Vote::class);
}

public function groupChats()
{
    return $this->belongsToMany(GroupChat::class);
}

public function chatMessages()
{
    return $this->hasMany(ChatMessage::class);
}

public function isAdmin()
{
    return $this->role === 'admin';
}

public function isOrg()
{
    return $this->role === 'org';
}
public function hasAnyRole(...$roles)
{
    return in_array($this->role, $roles);
}


}
