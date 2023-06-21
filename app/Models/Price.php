<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model {
    protected $fillable = [
        'product_id',
        'duration_id',
        'euquipment_id',
        'total',
        'deposit',
    ];

    public function equipmentTypes()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function durations() {
        return $this->belongsTo(Duration::class);
    }

	public function product() {
        return $this->belongsTo(RentalProducts::class);
    }
};
