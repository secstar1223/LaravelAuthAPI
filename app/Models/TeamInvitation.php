<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TeamInvitation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'team_invitations';
    protected $fillable = [
        'team_id',
        'email',
        'role',
        'hash',
    ];

    public function team(): BelongsToMany
    {
        return $this->BelongsToMany(Teams::class);
    }


}
