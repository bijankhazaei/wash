<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'cities';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province() : BelongsTo
    {
        return $this->belongsTo(Province::class, 'province', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_city');
    }
}
