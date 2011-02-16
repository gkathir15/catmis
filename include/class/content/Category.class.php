<?
/** 
 * Class Category contains values of a category on the website
 * and methods for adding, updating and deleting categories.
 * @author	Kaspar Rosengreen Nielsen
 */
class Category extends ModuleContentType {
	public $description = "";
	public $title = "";

	/** 
	 * Category constructor.
	 * @param 	$id 	Identifier of category.
	 * @param	$title	Title of category.
	 */ 
	function __construct($id=0, $title="") {
		parent::__construct("categoryModuleId", "categoryContentTypeId");
	
		// Initialize values		
		$this->init($id, $title);
	}
	
	/**
	 * Add a reference between content on the website and a given category.
	 * @param	$moduleId				Module identifier of content item.
	 * @param	$moduleContentTypeId	Type identifier of content item.
	 * @param 	$moduleContentId		Identifier of content item.
	 * @param	$categoryId				Category to associate with.
	 * @param	$position				Position of category
	 */	
	function addCategoryReference($moduleId, $moduleContentTypeId, $moduleContentId, $categoryId, $position) {
		global $dbi;
		
		// Associate with new keywords
		if (!empty($categoryId)) {
			// Determine if reference already exist
			$result = $dbi->query("SELECT Id FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId)." AND categoryId=".$dbi->quote($categoryId));
			if (!$result->rows()) {
				$dbi->query("INSERT INTO ".categoryContentRefTableName."(moduleId,moduleContentTypeId,moduleContentId,categoryId,position) VALUES(".$dbi->quote($moduleId).",".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",".$dbi->quote($categoryId).",".$position.")");
			}
			
			// Free result
			$result->finish();
		}
	}
	
	/**
	 * Add references between content on the website and a given list of categories.
	 * @param	$moduleId				Module identifier of content item.
	 * @param	$moduleContentTypeId	Type identifier of content item.
	 * @param 	$moduleContentId		Identifier of content item.
	 * @param	$categories				List of categories to associate with.
	 */	
	function addCategoryReferences($moduleId, $moduleContentTypeId, $moduleContentId, $categories) {
		global $dbi;
		
		// Delete all references between module and categories
		$this->deleteCategoryReferences($moduleId, $moduleContentTypeId, $moduleContentId);
		
		// Associate with new keywords				
		for ($i=0; $i<sizeof($categories); $i++) {
			if (!empty($categories[$i])) {
				$categoryTitle = trim($categories[$i]);
				if (!empty($categoryTitle)) {
					$categoryId = 0;
					$result = $dbi->query("SELECT id FROM ".categoryTableName." WHERE title=".$dbi->quote($categoryTitle));
					if ($result->rows()) {
						list($categoryId) = $result->fetchrow_array();
					}
					else {
						$dbi->query("INSERT INTO ".categoryTableName."(title) VALUES(".$dbi->quote($categoryTitle).")");
						$categoryId = $dbi->getInsertId();
					}
					$dbi->query("INSERT INTO ".categoryContentRefTableName."(moduleId,moduleContentTypeId,moduleContentId,categoryId,position) VALUES(".$dbi->quote($moduleId).",".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",".$dbi->quote($categoryId).",".$i.")");

					// Free result
					$result->finish();
				}
			}
		}
	}

	/** Delete this category */
	function deleteCategory() {
		if (!empty($this->id)) {
			if ($this->hasDeletePermission()) {
				global $dbi, $log;
				
				// Check if data is submitted from the form
				checkSubmitter(scriptUrl);
				
				// Delete from category database
				$dbi->query("DELETE FROM ".categoryTableName." WHERE id=".$dbi->quote($this->id));
	
				// Delete all references between category and content
				$dbi->query("DELETE FROM ".categoryContentRefTableName." WHERE categoryId=".$dbi->quote($this->id));
	
				// Delete from log database
				$log->deleteTransaction(categoryContentTypeId, $this->id);
			}
		}
	}

	/** 
	 * Delete all references between module content and categories.
	 * @param	$moduleId				Module identifier of content.
	 * @param	$moduleContentTypeId	Type identifier of content.
	 * @param	$moduleContentId 		Identifier of content.
	 */
	function deleteCategoryReferences($moduleId, $moduleContentTypeId, $moduleContentId) {
		if (!empty($moduleId) && !empty($moduleContentTypeId) && !empty($moduleContentId)) {
			if ($this->hasDeletePermission()) {
				global $dbi;
				
				// Delete associations with existing keywords
				$dbi->query("DELETE FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
			}
		}
	}

	/**
	 * Get link for category.
	 * @return link for category.
	 */
	function getLink($id="") {
		if (!empty($id)) {
			return scriptUrl."/".folderCategory."/".fileCategory."?categoryId=".$id;
		}
		return scriptUrl."/".folderCategory."/".fileCategoryIndex;
	}

	/**
	 * Get name of a given category.
	 * @param	$id	Identifier of category
	 * @return name of category.
	 */		
	function getName($id="") {
		if (!empty($id)) {
			$category = new Category($id);
			return $category->title;
		}
		else {
			global $lCategory;
			return $lCategory["Header"];
		}
	}
	
	/**
	 * Get number of categories in the system.
	 * @return number of categories.
	 */
	function getNumberOfCategories() {
		global $dbi;
		$result = $dbi->query("SELECT COUNT(*) FROM ".categoryTableName);
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
			$result->finish();
			return $count;
		}
		
		// Free result		
		$result->finish();
		return 0;
	}
	
	/** 
	 * Get number of references between category and content. 
	 * @return number of references.
     */
	function getNumberOfReferences() {
		if(!empty($this->id)) {
			global $dbi;

			// Fetch number of references
			$result = $dbi->query("SELECT COUNT(*) FROM ".categoryContentRefTableName." WHERE categoryId=".$dbi->quote($this->id));
			if ($result->rows()) {
				list ($count) = $result->fetchrow_array();
				$result->finish();			
				return $count;
			}
			
			// Free result
			$result->finish();			
		}
		return 0;	
	}

	/**
	 * Initialize Category object.
	 * @param	$id		Category identifier.
	 * @param	$title	Category title.
	 */
	function init($id, $title="") {
		if (!empty($id) || !empty($title)) {
			global $dbi;
	
			// Get category data
			$result = $dbi->query("SELECT id,title,description FROM ".categoryTableName." WHERE ".(!empty($id)?"id=".$dbi->quote($id):"").(!empty($title)?"title=".$dbi->quote(addslashes($title)):""));
			if ($result->rows()) {
				list ($this->id, $this->title, $this->description) = $result->fetchrow_array();
	
				// Parse strings
				$this->title = parseString($this->title);
				$this->description = parseString($this->description);
			}
	
			// Free result
			$result->finish();
		}	
		else {
			$this->id = -1;
		}	
	}

	/** 
	 * Save category in database. 
	 * @return ErrorLog containing errors if any. 
	 */ 
	function saveCategory() {
		// Initialize ErrorLog object
		$errors = new ErrorLog();

		// Check if user has edit permission
		if ($this->hasEditPermission()) {
			global $dbi, $log;
			global $lCategoryEdit;
	
			// Check if data is submitted from the form
			checkSubmitter(scriptUrl);
	
			// Save values
			$this->title = getValue("title");
			$this->description = getValue("description");
	
			// Validate data
			if (empty($this->title)) $errors->addError("title", $lCategoryEdit["MissingTitle"]);
			else if (empty($this->id)) {
				$category = new Category("", $this->title);
				if (!empty($category->id)) {
					$errors->addError("title", $lCategoryEdit["CategoryExists"]);
				}
			}
	
			if (!$errors->hasErrors()) {
				if (!empty($this->id)) {
					// Update category in database
					$dbi->query("UPDATE ".categoryTableName." SET title=".$dbi->quote($this->title).",description=".$dbi->quote($this->description)." WHERE (id=".$dbi->quote($this->id).")");
				}
				else {
					// Insert category into database
					$dbi->query("INSERT INTO ".categoryTableName."(title,description) VALUES(".$dbi->quote($this->title).",".$dbi->quote($this->description).")");
					
					// Get insert id
					$this->id = $dbi->getInsertId();
				}
	
				// Log transaction
				$log->logTransaction(categoryContentTypeId, $this->id);
			}
		}
		return $errors;
	}
}
?>