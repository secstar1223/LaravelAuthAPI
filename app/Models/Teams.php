<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teams extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'teams';
    protected $fillable = [
        'name',
        'email',
        'user_id',
        'phone',
        'date_join',
        'bank',
        'bank_route',
        'front_percent',
        'back_percent',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'timezone',
        'website',
        'currency',
        'cc_disputes_email',
    ];

    public function user(): BelongsToMany
    {
        return $this->BelongsToMany(User::class);
    }

    public function country(): BelongsToMany
    {
        return $this->BelongsToMany(Country::class);
    }


}
