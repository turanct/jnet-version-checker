<?php
//***********************************************************
// Master Script: JNET Version Checker
//***********************************************************

class VersionChecker {
	// Variables ----------------------------
	private $keyword;


	// Methods ------------------------------
	/**
	 * Constructor
	 *
	 * @param string		$keyword		The package we want to inspect
	 */
	public function __construct($keyword = false) {
		// Typecast
		$this->keyword = ($keyword !== false) ? (string) $keyword : false ;
	}

}



// Run the class if we're not included
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
	// List of possible FOSS CMS'es
	$list = array('drupal', 'gallery', 'joomla', 'phpbb', 'wordpress');

	// Command line + arguments
	if (isset($argv) && isset($argv[1])) {
		// Get argv
		$a = (array) $argv;

		// Shift filename off the beginning of the array
		array_shift($a);

		// Options (nr. 1 = keyword)
		$keyword = $a;
	}

	// HTTP GET + arguments
	elseif(isset($_GET) && isset($_GET['s'])) {
		// Get GET data
		$keyword = (array) explode('+', $_GET['s']);
	}

	// Else
	else {
		// All
		$keyword = $list;
	}

	// Filter keywords
	foreach ((array) $keyword as $key => $value) {
		if (!in_array($value, $list)) {
			// Unset the value if we don't support it
			unset($keyword);
		}
	}
	
	// Reset array keys
	$keyword = array_merge($keyword);

	// Create new VersionChecker instance
	$VersionChecker = new VersionChecker($keyword);

	// Newline
	echo "\n";
}
?>
