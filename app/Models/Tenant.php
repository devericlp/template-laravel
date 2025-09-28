<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\Models\HasSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $social_reason
 * @property string $identification_number
 * @property string $subdomain
 * @property string $logo
 * @property string $zipcode
 * @property string $street
 * @property string $neighborhood
 * @property string $city
 * @property string $state
 * @property string $country
 * @property ?int $number
 * @property ?string $complement
 * @property string $cellphone
 * @property string $email
 * @property string $status
 */
class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    use HasSearch;

    protected $fillable = [
        'social_reason',
        'identification_number',
        'subdomain',
        'logo',
        'zipcode',
        'street',
        'neighborhood',
        'city',
        'state',
        'country',
        'number',
        'complement',
        'cellphone',
        'email',
        'status',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
