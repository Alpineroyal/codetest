<!DOCTYPE html>
<html>
  <head>
    <meta charset='UTF-8'>
    <meta name="description" content="">
    <meta name="author" content="Jason Verbiest">
    <meta content='IE=edge' http_equiv='X-UA-Compatible'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <title>JUMBO Interactive Codetest</title>
    <link rel="stylesheet" type="text/css" href="/web/css/site.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/web/js/site.js"></script> 
  </head>
  <body>
    
<?PHP
include_once('config/Init.php');
setlocale(LC_MONETARY, 'en_AU');
$oGameDataHelper = new GamesDataHelper();

$aGameList = $oGameDataHelper->getGamesList();
$aProcessedGamesData = $oGameDataHelper->getGamesDataForDisplay();
?>      
      <div>
       
<?PHP 
        $iRow = 0;
        foreach ( $aGameList as $sKey => $aGameRow  ) : ?>          
          <div>
            <h2><?PHP echo $aGameRow['name'];?></h2>
            
            <div>
                
            <div>Ways of playing the <?PHP echo $aGameRow['name'];?>:</div><br/>
<?PHP 
            // Loop through the game types
            foreach ( $aProcessedGamesData[$sKey]['game_types'] as $aGameType ) :
?>                     
                <div><b><?PHP echo $aGameType->name; ?></b> - <?PHP echo $aGameType->description; ?><button class="show_options" id="toggle_<?PHP echo $iRow?>" onclick="toggleOptions('<?PHP echo $iRow?>');">Show options</button></div><br/>
                
                <div id="options_<?PHP echo $iRow;?>" style="display: none;">
                
                <div><?PHP echo $aGameType->name; ?> Options</div><br/>
  
                <table class='game_types'> 
                <tr>
                    <th>Play option</th>
                    <th>About</th>
                    <th>Number range</th>
                    <th>Price</th>
                    <th>Minimum games</th>
                    <th>Maximum games</th>
                    <th>Multiple games?</th>
                    <th>Available Quickpicks<br/>(No of entries)</th>
                    <th>Upcoming Draws and Jackpots</th>
                </tr>                
                
<?PHP 
                // loop through the game offers
                foreach ( $aGameType->game_offers as $aGameOffers ) :
                    $fMinimumSpend = round($aGameOffers->price->amount * $aGameOffers->min_games,2);
                    $fMaximumSpend = round($aGameOffers->price->amount * $aGameOffers->max_games,2);
                    $sCurrency = $aGameOffers->price->currency;                          
?>        
                    <tr>
                        <td><?PHP echo $aGameOffers->name; ?></td>
                        <td><?PHP echo $aGameOffers->description; ?></td>
                        <td><?PHP echo $aGameOffers->number_sets[0]->first; ?> to <?PHP echo $aGameOffers->number_sets[0]->last; ?></td>
                        <td><?PHP echo money_format('%.2n', $aGameOffers->price->amount)." ".$sCurrency;?></td>
                        <td><?PHP echo $aGameOffers->min_games;?> <br/><span class="notes">(Min spend <?PHP echo money_format('%.2n', $fMinimumSpend)." ".$sCurrency;?> )</span></td>
                        <td><?PHP echo $aGameOffers->max_games;?> <br/><span class="notes">(Max spend <?PHP echo money_format('%.2n', $fMaximumSpend)." ".$sCurrency;?> )</span></td>
                        <td><?PHP echo $aGameOffers->multiple == 1 ? "Yes" : "No";?></td>

                        <td><?PHP echo implode(',',$aProcessedGamesData[$sKey]['quickpick_sizes']); ?></td>
                    
                        <td id="draws">
<?PHP
                            foreach ( $aProcessedGamesData[$sKey]['draws'] as $aDraws ) :  
                                echo $aDraws['formatted_date']."<br/>Jackpot ".money_format('%.2n', $aDraws['amount'])." ".$sCurrency."<br/><br>";
                            endforeach;
?>
                        </td>
                    </tr>            
<?PHP           endforeach; ?>                
           
                </table>    
                
                </div>
                
                <br/>
<?PHP           
                $iRow++;
            endforeach; ?>

            </div>

          </div>          
<?PHP endforeach; ?>             
          
      </div>   
      
  </body>
</html>


