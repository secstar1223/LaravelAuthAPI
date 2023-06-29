<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultipleChoice extends Model
{
    protected $table = 'multiple_choices';
    protected $fillable = [
        'question_id',
        'choice',
    ];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
