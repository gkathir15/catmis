<?
class Theme {
	var $author = "";
	var $description = "";
	var $folder = "";
	var $link = "";
	var $name = "";
	var $subthemes = array();
	var $title = "";
	
	function Theme($folder="") {
		if (file_exists(scriptPath."/theme/layout/".$folder."/about.php")) {
			include scriptPath."/theme/layout/".$folder."/about.php";
			$this->author = !empty($author)?$author:"";
			$this->description = !empty($description)?$description:"";
			$this->folder = $folder;
			$this->link = !empty($link)?$link:"";
			$this->name = !empty($name)?$name:"";
			$this->subthemes = !empty($subthemes)?$subthemes:array();
			$this->title = !empty($title)?$title:"";
		}
	}
	
	function getNumberOfSubthemes() {
		return sizeof($this->subthemes);	
	}

	function getSubthemePreviewURL($subtheme) {
		if (file_exists(scriptPath."/theme/layout/".$this->folder."/preview/".$subtheme.".jpg")) {
			return scriptUrl."/theme/layout/".$this->folder."/preview/".$subtheme.".jpg";
		}
		return iconUrl."/noPreview.jpg";
	}
	
	function getThemePreviewURL() {
		if (file_exists(scriptPath."/theme/layout/".$this->folder."/preview/".$this->folder.".jpg")) {
			return scriptUrl."/theme/layout/".$this->folder."/preview/".$this->folder.".jpg";
		}
		return iconUrl."/noPreview.jpg";
	}
}
?>