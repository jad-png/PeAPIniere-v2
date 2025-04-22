<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use League\CommonMark\Node\Query\OrExpr;
use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model representing a user in the system",
 *     @OA\Property(property="id", type="integer", description="Unique identifier for the user"),
 *     @OA\Property(property="fullname", type="string", description="Full Name of the user"),
 *     @OA\Property(property="email", type="string", description="Email address of the user (must be unique)"),
 *     @OA\Property(property="password", type="string", description="Password of the user (hashed in the database)"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Timestamp when the email was verified"),
 *     @OA\Property(property="role_id", type="integer", description="ID of the role assigned to the user"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the user was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the user was last updated")
 * )
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        "role_id"
    ];

    protected $attributes = [
        "role_id" => 3
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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(OrExpr::class);
    }
}
