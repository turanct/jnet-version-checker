<?php
//***********************************************************
// Master Script: JNET Version Checker
//***********************************************************

// Requirements
require_once('get_version.php');


class VersionChecker {
	// Variables ----------------------------
	private $exceptions = true;
	private $keyword;
	private $list;
	private $versions;


	// Methods ------------------------------
	/**
	 * Constructor
	 *
	 * @param string		$keyword		The package we want to inspect
	 * @param array			$list			The list with possible packages
	 */
	public function __construct($keyword = false, $list = array()) {
		// Typecast
		$this->keyword = ($keyword !== false) ? (array) $keyword : false ;
		$this->list = (array) $list;
		$this->versions = new stdClass();

		// Begin!
		$this->Walk();
	}


	/**
	 * Method to walk through the keywords
	 */
	private function Walk() {
		// Check Input
		if (empty($this->keyword)) {
			$this->keyword = $list;
		}

		// Walk through keywords
		foreach ((array) $this->keyword as $key => $value) {
			// Check if we can work with this keyword
			if (!in_array($value, $this->list)) {
				// Throw Exception
				$this->exception('This package is not supported: '.$value);

				// Return false
				return false;
			}

			// Get current version
			$Versions = new FOSSVersions($value);
			$this->versions->{$value} = $Versions->version;

			// Get installed versions
			$installed = $this->runLookup($value);

			// Compare
			$this->Compare($this->versions->{$value}, $installed);

			// Actions
			$this->Actions();
		}
	}


	/**
	 * Method to run one of the .sh files
	 *
	 * @param string	$package		The name of the package to look for
	 * @return array					An array of output lines from the shell script
	 */
	private function runLookup($package) {
		// Check if we can work with this package
		if (!in_array($package, $this->list) || !file_exists($package.'.sh') || !is_file($package.'.sh')) {
			// Throw Exception
			$this->exception('This package is not supported: '.$package);

			// Return false
			return false;
		}

		// Run the package script
		exec('./'.$package.'.sh', $output);

		// Return with correct output
		if (!isset($output) || empty($output)) {
			// Throw Exception
			$this->exception('Did not get any output from '.$package.'.sh');

			// Return false
			return false;
		}

		// Process lines
		foreach ((array) $output as $key => $value) {
			$parts = preg_split('/(?!^[\d\.]*)\s/i', $value);
			$output[$key] = new stdClass();
			$output[$key]->version = $parts[0];
			$output[$key]->path = $parts[1];
		}

		// Return
		return $output;
	}


	/**
	 * Method to Compare a list of .sh output to the known last version
	 *
	 * @param array		$current	The current version number
	 * @param array		$output		A list of output from a .sh file
	 */
	private function Compare($current, $output) {
		// Typecast
		$current = (array) $current;
		$output = (array) $output;

		// Prepare arrays
		$this->ListOld = array();
		$this->ListUpToDate = array();

		// Walk through output
		foreach ($output as $key => $value) {
			// If the version is not the same as the current one, it must be older
			if (!in_array($value->version, $current)) {
				$this->ListOld[] = $value;
			}
			else {
				$this->ListUpToDate[] = $value;
			}
		}

		// Return
		return array('old' => $this->ListOld, 'upToDate' => $this->ListUpToDate);
	}


	/**
	 * TODO: Method to do some actions after detection
	 *
	 * @todo Create this method
	 */
	private function Actions() {
		
	}


	/**
	 * Method to throw exceptions
	 *
	 * @param string	$message	The exception message to throw
	 */
	private function exception($message) {
		// Typecast
		$message = (string) $message;

		if ((bool) $this->exceptions === true) {
			// Throw it
			throw new Exception($message);
		}
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
	$VersionChecker = new VersionChecker($keyword, $list);

	// Newline
	echo "\n";
}
?>
