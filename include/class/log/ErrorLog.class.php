<?
class ErrorLog {
	var $errors = array();
	
	/**
	 * Add error to error log.
	 * @param	$field	Field name to associate with. If empty the error is appended to the end of the log.
	 * @param	$text	Error message to log.
	 */
	function addError($field, $text) {
		if (!empty($field)) {
			$this->errors[$field] = $text;
		}
		else {
			$this->errors[] = $text;
		}
	}

	/**
	 * Get errors for a given field.
	 * @param	$field	Field to get errors for.
	 * @return Error for the given field.
	 */
	function getErrors($field) {
		if (!empty($this->errors[$field])) {
			return $this->errors[$field];
		}
	}
	
	function getNumberOfErrors() {
		return sizeof($this->errors);
	}
	
	function hasError($field) {
		return !empty($this->errors[$field])?true:false;
	}
	
	function hasErrors() {
		return sizeof($this->errors)==0?false:true;
	}
	
	function printErrorMessages() {
		if ($this->hasErrors()) {
			include scriptPath."/include/language/".pageLanguage."/general.php";
			
			echo "<table width=\"100%\" style=\"background-color:#ffff99;border:1px #cccccc solid\">";
			echo "<tr><td width=\"16\"><img src=\"".iconUrl."/warning.gif\" width=\"16\" height=\"16\" title=\"\" alt=\"\" border=\"\" /></td><td width=\"100%\" class=\"small1\"><b>".$lErrors["ErrorsText"]."</b></tr><tr><td colspan=\"2\" class=\"small1\"><ul>";
			foreach($this->errors as $error) {
				echo "<li style=\"color:#cc0000\">".$error."</li>";
			}
			echo "</ul>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
		}
	}
	
	function printWarningIcon($field, $warning="", $visible=0) {
		if (!empty($warning) || !empty($this->errors[$field])) {
			echo "<img name=\"warning_".$field."\" src=\"".iconUrl."/warning.gif\" width=\"16\" height=\"16\" title=\"".(!empty($warning)?$warning:$this->errors[$field])."\" alt=\"".(!empty($warning)?$warning:$this->errors[$field])."\" border=\"\"".(empty($this->errors[$field]) && !$visible?" style=\"display:none\"":"")." />";
		}
	}
	
	function removeError($field) {
		$this->errors[$field] = "";
	}
}
?>