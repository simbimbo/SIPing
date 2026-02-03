<?php

// Global variable for table object
$rbping_server = NULL;

//
// Table class for rbping_server
//
class crbping_server {
	var $TableVar = 'rbping_server';
	var $TableName = 'rbping_server';
	var $TableType = 'TABLE';
	var $id;
	var $agent_id;
	var $host;
	var $ip_addr;
	var $protocol;
	var $port;
	var $tos;
	var $enable;
	var $down_enable;
	var $polling_cycles;
	var $fields = array();
	var $UseTokenInUrl = EW_USE_TOKEN_IN_URL;
	var $Export; // Export
	var $ExportOriginalValue = EW_EXPORT_ORIGINAL_VALUE;
	var $ExportAll = TRUE;
	var $ExportPageBreakCount = 0; // Page break per every n record (PDF only)
	var $SendEmail; // Send email
	var $TableCustomInnerHtml; // Custom inner HTML
	var $BasicSearchKeyword; // Basic search keyword
	var $BasicSearchType; // Basic search type
	var $CurrentFilter; // Current filter
	var $CurrentOrder; // Current order
	var $CurrentOrderType; // Current order type
	var $RowType; // Row type
	var $CssClass; // CSS class
	var $CssStyle; // CSS style
	var $RowAttrs = array(); // Row custom attributes

	// Reset attributes for table object
	function ResetAttrs() {
		$this->CssClass = "";
		$this->CssStyle = "";
    	$this->RowAttrs = array();
		foreach ($this->fields as $fld) {
			$fld->ResetAttrs();
		}
	}

	// Setup field titles
	function SetupFieldTitles() {
		foreach ($this->fields as &$fld) {
			if (strval($fld->FldTitle()) <> "") {
				$fld->EditAttrs["onmouseover"] = "ew_ShowTitle(this, '" . ew_JsEncode3($fld->FldTitle()) . "');";
				$fld->EditAttrs["onmouseout"] = "ew_HideTooltip();";
			}
		}
	}
	var $TableFilter = "";
	var $CurrentAction; // Current action
	var $LastAction; // Last action
	var $CurrentMode = ""; // Current mode
	var $UpdateConflict; // Update conflict
	var $EventName; // Event name
	var $EventCancelled; // Event cancelled
	var $CancelMessage; // Cancel message
	var $AllowAddDeleteRow = TRUE; // Allow add/delete row
	var $DetailAdd = TRUE; // Allow detail add
	var $DetailEdit = TRUE; // Allow detail edit
	var $GridAddRowCount = 5;

	// Check current action
	// - Add
	function IsAdd() {
		return $this->CurrentAction == "add";
	}

	// - Copy
	function IsCopy() {
		return $this->CurrentAction == "copy" || $this->CurrentAction == "C";
	}

	// - Edit
	function IsEdit() {
		return $this->CurrentAction == "edit";
	}

	// - Delete
	function IsDelete() {
		return $this->CurrentAction == "D";
	}

	// - Confirm
	function IsConfirm() {
		return $this->CurrentAction == "F";
	}

	// - Overwrite
	function IsOverwrite() {
		return $this->CurrentAction == "overwrite";
	}

	// - Cancel
	function IsCancel() {
		return $this->CurrentAction == "cancel";
	}

	// - Grid add
	function IsGridAdd() {
		return $this->CurrentAction == "gridadd";
	}

	// - Grid edit
	function IsGridEdit() {
		return $this->CurrentAction == "gridedit";
	}

	// - Insert
	function IsInsert() {
		return $this->CurrentAction == "insert" || $this->CurrentAction == "A";
	}

	// - Update
	function IsUpdate() {
		return $this->CurrentAction == "update" || $this->CurrentAction == "U";
	}

	// - Grid update
	function IsGridUpdate() {
		return $this->CurrentAction == "gridupdate";
	}

	// - Grid insert
	function IsGridInsert() {
		return $this->CurrentAction == "gridinsert";
	}

	// - Grid overwrite
	function IsGridOverwrite() {
		return $this->CurrentAction == "gridoverwrite";
	}

	// Check last action
	// - Cancelled
	function IsCanceled() {
		return $this->LastAction == "cancel" && $this->CurrentAction == "";
	}

	// - Inline inserted
	function IsInlineInserted() {
		return $this->LastAction == "insert" && $this->CurrentAction == "";
	}

