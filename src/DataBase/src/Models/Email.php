<?php

declare(strict_types=1);

namespace DataBase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель таблицы users
 */
class Email extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'email',
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'emails';

    /**
     * Get the contact that owns the email.
     * @return BelongsTo
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(
            Contact::class,
            'contact_id',
            'id'
        );
    }
}
