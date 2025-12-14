<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Status;
use App\Traits\Models\HasSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\{Carbon, Str};
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $validation_code
 * @property int|null $deleted_by
 * @property Carbon|null $restored_at
 * @property int|null $restored_by
 * @property Status $status
 * @property int|null $tenant_id
 * @property string|null $avatar
 */
class User extends Authenticatable implements Auditable
{
    use HasFactory;
    use HasRoles;
    use HasSearch;
    use Notifiable;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'validation_code',
        'deleted_by',
        'restored_at',
        'restored_by',
        'status',
        'tenant_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'restored_at' => 'datetime',
            'password' => 'hashed',
            'status' => Status::class,
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

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where($field ?? 'id', $value)->firstOrFail();
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function restoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by', 'id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }
}
