<?php
// This script and data application were generated by AppGini 5.70
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/property_photos.php");
	include("$currDir/property_photos_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('property_photos');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "property_photos";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`property_photos`.`id`" => "id",
		"IF(    CHAR_LENGTH(`properties1`.`id`) || CHAR_LENGTH(`properties1`.`property_name`), CONCAT_WS('',   `properties1`.`id`, ' / ', `properties1`.`property_name`), '') /* Property */" => "property",
		"`property_photos`.`photo`" => "photo",
		"`property_photos`.`description`" => "description"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`property_photos`.`id`',
		2 => 2,
		3 => 3,
		4 => 4
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`property_photos`.`id`" => "id",
		"IF(    CHAR_LENGTH(`properties1`.`id`) || CHAR_LENGTH(`properties1`.`property_name`), CONCAT_WS('',   `properties1`.`id`, ' / ', `properties1`.`property_name`), '') /* Property */" => "property",
		"`property_photos`.`photo`" => "photo",
		"`property_photos`.`description`" => "description"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`property_photos`.`id`" => "ID",
		"IF(    CHAR_LENGTH(`properties1`.`id`) || CHAR_LENGTH(`properties1`.`property_name`), CONCAT_WS('',   `properties1`.`id`, ' / ', `properties1`.`property_name`), '') /* Property */" => "Property",
		"`property_photos`.`description`" => "Description"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`property_photos`.`id`" => "id",
		"IF(    CHAR_LENGTH(`properties1`.`id`) || CHAR_LENGTH(`properties1`.`property_name`), CONCAT_WS('',   `properties1`.`id`, ' / ', `properties1`.`property_name`), '') /* Property */" => "property",
		"`property_photos`.`description`" => "description"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'property' => 'Property');

	$x->QueryFrom = "`property_photos` LEFT JOIN `properties` as properties1 ON `properties1`.`id`=`property_photos`.`property` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = true;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "property_photos_view.php";
	$x->RedirectAfterInsert = "property_photos_view.php?SelectedID=#ID#";
	$x->TableTitle = "Property photos";
	$x->TableIcon = "resources/table_icons/camera_link.png";
	$x->PrimaryKey = "`property_photos`.`id`";

	$x->ColWidth   = array(  150, 150, 150);
	$x->ColCaption = array("Property", "Photo", "Description");
	$x->ColFieldName = array('property', 'photo', 'description');
	$x->ColNumber  = array(2, 3, 4);

	// template paths below are based on the app main directory
	$x->Template = 'templates/property_photos_templateTV.html';
	$x->SelectedTemplate = 'templates/property_photos_templateTVS.html';
	$x->TemplateDV = 'templates/property_photos_templateDV.html';
	$x->TemplateDVP = 'templates/property_photos_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->ShowRecordSlots = 0;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `property_photos`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='property_photos' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `property_photos`.`id`=membership_userrecords.pkValue and membership_userrecords.tableName='property_photos' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`property_photos`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: property_photos_init
	$render=TRUE;
	if(function_exists('property_photos_init')){
		$args=array();
		$render=property_photos_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: property_photos_header
	$headerCode='';
	if(function_exists('property_photos_header')){
		$args=array();
		$headerCode=property_photos_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: property_photos_footer
	$footerCode='';
	if(function_exists('property_photos_footer')){
		$args=array();
		$footerCode=property_photos_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>