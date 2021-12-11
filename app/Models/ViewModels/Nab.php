<?php

namespace App\Models\ViewModels;

use Database\Factories\NabFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nab extends Model
{
    use HasFactory;

    protected $table = 'vm_nabs';

    protected $fillable = [
        'nab',
        'date',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nab' => 'double',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return NabFactory::new();
    }
}
