<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availabilities';
    protected $casts = [
        'starts_specific' => 'json',
    ];

    protected $fillable = [
        'times',
        'start_time',
        'end_time',
        'starts_every',
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun',
        'starts_specific',
    ];

    public function product()
    {
        return $this->belongsTo(RentalProducts::class,'product_id');
    }

    public function durations()
    {
        return $this->belongsToMany(Duration::class);
    }
}
