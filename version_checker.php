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
	private $actions;

	public $ListOld;
	public $ListUpToDate;


	// Methods ------------------------------
	/**
	 * Constructor
	 *
	 * @param string		$keyword		The package we want to inspect
	 * @param array			$list			The list with possible packages
	 * @param array			$actions		A list of actions to handle after checking the versions
	 */
	public function __construct($keyword = false, $list = array(), $actions) {
		// Typecast
		$this->keyword = ($keyword !== false) ? (array) $keyword : false ;
		$this->list = (array) $list;
		$this->actions = (array) $actions;

		// Prepare
		$this->versions = new stdClass();
		$this->ListOld = new stdClass();
		$this->ListUpToDate = new stdClass();

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
			$this->Compare($value, $installed);
		}

		// Actions
		$this->Actions();
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
			$output[$key]->package = $package;
			$output[$key]->version = $parts[0];
			$output[$key]->path = $parts[1];
		}

		// Return
		return $output;
	}


	/**
	 * Method to Compare a list of .sh output to the known last version
	 *
	 * @param string		$current	The current version name
	 * @param array		$output		A list of output from a .sh file
	 * @return array				An array of two arrays: 'old' and 'upToDate'
	 */
	private function Compare($current, $output) {
		// Typecast
		$current = (string) $current;
		$output = (array) $output;

		// Get current version
		$currentVersion = $this->versions->{$current};

		// Prepare arrays
		$this->ListOld->{$current} = array();
		$this->ListUpToDate->{$current} = array();

		// Walk through output
		foreach ($output as $key => $value) {
			// If the version is not the same as the current one, it must be older
			if (!in_array($value->version, $currentVersion)) {
				$this->ListOld->{$current}[] = $value;
			}
			else {
				$this->ListUpToDate->{$current}[] = $value;
			}
		}

		// Return
		return array('old' => $this->ListOld->{$current}, 'upToDate' => $this->ListUpToDate->{$current});
	}


	/**
	 * Method to do some actions after detection
	 *
	 * @todo Emails
	 */
	private function Actions() {
		// TODO - Email

		// JSON all
		if (in_array('json', $this->actions)) {
			// Prepare
			$data = (object) array('upToDate' => $this->ListUpToDate, 'old' => $this->ListOld);

			// Encode + Output
			echo json_encode($data);
		}

		// Display Old
		if (in_array('old', $this->actions)) {
			if (isset($this->ListOld) && !empty($this->ListOld)) {
				// Echo title
				echo '[+] Not up to date:' . "\n";

				// Walk through elements
				foreach ((array) $this->ListOld as $key => $value) {
					echo "\t" . $value->package . "\n";
					echo "\t" . '[ ' . $value->version . ' ] ' . $value->path . "\n\n";
				}
			}
		}

		// Display Up To Date
		if (in_array('uptodate', $this->actions)) {
			if (isset($this->ListUpToDate) && !empty($this->ListUpToDate)) {
				// Echo title
				echo '[+] Up to date:' . "\n";

				// Walk through elements
				foreach ((array) $this->ListUpToDate as $key => $value) {
					echo "\t" . $value->package . "\n";
					echo "\t" . '[ ' . $value->version . ' ] ' . $value->path . "\n\n";
				}
			}
		}
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
	$VersionChecker = new VersionChecker($keyword, $list, array('old', 'uptodate'));

	// Newline
	echo "\n";
}
?>
