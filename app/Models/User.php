<?php

namespace App\Models;

use App\Models\ViewModels\Nab;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'unit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit' => 'double',
    ];

    public function getBalanceAttribute(): float
    {
        $nab = Nab::latest('date')->first();
        if ($nab) {
            $nab = $nab->nab;
        } else {
            $nab = 1;
        }

        $balance = round($this->unit * $nab, 2, PHP_ROUND_HALF_DOWN);

        return $balance;
    }
}
