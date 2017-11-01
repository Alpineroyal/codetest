/* 
 * jQuery code for the Jumbo Interactive codetest.
 * Author: Jason Verbiest - October 2017
 */
function toggleOptions(key) {

    $("#options_"+key).slideToggle();

    if ( $("#toggle_"+key).html() == "Show options" ) {
        $("#toggle_"+key).html('Hide options');
    }
    else {
        $("#toggle_"+key).html('Show options');
    }

}