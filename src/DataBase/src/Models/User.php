<?php

declare(strict_types=1);

namespace DataBase\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель таблицы users
 */
class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_email',
        'user_password',
    ];

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'users';

}
