<?php

declare(strict_types=1);

namespace DataBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель таблицы api_tokens
 */
class ApiToken extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'access_token',
        'expires',
        'refresh_token',
        'api_key',
        'base_domain',
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'api_tokens';

    /**
     * Get the user that owns the api token.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}
