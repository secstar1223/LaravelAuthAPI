<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    protected $table = 'rental_equipment_types';
    protected $fillable = [
        'name',
        'asset_id',
        'product_id',
        'description',
        'widget_image',
        'widget_display',
        'min_amount',
        'max_amount',
        'require_min',
        'tax_template',
    ];


    public function product()
    {
        return $this->belongsTo(RentalProducts::class);
    }

}
