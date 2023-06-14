<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalProducts extends Model {
	 protected $table = 'rental_products';

    protected $fillable = [
        'name',
        'description',
        'tax_template',
        'team_id',
        'image',
    ];

    public function team()
    {
        return $this->belongsTo(Teams::class);
    }

    // public function groups() {
    //     return $this->belongsToMany(TaxGroup::class);
    // }

	public function equipmenttypes() {
        return $this->hasMany(EquipmentType::class, 'product_id');
    }
    public function availabilities() {
        return $this->hasMany(Availability::class, 'product_id');
    }

    public function durations() {
        return $this->hasMany(Duration::class, 'product_id');
    }

    public function prices(){
        return $this->hasMany(Price::class,'product_id');
    }
};
