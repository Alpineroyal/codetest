<?PHP
/**
 * Sets up a curl connection to a remote URL (which is stored in the settings file) and then retrieves 
 *
 * @author jasonverbiest
 */
class LotteryGamesData {
    
    private $aGamesData;
    private $JSONGamesData;
    
    
    /**
     * class contructor 
     */
    public function __construct() {       
        $this->setGamesData();     
    }

    
    /**
     * Open a curl connection to the remote server and get the JSON object. If there is no data then the JSON data attribute is set to false.
     * 
     * @throws Exception if it can't connect to the server. 
     */
    private function setGamesData() {
        
        try {
            
            $curlConnection = curl_init();
            curl_setopt($curlConnection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($curlConnection, CURLOPT_URL, JSON_URL); 
            curl_setopt($curlConnection, CURLOPT_URL, 'http://localhost:8080');                    
            $oJSONDataResult = curl_exec($curlConnection);
            curl_close($curlConnection);
                      
            if ( $oJSONDataResult === false ) :
                throw new Exception("Unable to connect to the remote server, JSON service may be down or connection settings incorrect.");
            endif;  
         
        } catch (Exception $ex) {
            echo $ex;
        }
              
        $aGameData = json_decode($oJSONDataResult);
        
        if ( !empty($aGameData) ) :           
            // We'll store the original JSON data in case it's needed.
            $this->JSONGamesData = $oJSONDataResult;
            
            // and also store the data in a standard PHP array
            $this->aGamesData = $aGameData->result;            
        else :            
            $this->JSONGamesData = false;
            $this->aGamesData = false;
        endif;       
        
    }
    
    
    /**
     * Does what it says on the tin.
     * 
     * @return false if there is no data otherwide an array
     */
    public function getGamesDataArray() {
        
        if ( empty($this->aGamesData) ) :
            return false;
        endif;
        
        return $this->aGamesData;        
               
    }
    
    
    /**
     * Does what it says on the tin.
     * 
     * @return JSON object in its' original form
     */
    public function getGamesDataJSON() {
        
        if ( empty($this->JSONGamesData) ) :
            return false;
        endif;
        
        return $this->JSONGamesData;
               
    }
    
    
    /**
     * class distructor
     */
    public function __destruct() {
        unset($this->aGamesData);
        unset($this->JSONGamesData);
        
    }
    

} // end of class
