<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'vm_transactions';

    protected $fillable = [
        'user_id',
        'type',
        'nab',
        'unit',
        'total_unit',
        'amount',
        'date',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'nab' => 'double',
        'unit' => 'double',
        'amount' => 'integer',
    ];

    // /**
    //  * Create a new factory instance for the model.
    //  *
    //  * @return \Illuminate\Database\Eloquent\Factories\Factory
    //  */
    // protected static function newFactory()
    // {
    //     return NabFactory::new();
    // }
}
