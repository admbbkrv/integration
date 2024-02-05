<?php

declare(strict_types=1);

namespace DataBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'contacts';

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
