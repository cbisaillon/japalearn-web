<?php


namespace App\Models;


use App\Helpers\SRSHelper;
use App\Interfaces\Learnable;
use Illuminate\Database\Eloquent\Model;

class KanaLearningStats extends Model implements Learnable
{
    protected $table = "kana_student";

    protected $fillable = [
        'student_info_id',
        'kana_id',
        'number_right',
        'last_review'
    ];

    protected $appends = [
        'last_review_date',
        'level',
        'human_level',
        'object_id',
        'next_review',
        'answers'
    ];

    protected $dates = [
        'last_review'
    ];

    public static $LEVELS_INTERVAL = [0, 2, 4, 10, 15]; # In hours

    public function student(){
        return $this->belongsTo(User::class, 'student_id');
    }

    public function kana(){
        return $this->belongsTo(Kana::class, 'kana_id');
    }

    public function getLastReviewDateAttribute()
    {
        return $this->last_review;
    }

    public function getLevelAttribute()
    {
        return $this->number_right;
    }

    public function getHumanLevelAttribute()
    {
        return "HAHA";
    }

    public function getAnswersAttribute(){
        return $this->kana->answers;
    }

    public function getObjectIdAttribute()
    {
        return "kana";
    }

    public function getNextReviewAttribute()
    {
        if($this->number_right >= 5){
            return null;
        }

        return $this->last_review->addHours(KanaLearningStats::$LEVELS_INTERVAL[$this->number_right]);
    }

    public function getLevelIntervalAttribute()
    {
        return KanaLearningStats::$LEVELS_INTERVAL;
    }
}
