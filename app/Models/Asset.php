<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model {
    protected $table = 'assets';
    protected $fillable = [
        'name',
        'amount',
        'resource_tracking',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
};
