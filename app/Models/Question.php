<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = [
        'question',
        'question_type',
        'question_answer',
        'add_charge_id',
        'follow_up_question',
    ];


    public function multipleChoice()
    {
        return $this->hasMany(MultipleChoice::class);
    }
}
