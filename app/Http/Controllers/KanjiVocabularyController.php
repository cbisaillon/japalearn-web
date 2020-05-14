<?php


namespace App\Http\Controllers;


use App\Helpers\SRSHelper;
use App\Models\VocabLearningPath;
use App\Models\VocabLearningPathItemStats;
use Illuminate\Http\Request;

class KanjiVocabularyController extends Controller
{
    /**
     * Displays to the student a dashboard showing the lessons
     * and reviews available and stats about learning
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $user = $request->user();

        $vocabUser = $user->info->information->vocabLearningPathStats;
        $allVocabItems = VocabLearningPath::query()
            ->whereNotIn('id', $vocabUser->pluck('learning_path_item_id'))
            ->orderBy('level')
            ->orderBy('word_type_id')
            ->limit(30)->get()->toArray();

        $helper = new SRSHelper($allVocabItems, $vocabUser->toArray());
        $itemsToLearn = $helper->toLearnAvailable();
        $itemsToReview = $helper->reviewsAvailable();

        return view('app.kanji_vocabulary.index', compact('user', 'itemsToLearn', 'itemsToReview', 'vocabUser'));
    }

}