	// - Inline updated
	function IsInlineUpdated() {
		return $this->LastAction == "update" && $this->CurrentAction == "";
	}

	// - Grid updated
	function IsGridUpdated() {
		return $this->LastAction == "gridupdate" && $this->CurrentAction == "";
	}

	// - Grid inserted
	function IsGridInserted() {
		return $this->LastAction == "gridinsert" && $this->CurrentAction == "";
	}

	//
	// Table class constructor
	//
	function crbping_server() {
		global $Language;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row

		// id
		$this->id = new cField('rbping_server', 'rbping_server', 'x_id', 'id', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] =& $this->id;

		// agent_id
		$this->agent_id = new cField('rbping_server', 'rbping_server', 'x_agent_id', 'agent_id', '`agent_id`', 200, -1, FALSE, '`agent_id`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['agent_id'] =& $this->agent_id;

		// host
		$this->host = new cField('rbping_server', 'rbping_server', 'x_host', 'host', '`host`', 200, -1, FALSE, '`host`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['host'] =& $this->host;

		// ip_addr
		$this->ip_addr = new cField('rbping_server', 'rbping_server', 'x_ip_addr', 'ip_addr', '`ip_addr`', 200, -1, FALSE, '`ip_addr`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ip_addr'] =& $this->ip_addr;

		// protocol
		$this->protocol = new cField('rbping_server', 'rbping_server', 'x_protocol', 'protocol', '`protocol`', 200, -1, FALSE, '`protocol`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['protocol'] =& $this->protocol;

		// port
		$this->port = new cField('rbping_server', 'rbping_server', 'x_port', 'port', '`port`', 3, -1, FALSE, '`port`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->port->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['port'] =& $this->port;

		// tos
		$this->tos = new cField('rbping_server', 'rbping_server', 'x_tos', 'tos', '`tos`', 3, -1, FALSE, '`tos`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->tos->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tos'] =& $this->tos;

		// enable
		$this->enable = new cField('rbping_server', 'rbping_server', 'x_enable', 'enable', '`enable`', 3, -1, FALSE, '`enable`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->enable->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['enable'] =& $this->enable;

		// down_enable
		$this->down_enable = new cField('rbping_server', 'rbping_server', 'x_down_enable', 'down_enable', '`down_enable`', 3, -1, FALSE, '`down_enable`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->down_enable->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['down_enable'] =& $this->down_enable;

		// polling_cycles
		$this->polling_cycles = new cField('rbping_server', 'rbping_server', 'x_polling_cycles', 'polling_cycles', '`polling_cycles`', 3, -1, FALSE, '`polling_cycles`', FALSE, FALSE, 'FORMATTED TEXT');
		$this->polling_cycles->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['polling_cycles'] =& $this->polling_cycles;
	}

	// Get field values
	function GetFieldValues($propertyname) {
		$values = array();
		foreach ($this->fields as $fldname => $fld)
			$values[$fldname] =& $fld->$propertyname;
		return $values;
	}

	// Table caption
	function TableCaption() {
		global $Language;
		return $Language->TablePhrase($this->TableVar, "TblCaption");
	}

	// Page caption
	function PageCaption($Page) {
		global $Language;
		$Caption = $Language->TablePhrase($this->TableVar, "TblPageCaption" . $Page);
		if ($Caption == "") $Caption = "Page " . $Page;
		return $Caption;
	}

	// Export return page
	function ExportReturnUrl() {
		$url = @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_EXPORT_RETURN_URL];
		return ($url <> "") ? $url : ew_CurrentPage();
	}

	function setExportReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_EXPORT_RETURN_URL] = $v;
	}

	// Records per page
	function getRecordsPerPage() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_REC_PER_PAGE];
	}

	function setRecordsPerPage($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_REC_PER_PAGE] = $v;
	}

