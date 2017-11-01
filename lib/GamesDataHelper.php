<?php
include_once('config/Init.php');

/**
 * GamesDataHelper provides functions for processing the lottery games data so that it can be displayed to the public 
 * in a readable format.
 * 
 * The raw lottery games data is supplied via the LotterGamesData class.
 *
 * @author jasonverbiest
 */
class GamesDataHelper {

    private $aRawGamesData;
    private $aGamesListAndKeys;
    private $aProcessedGamesData;
    
    
    public function __construct() {
        $this->loadRawGamesData();
        $this->processGamesDataForDisplay();
    }
    
    
    
    private function loadRawGamesData() {          
        $oGameData = new LotteryGamesData();
        $this->aRawGamesData = $oGameData->getGamesDataArray();      
        unset($oGameData);
    }
    
    
    /**
     * This function processes the raw games data array into a new array which uses the key as an index. This will allow quick 
     * access to the data for display purposes. The old raw data array is unset to free up system memory to cater for a high number of users.
     */
    private function processGamesDataForDisplay() {
     
        if ( empty($this->aRawGamesData) ) :
            $this->loadRawGamesData();
        endif;
        
        $aNewGamesListAndKeys = array();
        $aNewProcessedData = array();
        
        foreach ( $this->aRawGamesData as $aRawGamesRow ) :
            
            // This array will be used in the top level loop through the games, we just need the name and key
            $aNewGamesListAndKeys[$aRawGamesRow->key]['name'] = $aRawGamesRow->name;
            $aNewGamesListAndKeys[$aRawGamesRow->key]['key'] = $aRawGamesRow->key;            
            
            // The main processed array holds the data and will be indexed using the key to reduce system load.
            $aNewProcessedData[$aRawGamesRow->key]['type'] = $aRawGamesRow->type;
            $aNewProcessedData[$aRawGamesRow->key]['name'] = $aRawGamesRow->name;
            $aNewProcessedData[$aRawGamesRow->key]['autoplayable'] = $aRawGamesRow->autoplayable = 'never' ? "No" : $aRawGamesRow->autoplayable;
            $aNewProcessedData[$aRawGamesRow->key]['game_types'] = $aRawGamesRow->game_types;
            
            
            // Put the draw details into their own array with the date formatted.
            // Normally you would check the current date and only include those draws due after today but for this codetest all the draw dates are old.
            foreach ( $aRawGamesRow->draws as $oDraws ) :               
                $oDate = new DateTime($oDraws->date);
                                
                $aNewProcessedData[$aRawGamesRow->key]['draws'][$oDraws->draw_no]['draw_timestamp'] = $oDraws->date;
                $aNewProcessedData[$aRawGamesRow->key]['draws'][$oDraws->draw_no]['formatted_date'] = $oDate->format('D jS M Y \a\t H:i');
                $aNewProcessedData[$aRawGamesRow->key]['draws'][$oDraws->draw_no]['amount'] = $oDraws->prize_pool->amount;
                $aNewProcessedData[$aRawGamesRow->key]['draws'][$oDraws->draw_no]['currency'] = $oDraws->prize_pool->currency;
                $aNewProcessedData[$aRawGamesRow->key]['draws'][$oDraws->draw_no]['jackpot_image'] = $oDraws->jackpot_image;
            endforeach;
            
            $aNewProcessedData[$aRawGamesRow->key]['days'] = $aRawGamesRow->days;
            $aNewProcessedData[$aRawGamesRow->key]['addons'] = $aRawGamesRow->addons;
            $aNewProcessedData[$aRawGamesRow->key]['quickpick_sizes'] = $aRawGamesRow->quickpick_sizes;
 
        endforeach;

        $this->aGamesListAndKeys = $aNewGamesListAndKeys;
        $this->aProcessedGamesData = $aNewProcessedData;
        unset($aNewGamesListAndKeys);
        unset($aNewProcessedData);
        unset($this->aRawGamesData);
            
        
    }
   
    
    /**
     * 
     * @return array of games indexed by the game key, returns false if there are no games.
     */
    public function getGamesList() {
        
        if ( empty($this->aGamesListAndKeys) ) :
            return false;
        endif;
        
        return $this->aGamesListAndKeys;
    }
    
    
    /**
     * 
     * @return array containing the processed games data
     */
    public function getGamesDataForDisplay() {
        
        if ( empty($this->aProcessedGamesData) ) :
            $this->processGamesDataForDisplay();
        endif;
        
        return $this->aProcessedGamesData;
        
    }
    
    
    
    public function __destruct() {
        unset($this->aRawGamesData);
        unset($this->aProcessedGamesData);
    }
    
}
