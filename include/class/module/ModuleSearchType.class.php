<?
interface ModuleSearchType {
	public function getNumberOfSearchResults($searchString);
	public function printSearchResults($searchString, $limit=0, $page=0, $viewAll=0);	
}
?>