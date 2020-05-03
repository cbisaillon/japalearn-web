<?php


namespace App\Helpers;

use App\Interfaces\Learnable;
use Carbon\Carbon;

/**
 * Helper class for the spaced repetition algorithm
 * Class SRSHelper
 * @package App\Helpers
 */
class SRSHelper
{
    private $levels_interval = [4, 8, 24, 72, 168, 336, 720, 2880]; # In hours
    private $MAX_NB_ITEMS = 30;

    /**
     * @var array
     */
    private $allObjects;

    /**
     * @var array
     */
    private $objectUser;

    public function  __construct($allObjects, $objectUser)
    {
        $this->allObjects = $allObjects;
        $this->objectUser = $objectUser;
    }


    /**
     * Get the items that are ready to be reviewed
     * @return array
     */
    public function reviewsAvailable(){
        $toReview = [];

        foreach($this->objectUser as $item){
            if($this->itemNeedReview($item)){
                $toReview[] = $item;
            }
        }

        $answers = [];
        foreach($toReview as $item){
            $answers[] = $item['answers'];
        }

        return [
            'objects' => $toReview,
            'answers'
        ];
    }

    /**
     * Verify if the item need to be reviewed
     * @param Learnable $item
     * @return bool
     */
    private function itemNeedReview($item){
        return Carbon::now()->diffInHours($item['last_review_date']) > $this->levels_interval[$item['level']];
    }

    /**
     * Get the items that are ready to be learned
     * @return array
     */
    public function toLearnAvailable(){
        if(count($this->objectUser) == 0){
            return array_splice($this->allObjects, 0, $this->MAX_NB_ITEMS);
        }

        $nbItemsAvailable = $this->MAX_NB_ITEMS - count(array_filter($this->objectUser, function($item){
            return $item['level'] < 5;
        }));

        $unlearnedItems = $this->unlearnedItems();

        return array_splice($unlearnedItems, 0, $nbItemsAvailable);
    }

    /**
     * Get the objects that the users have not learned yet
     * @return array
     */
    private function unlearnedItems(){
        // Get the indices of the objects the user learned
        $itemsLearnedIndices = array_column($this->objectUser, 'id');

        return array_filter($this->allObjects, function($item) use ($itemsLearnedIndices){
            return !in_array($item['id'], $itemsLearnedIndices);
        });
    }
}
