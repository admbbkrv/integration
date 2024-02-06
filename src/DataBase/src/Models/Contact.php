<?php

declare(strict_types=1);

namespace DataBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель таблицы contact
 */
class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'user_id',
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'contacts';

    /**
     * Get the user that owns the contact.
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

    /**
     * Get the email associated with the contact.
     * @return HasMany
     */
    public function emails(): HasMany
    {
        return $this->hasMany(
            Email::class,
            'contact_id',
            'id'
        );
    }
}
