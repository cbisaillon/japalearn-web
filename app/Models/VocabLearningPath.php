<?php


namespace App\Models;


use App\Interfaces\Learnable;
use Illuminate\Database\Eloquent\Model;

class VocabLearningPath extends Model
{
    protected $table = "vocab_learning_path";
    protected $fillable = ['word', 'level', 'word_type_id'];
    protected $appends = [
        'answers'
    ];

    public function vocabulary(){
        return $this->belongsTo(Vocabulary::class);
    }

    public function readings(){
        return $this->hasMany(VocabLearningPathReadings::class, 'vocab_learning_path_item_id');
    }

    public function meanings(){
        return $this->hasMany(VocabLearningPathMeanings::class, 'vocab_learning_path_item_id');
    }

    public function examples(){
        return $this->hasMany(VocabLearningPathExample::class, "vocab_learning_path_item_id");
    }

    public function getAnswersAttribute(){
        if($this->word_type_id == WordType::radical()->id){
            return $this->meanings->pluck('meaning');
        }else{
            return [
                'meanings' => $this->meanings->pluck('meaning'),
                'readings' => $this->readings->pluck('reading')
            ];
        }
    }

}
