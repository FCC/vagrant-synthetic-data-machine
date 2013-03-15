<?php

/**
 * Utility functions for use throughout the script.
 * @author Ben Keen <ben.keen@gmail.com>
 * @package Core
 */
class Utils {

	public static function cleanHash($hash) {
		$cleanHash = $hash;
		if (get_magic_quotes_gpc()) {
			while (list($key, $value) = each($hash)) {
				if (!is_array($value)) {
					$cleanHash[$key] = stripslashes($value);
				} else {
					$cleanArray = array();
					foreach ($value as $val) {
						$cleanArray[] = stripslashes($val);
					}
					$cleanHash[$key] = $cleanArray;
				}
			}
		}
		return $cleanHash;
	}


	/**
	 * A generic assertion function used to confirm the existence of things like the existence of values in $_POST,
	 * $_GET, $_SESSIONS, whether the user is logged in and so on. If it fails anything, it throws a GDException,
	 * otherwise it does nothing.
	 *
	 * TODO
	 *
	 * @param array $statements
	 */
	public static function assert($statements) {
		if (empty($statements)) {
			return;
		}

		 while (list($test, $values) = each($statements)) {
			 switch ($test) {
				 case "loggedIn":
					if (empty(Core::$user)) {
						throw new GDException(Exceptions::NOTLOGGEDIN);
						return;
					}
					 break;

				 case "noSettingsFile":
					 $settingsFileAndPath = realpath(dirname(__FILE__) . "/../settings.php");
					 $settingsFileExists = file_exists($settingsFileAndPath);
					if ($values === true && $settingsFileExists) {
						throw new GDException(Exceptions::SETTINGSFILEEXISTS);
					}
					 break;
			 }
		}
	}


	/**
	 * Recursively sanitizes data stored in any non-object data format, preparing it
	 * for safe use in SQL statements.
	 */
	public static function sanitize($input) {
		if (is_array($input)) {
			$output = array();
			foreach ($input as $k=>$i) {
				$output[$k] = Utils::sanitize($i);
			}
		} else {
			if (get_magic_quotes_gpc()) {
				$output = stripslashes($input);
			} else {
				$output = $input;
			}
		}

		return $output;
	}


	/**
	 * Returns a random subset of an array. The result may be empty, or the same set.
	 *
	 * @param array $set - the set of items
	 * @param integer $num - the number of items in the set to return
	 */
	public static function returnRandomSubset($set, $num) {
		// check $num is no greater than the total set
		if ($num > count($set)) {
			$num = count($set);
		}
		shuffle($set);
		return array_slice($set, 0, $num);
	}


	/**
	 * Converts a datetime to a timestamp.
	 */
	public static function convertDatetimeToTimestamp($datetime) {
		list($date, $time) = explode(" ", $datetime);
		list($year, $month, $day) = explode("-", $date);
		list($hours, $minutes, $seconds) = explode(":", $time);
		return mktime($hours, $minutes, $seconds, $month, $day, $year);
	}


	public static function getCurrentDatetime() {
		return date("Y-m-d H:i:s");
	}

	/**
	 * Adds years to a MySQL datetime & returns a UNIX timestamp of the new date
	 */
	public static function addYearsToDatetime($datetime, $yearsToAdd) {
		list($date, $time) = explode(" ", $datetime);
		list($year, $month, $day) = explode("-", $date);
		list($hours, $minutes, $seconds) = explode(":", $time);
		return mktime($hours, $minutes, $seconds, $month, $day, $year+$yearsToAdd);
	}


	/**
	 * This function is like rand, only allows it to be weighted.
	 *
	 * @param array $weights
	 * @return integer
	 */
	public static function weightedRand($weights) {
		$r = mt_rand(1, 1000);
		$offset = 0;
		foreach ($weights as $k => $w) {
			$offset += $w * 1000;
			if ($r <= $offset) {
				return $k;
			}
		}
	}


	/**
	 * A security-related function. This returns a clean version of PHP_SELF for use in the templates. This wards
	 * against URI Cross-site scripting attacks.
	 *
	 * @return the cleaned $_SERVER["PHP_SELF"]
	 */
	public static function getCleanPhpSelf() {
		return htmlspecialchars(strip_tags($_SERVER['PHP_SELF']), ENT_QUOTES);
	}