	// Start record number
	function getStartRecordNumber() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_START_REC];
	}

	function setStartRecordNumber($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_START_REC] = $v;
	}

	// Search highlight name
	function HighlightName() {
		return "rbping_server_Highlight";
	}

	// Advanced search
	function getAdvancedSearch($fld) {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ADVANCED_SEARCH . "_" . $fld];
	}

	function setAdvancedSearch($fld, $v) {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ADVANCED_SEARCH . "_" . $fld] <> $v) {
			$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ADVANCED_SEARCH . "_" . $fld] = $v;
		}
	}

	// Basic search keyword
	function getSessionBasicSearchKeyword() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH];
	}

	function setSessionBasicSearchKeyword($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH] = $v;
	}

	// Basic search type
	function getSessionBasicSearchType() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH_TYPE];
	}

	function setSessionBasicSearchType($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_BASIC_SEARCH_TYPE] = $v;
	}

	// Search WHERE clause
	function getSearchWhere() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_SEARCH_WHERE];
	}

	function setSearchWhere($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_SEARCH_WHERE] = $v;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session WHERE clause
	function getSessionWhere() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_WHERE];
	}

	function setSessionWhere($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_WHERE] = $v;
	}

	// Session ORDER BY
	function getSessionOrderBy() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY];
	}

	function setSessionOrderBy($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY] = $v;
	}

	// Session key
	function getKey($fld) {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_KEY . "_" . $fld];
	}

	function setKey($fld, $v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_KEY . "_" . $fld] = $v;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`rbping_server`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (EW_PAGE_ID) {
			case "add":
			case "register":
			case "addopt":
				return ;
			case "edit":
			case "update":
				return ;
			case "delete":
				return ;
			case "view":
				return ;
			case "search":
				return ;
			default:
				return ;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($this->SelectSQL())) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		if (substr($names, -1) == ",") $names = substr($names, 0, strlen($names)-1);
		if (substr($values, -1) == ",") $values = substr($values, 0, strlen($values)-1);
		return "INSERT INTO `rbping_server` ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		global $conn;
		$SQL = "UPDATE `rbping_server` SET ";
		foreach ($rs as $name => $value) {
			$SQL .= $this->fields[$name]->FldExpression . "=";
			$SQL .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		if (substr($SQL, -1) == ",") $SQL = substr($SQL, 0, strlen($SQL)-1);
		if ($this->CurrentFilter <> "")	$SQL .= " WHERE " . $this->CurrentFilter;
		return $SQL;
	}

	// DELETE statement
	function DeleteSQL(&$rs) {
		$SQL = "DELETE FROM `rbping_server` WHERE ";
		$SQL .= ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "rbping_serverlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function ListUrl() {
		return "rbping_serverlist.php";
	}

	// View URL
	function ViewUrl() {
		return $this->KeyUrl("rbping_serverview.php", $this->UrlParm());
	}

	// Add URL
	function AddUrl() {
		$AddUrl = "rbping_serveradd.php";

//		$sUrlParm = $this->UrlParm();
//		if ($sUrlParm <> "")
//			$AddUrl .= "?" . $sUrlParm;

		return $AddUrl;
	}

	// Edit URL
	function EditUrl($parm = "") {
		return $this->KeyUrl("rbping_serveredit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function InlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function CopyUrl($parm = "") {
		return $this->KeyUrl("rbping_serveradd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function InlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function DeleteUrl() {
		return $this->KeyUrl("rbping_serverdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Add URL parameter
	function UrlParm($parm = "") {
		$UrlParm = ($this->UseTokenInUrl) ? "t=rbping_server" : "";
		if ($parm <> "") {
			if ($UrlParm <> "")
				$UrlParm .= "&";
			$UrlParm .= $parm;
		}
		return $UrlParm;
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->agent_id->setDbValue($rs->fields('agent_id'));
		$this->host->setDbValue($rs->fields('host'));
		$this->ip_addr->setDbValue($rs->fields('ip_addr'));
		$this->protocol->setDbValue($rs->fields('protocol'));
		$this->port->setDbValue($rs->fields('port'));
		$this->tos->setDbValue($rs->fields('tos'));
		$this->enable->setDbValue($rs->fields('enable'));
		$this->down_enable->setDbValue($rs->fields('down_enable'));
		$this->polling_cycles->setDbValue($rs->fields('polling_cycles'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// agent_id
		// host
		// ip_addr
		// protocol
		// port
		// tos
		// enable
		// down_enable
		// polling_cycles

		$this->polling_cycles->CellCssStyle = "white-space: nowrap;";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// agent_id
		$this->agent_id->ViewValue = $this->agent_id->CurrentValue;
		$this->agent_id->ViewCustomAttributes = "";

		// host
		$this->host->ViewValue = $this->host->CurrentValue;
		$this->host->ViewCustomAttributes = "";

		// ip_addr
		$this->ip_addr->ViewValue = $this->ip_addr->CurrentValue;
		$this->ip_addr->ViewCustomAttributes = "";

		// protocol
		if (strval($this->protocol->CurrentValue) <> "") {
			switch ($this->protocol->CurrentValue) {
				case "icmp":
					$this->protocol->ViewValue = $this->protocol->FldTagCaption(1) <> "" ? $this->protocol->FldTagCaption(1) : $this->protocol->CurrentValue;
					break;
				case "tcp":
					$this->protocol->ViewValue = $this->protocol->FldTagCaption(2) <> "" ? $this->protocol->FldTagCaption(2) : $this->protocol->CurrentValue;
					break;
				default:
					$this->protocol->ViewValue = $this->protocol->CurrentValue;
			}
		} else {
			$this->protocol->ViewValue = NULL;
		}
		$this->protocol->ViewCustomAttributes = "";

		// port
		$this->port->ViewValue = $this->port->CurrentValue;
		$this->port->ViewCustomAttributes = "";

		// tos
		$this->tos->ViewValue = $this->tos->CurrentValue;
		$this->tos->ViewCustomAttributes = "";

		// enable
		if (strval($this->enable->CurrentValue) <> "") {
			switch ($this->enable->CurrentValue) {
				case "1":
					$this->enable->ViewValue = $this->enable->FldTagCaption(1) <> "" ? $this->enable->FldTagCaption(1) : $this->enable->CurrentValue;
					break;
				case "0":
					$this->enable->ViewValue = $this->enable->FldTagCaption(2) <> "" ? $this->enable->FldTagCaption(2) : $this->enable->CurrentValue;
					break;
				default:
					$this->enable->ViewValue = $this->enable->CurrentValue;
			}
		} else {
			$this->enable->ViewValue = NULL;
		}
		$this->enable->ViewCustomAttributes = "";

		// down_enable
		if (strval($this->down_enable->CurrentValue) <> "") {
			switch ($this->down_enable->CurrentValue) {
				case "1":
					$this->down_enable->ViewValue = $this->down_enable->FldTagCaption(1) <> "" ? $this->down_enable->FldTagCaption(1) : $this->down_enable->CurrentValue;
					break;
				case "0":
					$this->down_enable->ViewValue = $this->down_enable->FldTagCaption(2) <> "" ? $this->down_enable->FldTagCaption(2) : $this->down_enable->CurrentValue;
					break;
				default:
					$this->down_enable->ViewValue = $this->down_enable->CurrentValue;
			}
		} else {
			$this->down_enable->ViewValue = NULL;
		}
		$this->down_enable->ViewCustomAttributes = "";

		// polling_cycles
		$this->polling_cycles->ViewValue = $this->polling_cycles->CurrentValue;
		$this->polling_cycles->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// agent_id
		$this->agent_id->LinkCustomAttributes = "";
		$this->agent_id->HrefValue = "";
		$this->agent_id->TooltipValue = "";

		// host
		$this->host->LinkCustomAttributes = "";
		$this->host->HrefValue = "";
		$this->host->TooltipValue = "";

		// ip_addr
		$this->ip_addr->LinkCustomAttributes = "";
		$this->ip_addr->HrefValue = "";
		$this->ip_addr->TooltipValue = "";

		// protocol
		$this->protocol->LinkCustomAttributes = "";
		$this->protocol->HrefValue = "";
		$this->protocol->TooltipValue = "";

		// port
		$this->port->LinkCustomAttributes = "";
		$this->port->HrefValue = "";
		$this->port->TooltipValue = "";

		// tos
		$this->tos->LinkCustomAttributes = "";
		$this->tos->HrefValue = "";
		$this->tos->TooltipValue = "";

		// enable
		$this->enable->LinkCustomAttributes = "";
		$this->enable->HrefValue = "";
		$this->enable->TooltipValue = "";

		// down_enable
		$this->down_enable->LinkCustomAttributes = "";
		$this->down_enable->HrefValue = "";
		$this->down_enable->TooltipValue = "";

		// polling_cycles
		$this->polling_cycles->LinkCustomAttributes = "";
		$this->polling_cycles->HrefValue = "";
		$this->polling_cycles->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in Xml Format
	function ExportXmlDocument(&$XmlDoc, $HasParent, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$XmlDoc)
			return;
		if (!$HasParent)
			$XmlDoc->AddRoot($this->TableVar);

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if ($HasParent)
					$XmlDoc->AddRow($this->TableVar);
				else
					$XmlDoc->AddRow();
				if ($ExportPageType == "view") {
					$XmlDoc->AddField('agent_id', $this->agent_id->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('host', $this->host->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('ip_addr', $this->ip_addr->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('protocol', $this->protocol->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('port', $this->port->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('tos', $this->tos->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('enable', $this->enable->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('down_enable', $this->down_enable->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('polling_cycles', $this->polling_cycles->ExportValue($this->Export, $this->ExportOriginalValue));
				} else {
					$XmlDoc->AddField('agent_id', $this->agent_id->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('host', $this->host->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('ip_addr', $this->ip_addr->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('protocol', $this->protocol->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('port', $this->port->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('tos', $this->tos->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('enable', $this->enable->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('down_enable', $this->down_enable->ExportValue($this->Export, $this->ExportOriginalValue));
					$XmlDoc->AddField('polling_cycles', $this->polling_cycles->ExportValue($this->Export, $this->ExportOriginalValue));
				}
			}
			$Recordset->MoveNext();
		}
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				$Doc->ExportCaption($this->agent_id);
				$Doc->ExportCaption($this->host);
				$Doc->ExportCaption($this->ip_addr);
				$Doc->ExportCaption($this->protocol);
				$Doc->ExportCaption($this->port);
				$Doc->ExportCaption($this->tos);
				$Doc->ExportCaption($this->enable);
				$Doc->ExportCaption($this->down_enable);
				$Doc->ExportCaption($this->polling_cycles);
			} else {
				$Doc->ExportCaption($this->agent_id);
				$Doc->ExportCaption($this->host);
				$Doc->ExportCaption($this->ip_addr);
				$Doc->ExportCaption($this->protocol);
				$Doc->ExportCaption($this->port);
				$Doc->ExportCaption($this->tos);
				$Doc->ExportCaption($this->enable);
				$Doc->ExportCaption($this->down_enable);
				$Doc->ExportCaption($this->polling_cycles);
			}
			if ($this->Export == "pdf") {
				$Doc->EndExportRow(TRUE);
			} else {
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break for PDF
				if ($this->Export == "pdf" && $this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					$Doc->ExportField($this->agent_id);
					$Doc->ExportField($this->host);
					$Doc->ExportField($this->ip_addr);
					$Doc->ExportField($this->protocol);
					$Doc->ExportField($this->port);
					$Doc->ExportField($this->tos);
					$Doc->ExportField($this->enable);
					$Doc->ExportField($this->down_enable);
					$Doc->ExportField($this->polling_cycles);
				} else {
					$Doc->ExportField($this->agent_id);
					$Doc->ExportField($this->host);
					$Doc->ExportField($this->ip_addr);
					$Doc->ExportField($this->protocol);
					$Doc->ExportField($this->port);
					$Doc->ExportField($this->tos);
					$Doc->ExportField($this->enable);
					$Doc->ExportField($this->down_enable);
					$Doc->ExportField($this->polling_cycles);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Row styles
	function RowStyles() {
		$sAtt = "";
		$sStyle = trim($this->CssStyle);
		if (@$this->RowAttrs["style"] <> "")
			$sStyle .= " " . $this->RowAttrs["style"];
		$sClass = trim($this->CssClass);
		if (@$this->RowAttrs["class"] <> "")
			$sClass .= " " . $this->RowAttrs["class"];
		if (trim($sStyle) <> "")
			$sAtt .= " style=\"" . trim($sStyle) . "\"";
		if (trim($sClass) <> "")
			$sAtt .= " class=\"" . trim($sClass) . "\"";
		return $sAtt;
	}

	// Row attributes
	function RowAttributes() {
		$sAtt = $this->RowStyles();
		if ($this->Export == "") {
			foreach ($this->RowAttrs as $k => $v) {
				if ($k <> "class" && $k <> "style" && trim($v) <> "")
					$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
			}
		}
		return $sAtt;
	}

	// Field object by name
	function fields($fldname) {
		return $this->fields[$fldname];
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}
}
?>
