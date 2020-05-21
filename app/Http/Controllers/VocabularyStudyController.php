<?php


namespace App\Http\Controllers;


use App\Helpers\SRSHelper;
use App\Models\VocabLearningPath;
use App\Models\WordType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


/**
 * Class that controls all the study related stuff
 *
 * Class VocabularyStudyController
 * @package App\Http\Controllers
 */
class VocabularyStudyController extends Controller
{

    /**
     * Get the vocabulary (radical, kanji, vocab) that the logged in
     * user can learn now. Redirect to learning view where user can learn the new items
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function vocabularyLesson(Request $request){
        $user = $request->user();

        $vocabUser = $user->info->information->vocabLearningPathStats;
        $allVocabItems = VocabLearningPath::query()
            ->where('word_type_id', "!=", WordType::radical()->id)
            ->whereNotIn('id', $vocabUser->pluck('learning_path_item_id'))
            ->orderBy('level', 'asc')
            ->orderBy('word_type_id', 'asc')
            ->orderBy('word')
            ->limit(30)->with(['onReadings', 'kunReadings'])->get()
            ->toArray();


        $helper = new SRSHelper($allVocabItems, $vocabUser->toArray());
        $itemsToLearn = $helper->toLearnAvailable();

        $itemsBeforeReviews = 5;
        if(count($itemsToLearn) == 0){
            // No items to learn, redirect to dashboard
            return redirect()->route('dashboard');
        }

        $items_chunk = array_chunk($itemsToLearn, $itemsBeforeReviews, false);

        return view("app.study.vocab_lesson", [
            'items' => json_encode($items_chunk),
            'mode' => 'learn'
        ]);
    }

    /**
     * Get the items (radical, kanjis, vocab) that the logged in user
     * can review. After each review, the user have to wait a certain time
     * before reviewing again. This is the principle of spaced repetition
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function vocabularyReview(Request $request){
        $user = $request->user();

        $vocabUser = $user->info->information->vocabLearningPathStats;
        $allVocabItems = VocabLearningPath::query()
            ->where('word_type_id', "!=", WordType::radical()->id)
            ->whereNotIn('id', $vocabUser->pluck('learning_path_item_id'))
            ->orderBy('level', 'asc')
            ->orderBy('word_type_id', 'asc')
            ->orderBy('word')
            ->limit(30)->with(['onReadings', 'kunReadings'])->get()
            ->toArray();

        $helper = new SRSHelper($allVocabItems, $vocabUser->toArray());
        $itemsToLearn = $helper->reviewsAvailable();

//        error_log(json_encode($itemsToLearn));

        return view('app.study.vocab_review', [
            'reviews' => json_encode($itemsToLearn)
        ]);
    }
}
