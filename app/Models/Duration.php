<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    protected $table = 'durations';
    protected $fillable = [
        'name',
        'duration',
		'buffer',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(RentalProducts::class);
    }

    public function availabilities() {
        return $this->belongsToMany(Availability::class);
    }

    public function price(){
        return $this->hasMany(Price::class);
    }
}
