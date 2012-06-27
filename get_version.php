<?php
//***********************************************************
// Get Latest version info from Project Sites
//***********************************************************

class FOSSVersions {
	// Variables ----------------------------
	public $version;


	// Methods ------------------------------
	// Constructor
	public function __construct($project) {
		// Typecast
		$project = ucfirst(strtolower((string) $project));

		// Check if the method exists
		if (method_exists($this, $project)) {
			$this->version = $this->$project();
		}
	}


	// Method to get latest Wordpress version
	private function Wordpress() {
		// Method vars
		$url = 'https://wordpress.org/download/';
		$match = '/<strong>Download&nbsp;WordPress&nbsp;([\d\.]*)<\/strong>/i';

		// Return
		return $this->version($url, $match);
	}


	// Method to get latest Joomla version
	private function Joomla() {
		// Method vars
		$url = 'http://www.joomla.org/download.html';
		$match = '/<td width="265">([\d\.]*)\sFull\sPackage<\/td>/i';

		// Return
		return $this->version($url, $match);
	}


	// Method to get latest Gallery version
	private function Gallery() {
		// Method vars
		$url = 'http://gallery.menalto.com/';
		$match = '/<a\sclass=\"db_g\d\"\shref\=\".*?\">Gallery\s([\d\.]*)<\/a>/i';

		// Return
		return $this->version($url, $match);
	}


	// Method to get latest Drupal version
	private function Drupal() {
		// Method vars
		$url = 'https://drupal.org/project/drupal';
		$match = '/<tr.*?release\-update\-status\-0.*?>[\s\t\n]*?<td.*?views\-field\-version.*?>[\s\t\n]*?<a.*?>([\d\.]*)<\/a>/i';

		// Return
		return $this->version($url, $match);
	}


	// Method to get latest phpBB version
	private function Phpbb() {
		// Method vars
		$url = 'http://www.phpbb.com/';
		$match = '/<span\sclass\=\"version\">([\d\.]*)<\/span>/i';

		// Return
		return $this->version($url, $match);
	}


	// Method to get latest CMS Made Simple version
	private function Cmsmadesimple() {
		// Method vars
		$url = 'http://www.cmsmadesimple.org/downloads/';
		$match = '/<span\sclass\=\"greent\">.*?Version\:\s([\d\.]*)<\/span>/i';

		// Return
		return $this->version($url, $match);
	}


	// Method to get latest MediaWiki version
	private function Mediawiki() {
		// Method vars
		$url = 'https://www.mediawiki.org/wiki/Download';
		$match = '/<a\s.*?class\=\"extiw\".*?>Download\sMediaWiki\s([\d\.]*)<\/a>/i';

		// Return
		return $this->version($url, $match);
	}


	// Method to get latest Fork CMS version
	private function Forkcms() {
		// Method vars
		$url = 'http://www.fork-cms.com/download';
		$match = '/<a\shref.*?github.*?>Fork\sCMS\s([\d\.]*)<\/a>/i';

		// Return
		return $this->version($url, $match);
	}


	// Helper Methods -----------------------
	// Method to get the version number array or false
	private function version($url, $match) {
		// Match
		$match = $this->match($match, @file_get_contents($url));

		// Did we find a match?
		if ($match !== false) {
			// Return version
			return $match[1];
		}
		else {
			// Return false
			return false;
		}
	}


	// Method to simplify preg_match and preg_match_all
	private function match($pattern, $subject) {
		// Run The Match
		$match = preg_match_all($pattern, $subject, $matches);
		// Check Matches
		if ($match != 0 && $match !== false && isset($matches) && is_array($matches)) {
			// Return Matches
			return $matches;
		}
		else {
			// Return
			return false;
		}
	}
}



// Run the class if we're not included
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
	if (isset($argv) && isset($argv[1])) {
		// Get argv
		$a = $argv;
		// Options (nr. 1 = keyword)
		$keyword = $a[1];
	}
	else {
		// Get GET data
		$keyword = $_GET['s'];
	}


	// Create new FOSSVersions instance
	$versions = new FOSSVersions($keyword);

	// Print
	echo implode("\n", (array) $versions->version);
	echo "\n";
}

?>