	/**
	 * Converts the following characters in the parameter string and returns it:
	 *
	 *     C, c, A - any consonant (Upper case, lower case, any)
	 *     V, v, B - any vowel (Upper case, lower case, any)
	 *     L, l, V - any letter (Upper case, lower case, any)
	 *     X       - 1-9
	 *     x       - 0-9
	 *     H       - 0-F
	 *
	 * @param string
	 * @return string
	 */
	static public function generateRandomAlphanumericStr($str) {
		$letters    = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$consonants = "BCDFGHJKLMNPQRSTVWXYZ";
		$vowels     = "AEIOU";
		$hex        = "0123456789ABCDEF";

		// loop through each character and convert all unescaped X's to 1-9 and
		// unescaped x's to 0-9.
		$new_str = "";
		for ($i=0; $i<strlen($str); $i++) {
			switch ($str[$i]) {
				// Numbers
				case "X": $new_str .= rand(1,9);  break;
				case "x": $new_str .= rand(0,9);  break;

				// Letters
				case "L": $new_str .= $letters[rand(0, strlen($letters)-1)]; break;
				case "l": $new_str .= strtolower($letters[rand(0, strlen($letters)-1)]); break;
				case "D":
					$bool = rand()&1;
					if ($bool) {
						$new_str .= $letters[rand(0, strlen($letters)-1)];
					} else {
						$new_str .= strtolower($letters[rand(0, strlen($letters)-1)]);
					}
					break;

				// Consonants
				case "C": $new_str .= $consonants[rand(0, strlen($consonants)-1)];      break;
				case "c": $new_str .= strtolower($consonants[rand(0, strlen($consonants)-1)]);  break;
				case "E":
					$bool = rand()&1;
					if ($bool) {
						$new_str .= $consonants[rand(0, strlen($consonants)-1)];
					} else {
						$new_str .= strtolower($consonants[rand(0, strlen($consonants)-1)]);
					}
					break;

				// Vowels
				case "V": $new_str .= $vowels[rand(0, strlen($vowels)-1)];  break;
				case "v": $new_str .= strtolower($vowels[rand(0, strlen($vowels)-1)]);  break;
				case "F":
					$bool = rand()&1;
					if ($bool) {
						$new_str .= $vowels[rand(0, strlen($vowels)-1)];
					} else {
						$new_str .= strtolower($vowels[rand(0, strlen($vowels)-1)]);
					}
					break;

				case "H":
					$new_str .= $hex[rand(0, strlen($hex)-1)];
					break;

				default:
					$new_str .= $str[$i];
					break;
			}
		}

		return trim($new_str);
	}


	/**
	 * Returns an array of lorem ipsum words. Assumes that a file exists in a misc/ subfolder called
	 * loremipsum.txt, containing lorem ipsum text.
	 *
	 * TODO this seems a good candidate to memoize...
	 *
	 * @return array a large array of words
	 */
	function getLipsum() {
		$prefix = Core::getDbTablePrefix();

		// grab all the words in the text files & put them in an array (1 word per index)
		$response = Core::$db->query("
			SELECT *
			FROM {$prefix}settings
			WHERE setting_name = 'lipsum'
		");

		if ($response["success"]) {
			$info = mysql_fetch_assoc($response["results"]);
			$words = preg_split("/\s+/", $info["setting_value"]);
			return $words;
		}
	}


	/**
	 * Generates a string of lorem ipsum words.
	 *
	 * @param string $starts_with_lipsum  - true/false
	 * @param string $type                - "fixed"/"range"
	 * @param integer $min     - the minimum # of words to return OR the total number
	 * @param integer $max     - the max # of words to return (or null for "fixed" type)
	 */
	public function generateRandomTextStr($words, $startsWithLipsum, $type, $min, $max = "") {
		// determine the number of words to return
		$index = 0;
		if ($type == "fixed") {
			$numWords = $min;
		} else if ($type == "range") {
			$numWords = rand($min, $max);
		}

		if ($numWords > count($words)) {
			$numWords = count($words);
		}

		// determine the offset
		$offset = 0;
		if (!$startsWithLipsum) {
			$offset = rand(2, count($words) - ($numWords + 1));
		}
		$wordArray = array_slice($words, $offset, $numWords);

		return join(" ", $wordArray);
	}


	/**
	 * Converts all x's and X's in a string with a random digit. X's: 1-9, x's: 0-9.
	 */
	public function generateRandomNumStr($str) {
		// loop through each character and convert all unescaped X's to 1-9 and
		// unescaped x's to 0-9.
		$new_str = "";
		for ($i=0; $i<strlen($str); $i++) {
			if ($str[$i] == '\\' && ($str[$i+1] == "X" || $str[$i+1] == "x")) {
				continue;
			} else if ($str[$i] == "X") {
				if ($i != 0 && ($str[$i-1] == '\\')) {
					$new_str .= "X";
				} else {
					$new_str .= rand(1,9);
				}
			} else if ($str[$i] == "x") {
				if ($i != 0 && ($str[$i-1] == '\\')) {
					$new_str .= "x";
				} else {
					$new_str .= rand(0,9);
				}
			} else {
				$new_str .= $str[$i];
			}
		}

		return trim($new_str);
	}



	public function maybeShowInstallationPage() {
		if (!Core::checkIsInstalled()) {
			$query_string = (isset($_GET["source"]) && in_array($_GET["source"], array("fromerrorpage"))) ?
				"?source={$_GET["source"]}" : "";

			header("location: install.php{$query_string}");
			exit;
		}
	}

	public function enquoteArray($arr, $char = "\"") {
		$newArr = array();
		foreach ($arr as $item) {
			$newArr[] = "{$char}$item{$char}";
		}
		return $newArr;
	}

	public static function isHash($var) {
		if (!is_array($var)) {
			return false;
		}
		return array_keys($var) !== range(0, sizeof($var)-1);
	}

	/**
	 * A method to recursively encode an array (associative or indexed).
	 * @param $array
	 */
	public static function utf8_encode_array($array) {

		// if the parameter wasn't an array, explicitly return false
		if (!is_array($array)) {
			return false;
		}

		$resultArray = array();
		foreach ($array as $key => $value) {
			if (Utils::isHash($array)) {
				if (is_array($value)) {
					$resultArray[utf8_encode($key)] = Utils::utf8_encode_array($value);
				} else {
					if (is_string($value)) {
						$resultArray[utf8_encode($key)] = utf8_encode($value);
					} else {
						$resultArray[utf8_encode($key)] = $value;
					}
				}
			} else {
				if (is_array($value)) {
					$resultArray[$key] = Utils::utf8_encode_array($value);
				} else {
					if (is_string($value)) {
						$resultArray[$key] = utf8_encode($value);
					} else {
						$resultArray[$key] = $value;
					}
				}
			}
		}
		return $resultArray;
	}
}