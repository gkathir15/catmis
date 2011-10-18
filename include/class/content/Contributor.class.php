<?
class Contributor implements ModuleSearchType {
	var $id = 0;
	var $name = "";
	
	function Contributor($id="") {
		$this->init($id);
	}
	
	function deleteContributor() {
	}
	
	function getContributorArticles() {
	}
	
	function getLink($id="") {
		return scriptUrl;
	}
	
	function getModuleContentTypeId() {
		return contributorContentTypeId;
	}
	
	function getName() {
		return "Bidragsydere";
	}
	
	/**
	 * Get number of search results with a given search string.
	 * @param	$searchString	Search string to get number of results for.
	 * @return	Number of search results.
	 */	
	function getNumberOfSearchResults($searchString) {
		global $dbi;
		
		// Fetch artist hits
		$count = "";
		$result = $dbi->query("SELECT COUNT(*) FROM ".contributorTableName." WHERE MATCH(name) AGAINST ('$searchString' IN BOOLEAN MODE)");
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
		}
		return $count;
	}
	
	function init($id="") {
		if (!empty($id)) {
			global $dbi;
			$result = $dbi->query("SELECT id,name FROM ".contributorTableName." WHERE id=".$dbi->quote($id));
			if ($result->rows()) {
				list($this->id,$this->name) = $result->fetchrow_array();
			}
		}		
	}
	
	/**
	 * Print search results for a given search string.
	 * @param	$searchString
	 * @param	$limit
	 * @param	$page
	 * @param	$viewAll
	 */
	function printSearchResults($searchString, $limit=0, $page=0, $viewAll=0) {
		global $dbi, $login;

		$result = $dbi->query("SELECT id,MATCH(name) AGAINST ('$searchString' IN BOOLEAN MODE) AS score FROM ".contributorTableName." WHERE MATCH(name) AGAINST ('$searchString' IN BOOLEAN MODE) ORDER BY name".(!empty($limit) && $viewAll?" LIMIT ".($limit*$page).",".$limit:(!empty($limit)?" LIMIT ".$limit:"")));
		if($result->rows()) {
			$highlight = str_replace("\"","",stripslashes($searchString));
			for($i=0;(list($id,$score)=$result->fetchrow_array());$i++) {
				$contributor = new Contributor($id);
				printSearchResultItem($searchString, $contributor->name, "Ingen beskrivelse.", $contributor->getLink($id), $score); 
			}
		}
		$result->finish();
	}
	
	function saveContributor() {
	}	
}
?>