<?php

declare(strict_types=1);

namespace DataBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Модель таблицы users
 */
class User extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'account_id',
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'users';

    /**
     * Get the api token associated with the user.
     * @return HasOne
     */
    public function apiToken(): HasOne
    {
        return $this->hasOne(
            ApiToken::class,
            'user_id',
            'id'
        );
    }

    /**
     * Get the contact associated with the user.
     * @return HasMany
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(
            Contact::class,
            'user_id',
            'id'
        );
    }

    /**
     * The integrations that belong to the user.
     */
    public function integrations(): BelongsToMany
    {
        return $this->belongsToMany(
            Integration::class,
            'integration_user',
            'user_id',
            'integration_id',
        );
    }
}
