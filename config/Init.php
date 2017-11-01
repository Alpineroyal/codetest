<?php
include_once 'ClassesToInclude.php';

error_reporting(0);

/**
 * Initialisation class, used to 
 * 
 * Usage: 
 * require_once APP_ROOT . 'includes/utils/Init.php';
 * 
 * @author yanchao
 *
 */
class Init {

	public function __construct() {

            //register classes
            if (function_exists('__autoload')) :
                // Register any existing autoloader function with SPL, so we don't get any clashes
		spl_autoload_register('__autoload');
            endif;
		
            spl_autoload_register( array ($this, 'loadClass' ) );

	}
	
	/**
	 * Class loader.
	 */
	public function loadClass($name) {
		$aClasses = ClassesToInclude::getClasses();

		if (!array_key_exists( $name, $aClasses )) :
		die ( 'Class "' . $name . '" not found.' );
		endif;
                
		require_once $aClasses[$name];
	}
	
} //end class 

// Auto run the init class.
$oInit = new Init();