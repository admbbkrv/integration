<?php

declare(strict_types=1);

namespace DataBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Модель таблицы integrations
 */
class Integration extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'client_id',
        'client_secret',
        'redirect_uri',
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'integrations';

    /**
     * The users that belong to the Integration.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'integration_user',
            'integration_id',
            'user_id',
        );
    }
}
