<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalQuestions extends Model
{
    protected $table = 'rental_questions';
    protected $fillable = [
        'product_id',
        'question_id',
        'is_require',
        'is_internal',
        'is_display',
        'is_checked',
    ];

}
