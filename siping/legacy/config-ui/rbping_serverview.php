<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg8.php" ?>
<?php include_once "ewmysql8.php" ?>
<?php include_once "phpfn8.php" ?>
<?php include_once "rbping_serverinfo.php" ?>
<?php include_once "userfn8.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
$rbping_server_view = new crbping_server_view();
$Page =& $rbping_server_view;

// Page init
$rbping_server_view->Page_Init();

// Page main
$rbping_server_view->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($rbping_server->Export == "") { ?>
<script type="text/javascript">
<!--

// Create page object
var rbping_server_view = new ew_Page("rbping_server_view");

// page properties
rbping_server_view.PageID = "view"; // page ID
rbping_server_view.FormID = "frbping_serverview"; // form ID
var EW_PAGE_ID = rbping_server_view.PageID; // for backward compatibility

// extend page with Form_CustomValidate function
rbping_server_view.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
rbping_server_view.SelectAllKey = function(elem) {
	ew_SelectAll(elem);
	ew_ClickAll(elem);
}
<?php if (EW_CLIENT_VALIDATE) { ?>
rbping_server_view.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
rbping_server_view.ValidateRequired = false; // no JavaScript validation
<?php } ?>

//-->
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
//-->

</script>
<?php } ?>
<p class="phpmaker ewTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $rbping_server->TableCaption() ?>
&nbsp;&nbsp;<?php $rbping_server_view->ExportOptions->Render("body"); ?>
</p>
<?php if ($rbping_server->Export == "") { ?>
<p class="phpmaker">
<a href="<?php echo $rbping_server_view->ListUrl ?>"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<a href="<?php echo $rbping_server_view->AddUrl ?>"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<a href="<?php echo $rbping_server_view->EditUrl ?>"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<a href="<?php echo $rbping_server_view->CopyUrl ?>"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<a onclick="return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));" href="<?php echo $rbping_server_view->DeleteUrl ?>"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
</p>
<?php $rbping_server_view->ShowPageHeader(); ?>
<?php
$rbping_server_view->ShowMessage();
?>
<p>
<?php if ($rbping_server->Export == "") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($rbping_server_view->Pager)) $rbping_server_view->Pager = new cPrevNextPager($rbping_server_view->StartRec, $rbping_server_view->DisplayRecs, $rbping_server_view->TotalRecs) ?>
<?php if ($rbping_server_view->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($rbping_server_view->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($rbping_server_view->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $rbping_server_view->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($rbping_server_view->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($rbping_server_view->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $rbping_server_view->Pager->PageCount ?></span></td>
	</tr></table>
<?php } else { ?>
	<?php if ($rbping_server_view->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
	</tr>
</table>
</form>
<br>
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" class="ewTable">
<?php if ($rbping_server->agent_id->Visible) { // agent_id ?>
	<tr id="r_agent_id"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->agent_id->FldCaption() ?></td>
		<td<?php echo $rbping_server->agent_id->CellAttributes() ?>>
<div<?php echo $rbping_server->agent_id->ViewAttributes() ?>><?php echo $rbping_server->agent_id->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->host->Visible) { // host ?>
	<tr id="r_host"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->host->FldCaption() ?></td>
		<td<?php echo $rbping_server->host->CellAttributes() ?>>
<div<?php echo $rbping_server->host->ViewAttributes() ?>><?php echo $rbping_server->host->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->ip_addr->Visible) { // ip_addr ?>
	<tr id="r_ip_addr"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->ip_addr->FldCaption() ?></td>
		<td<?php echo $rbping_server->ip_addr->CellAttributes() ?>>
<div<?php echo $rbping_server->ip_addr->ViewAttributes() ?>><?php echo $rbping_server->ip_addr->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->protocol->Visible) { // protocol ?>
	<tr id="r_protocol"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->protocol->FldCaption() ?></td>
		<td<?php echo $rbping_server->protocol->CellAttributes() ?>>
<div<?php echo $rbping_server->protocol->ViewAttributes() ?>><?php echo $rbping_server->protocol->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->port->Visible) { // port ?>
	<tr id="r_port"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->port->FldCaption() ?></td>
		<td<?php echo $rbping_server->port->CellAttributes() ?>>
<div<?php echo $rbping_server->port->ViewAttributes() ?>><?php echo $rbping_server->port->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->tos->Visible) { // tos ?>
	<tr id="r_tos"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->tos->FldCaption() ?></td>
		<td<?php echo $rbping_server->tos->CellAttributes() ?>>
<div<?php echo $rbping_server->tos->ViewAttributes() ?>><?php echo $rbping_server->tos->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->enable->Visible) { // enable ?>
	<tr id="r_enable"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->enable->FldCaption() ?></td>
		<td<?php echo $rbping_server->enable->CellAttributes() ?>>
<div<?php echo $rbping_server->enable->ViewAttributes() ?>><?php echo $rbping_server->enable->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->down_enable->Visible) { // down_enable ?>
	<tr id="r_down_enable"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->down_enable->FldCaption() ?></td>
		<td<?php echo $rbping_server->down_enable->CellAttributes() ?>>
<div<?php echo $rbping_server->down_enable->ViewAttributes() ?>><?php echo $rbping_server->down_enable->ViewValue ?></div></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->polling_cycles->Visible) { // polling_cycles ?>
	<tr id="r_polling_cycles"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->polling_cycles->FldCaption() ?></td>
		<td<?php echo $rbping_server->polling_cycles->CellAttributes() ?>>
<div<?php echo $rbping_server->polling_cycles->ViewAttributes() ?>><?php echo $rbping_server->polling_cycles->ViewValue ?></div></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<?php if ($rbping_server->Export == "") { ?>
<br>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($rbping_server_view->Pager)) $rbping_server_view->Pager = new cPrevNextPager($rbping_server_view->StartRec, $rbping_server_view->DisplayRecs, $rbping_server_view->TotalRecs) ?>
<?php if ($rbping_server_view->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($rbping_server_view->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($rbping_server_view->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $rbping_server_view->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($rbping_server_view->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($rbping_server_view->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_view->PageUrl() ?>start=<?php echo $rbping_server_view->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $rbping_server_view->Pager->PageCount ?></span></td>
	</tr></table>
<?php } else { ?>
	<?php if ($rbping_server_view->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<p>
<?php
$rbping_server_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rbping_server->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rbping_server_view->Page_Terminate();
?>
<?php

//
// Page class
//
class crbping_server_view {

	// Page ID
	var $PageID = 'view';

	// Table name
	var $TableName = 'rbping_server';

	// Page object name
	var $PageObjName = 'rbping_server_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		global $rbping_server;
		if ($rbping_server->UseTokenInUrl) $PageUrl .= "t=" . $rbping_server->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			echo "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			echo "<p class=\"ewSuccessMessage\">" . $sSuccessMessage . "</p>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			echo "<p class=\"ewErrorMessage\">" . $sErrorMessage . "</p>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm, $rbping_server;
		if ($rbping_server->UseTokenInUrl) {
			if ($objForm)
				return ($rbping_server->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($rbping_server->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crbping_server_view() {
		global $conn, $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Table object (rbping_server)
		if (!isset($GLOBALS["rbping_server"])) {
			$GLOBALS["rbping_server"] = new crbping_server();
			$GLOBALS["Table"] =& $GLOBALS["rbping_server"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rbping_server', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->Separator = "&nbsp;&nbsp;";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		global $rbping_server;

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$rbping_server->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$rbping_server->Export = $_POST["exporttype"];
		} else {
			$rbping_server->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $rbping_server->Export; // Get export parameter, used in header
		$gsExportFile = $rbping_server->TableVar; // Get export file, used in header
		$Charset = (EW_CHARSET <> "") ? ";charset=" . EW_CHARSET : ""; // Charset used in header
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
		}
		if ($rbping_server->Export == "excel") {
			header('Content-Type: application/vnd.ms-excel' . $Charset);
			header('Content-Disposition: attachment; filename=' . $gsExportFile .'.xls');
		}
		if ($rbping_server->Export == "word") {
			header('Content-Type: application/vnd.ms-word' . $Charset);
			header('Content-Disposition: attachment; filename=' . $gsExportFile .'.doc');
		}
		if ($rbping_server->Export == "xml") {
			header('Content-Type: text/xml' . $Charset);
			header('Content-Disposition: attachment; filename=' . $gsExportFile .'.xml');
		}
		if ($rbping_server->Export == "csv") {
			header('Content-Type: application/csv' . $Charset);
			header('Content-Disposition: attachment; filename=' . $gsExportFile .'.csv');
		}

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language, $rbping_server;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$rbping_server->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $rbping_server->id->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$rbping_server->CurrentAction = "I"; // Display form
			switch ($rbping_server->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("rbping_serverlist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($rbping_server->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
								$rbping_server->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "rbping_serverlist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if (in_array($rbping_server->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				if ($rbping_server->Export <> "email")
					$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "rbping_serverlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$rbping_server->RowType = EW_ROWTYPE_VIEW;
		$rbping_server->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		global $rbping_server;
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$rbping_server->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$rbping_server->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $rbping_server->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$rbping_server->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$rbping_server->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$rbping_server->setStartRecordNumber($this->StartRec);
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn, $rbping_server;

		// Call Recordset Selecting event
		$rbping_server->Recordset_Selecting($rbping_server->CurrentFilter);

		// Load List page SQL
		$sSql = $rbping_server->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$rbping_server->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $rbping_server;
		$sFilter = $rbping_server->KeyFilter();

		// Call Row Selecting event
		$rbping_server->Row_Selecting($sFilter);

		// Load SQL based on filter
		$rbping_server->CurrentFilter = $sFilter;
		$sSql = $rbping_server->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn, $rbping_server;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row =& $rs->fields;
		$rbping_server->Row_Selected($row);
		$rbping_server->id->setDbValue($rs->fields('id'));
		$rbping_server->agent_id->setDbValue($rs->fields('agent_id'));
		$rbping_server->host->setDbValue($rs->fields('host'));
		$rbping_server->ip_addr->setDbValue($rs->fields('ip_addr'));
		$rbping_server->protocol->setDbValue($rs->fields('protocol'));
		$rbping_server->port->setDbValue($rs->fields('port'));
		$rbping_server->tos->setDbValue($rs->fields('tos'));
		$rbping_server->enable->setDbValue($rs->fields('enable'));
		$rbping_server->down_enable->setDbValue($rs->fields('down_enable'));
		$rbping_server->polling_cycles->setDbValue($rs->fields('polling_cycles'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language, $rbping_server;

		// Initialize URLs
		$this->AddUrl = $rbping_server->AddUrl();
		$this->EditUrl = $rbping_server->EditUrl();
		$this->CopyUrl = $rbping_server->CopyUrl();
		$this->DeleteUrl = $rbping_server->DeleteUrl();
		$this->ListUrl = $rbping_server->ListUrl();

		// Call Row_Rendering event
		$rbping_server->Row_Rendering();

		// Common render codes for all row types
		// id
		// agent_id
		// host
		// ip_addr
		// protocol
		// port
		// tos
		// enable
		// down_enable
		// polling_cycles

		if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View row

			// agent_id
			$rbping_server->agent_id->ViewValue = $rbping_server->agent_id->CurrentValue;
			$rbping_server->agent_id->ViewCustomAttributes = "";

			// host
			$rbping_server->host->ViewValue = $rbping_server->host->CurrentValue;
			$rbping_server->host->ViewCustomAttributes = "";

			// ip_addr
			$rbping_server->ip_addr->ViewValue = $rbping_server->ip_addr->CurrentValue;
			$rbping_server->ip_addr->ViewCustomAttributes = "";

			// protocol
			if (strval($rbping_server->protocol->CurrentValue) <> "") {
				switch ($rbping_server->protocol->CurrentValue) {
					case "icmp":
						$rbping_server->protocol->ViewValue = $rbping_server->protocol->FldTagCaption(1) <> "" ? $rbping_server->protocol->FldTagCaption(1) : $rbping_server->protocol->CurrentValue;
						break;
					case "tcp":
						$rbping_server->protocol->ViewValue = $rbping_server->protocol->FldTagCaption(2) <> "" ? $rbping_server->protocol->FldTagCaption(2) : $rbping_server->protocol->CurrentValue;
						break;
					default:
						$rbping_server->protocol->ViewValue = $rbping_server->protocol->CurrentValue;
				}
			} else {
				$rbping_server->protocol->ViewValue = NULL;
			}
			$rbping_server->protocol->ViewCustomAttributes = "";

			// port
			$rbping_server->port->ViewValue = $rbping_server->port->CurrentValue;
			$rbping_server->port->ViewCustomAttributes = "";

			// tos
			$rbping_server->tos->ViewValue = $rbping_server->tos->CurrentValue;
			$rbping_server->tos->ViewCustomAttributes = "";

			// enable
			if (strval($rbping_server->enable->CurrentValue) <> "") {
				switch ($rbping_server->enable->CurrentValue) {
					case "1":
						$rbping_server->enable->ViewValue = $rbping_server->enable->FldTagCaption(1) <> "" ? $rbping_server->enable->FldTagCaption(1) : $rbping_server->enable->CurrentValue;
						break;
					case "0":
						$rbping_server->enable->ViewValue = $rbping_server->enable->FldTagCaption(2) <> "" ? $rbping_server->enable->FldTagCaption(2) : $rbping_server->enable->CurrentValue;
						break;
					default:
						$rbping_server->enable->ViewValue = $rbping_server->enable->CurrentValue;
				}
			} else {
				$rbping_server->enable->ViewValue = NULL;
			}
			$rbping_server->enable->ViewCustomAttributes = "";

			// down_enable
			if (strval($rbping_server->down_enable->CurrentValue) <> "") {
				switch ($rbping_server->down_enable->CurrentValue) {
					case "1":
						$rbping_server->down_enable->ViewValue = $rbping_server->down_enable->FldTagCaption(1) <> "" ? $rbping_server->down_enable->FldTagCaption(1) : $rbping_server->down_enable->CurrentValue;
						break;
					case "0":
						$rbping_server->down_enable->ViewValue = $rbping_server->down_enable->FldTagCaption(2) <> "" ? $rbping_server->down_enable->FldTagCaption(2) : $rbping_server->down_enable->CurrentValue;
						break;
					default:
						$rbping_server->down_enable->ViewValue = $rbping_server->down_enable->CurrentValue;
				}
			} else {
				$rbping_server->down_enable->ViewValue = NULL;
			}
			$rbping_server->down_enable->ViewCustomAttributes = "";

			// polling_cycles
			$rbping_server->polling_cycles->ViewValue = $rbping_server->polling_cycles->CurrentValue;
			$rbping_server->polling_cycles->ViewCustomAttributes = "";

			// agent_id
			$rbping_server->agent_id->LinkCustomAttributes = "";
			$rbping_server->agent_id->HrefValue = "";
			$rbping_server->agent_id->TooltipValue = "";

			// host
			$rbping_server->host->LinkCustomAttributes = "";
			$rbping_server->host->HrefValue = "";
			$rbping_server->host->TooltipValue = "";

			// ip_addr
			$rbping_server->ip_addr->LinkCustomAttributes = "";
			$rbping_server->ip_addr->HrefValue = "";
			$rbping_server->ip_addr->TooltipValue = "";

			// protocol
			$rbping_server->protocol->LinkCustomAttributes = "";
			$rbping_server->protocol->HrefValue = "";
			$rbping_server->protocol->TooltipValue = "";

			// port
			$rbping_server->port->LinkCustomAttributes = "";
			$rbping_server->port->HrefValue = "";
			$rbping_server->port->TooltipValue = "";

			// tos
			$rbping_server->tos->LinkCustomAttributes = "";
			$rbping_server->tos->HrefValue = "";
			$rbping_server->tos->TooltipValue = "";

			// enable
			$rbping_server->enable->LinkCustomAttributes = "";
			$rbping_server->enable->HrefValue = "";
			$rbping_server->enable->TooltipValue = "";

			// down_enable
			$rbping_server->down_enable->LinkCustomAttributes = "";
			$rbping_server->down_enable->HrefValue = "";
			$rbping_server->down_enable->TooltipValue = "";

			// polling_cycles
			$rbping_server->polling_cycles->LinkCustomAttributes = "";
			$rbping_server->polling_cycles->HrefValue = "";
			$rbping_server->polling_cycles->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($rbping_server->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$rbping_server->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language, $rbping_server;

		// Printer friendly
		$item =& $this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\">" . "<img src=\"phpimages/print.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendly")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendly")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item =& $this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\">" . "<img src=\"phpimages/exportxls.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcel")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcel")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item =& $this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\">" . "<img src=\"phpimages/exportdoc.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToWord")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWord")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item =& $this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\">" . "<img src=\"phpimages/exporthtml.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtml")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtml")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item =& $this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\">" . "<img src=\"phpimages/exportxml.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToXml")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXml")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item =& $this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\">" . "<img src=\"phpimages/exportcsv.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsv")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsv")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item =& $this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\">" . "<img src=\"phpimages/exportpdf.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToPdf")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPdf")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item =& $this->ExportOptions->Add("email");
		$item->Body = "<a name=\"emf_rbping_server\" id=\"emf_rbping_server\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_rbping_server',hdr:ewLanguage.Phrase('ExportToEmail'),key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . "<img src=\"phpimages/exportemail.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToEmail")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToEmail")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = FALSE;

		// Hide options for export/action
		if ($rbping_server->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		global $rbping_server;
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $rbping_server->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs < 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		if ($rbping_server->Export == "xml") {
			$XmlDoc = new cXMLDocument(EW_XML_ENCODING);
		} else {
			$ExportDoc = new cExportDocument($rbping_server, "v");
		}
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		if ($rbping_server->Export == "xml") {
			$rbping_server->ExportXmlDocument($XmlDoc, ($ParentTable <> ""), $rs, $StartRec, $StopRec, "view");
		} else {
			$sHeader = $this->PageHeader;
			$this->Page_DataRendering($sHeader);
			$ExportDoc->Text .= $sHeader;
			$rbping_server->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
			$sFooter = $this->PageFooter;
			$this->Page_DataRendered($sFooter);
			$ExportDoc->Text .= $sFooter;
		}

		// Close recordset
		$rs->Close();

		// Export header and footer
		if ($rbping_server->Export <> "xml") {
			$ExportDoc->ExportHeaderAndFooter();
		}

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write BOM if utf-8
		if ($utf8 && !in_array($rbping_server->Export, array("email", "xml")))
			echo "\xEF\xBB\xBF";

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		if ($rbping_server->Export == "xml") {
			header("Content-Type: text/xml");
			echo $XmlDoc->XML();
		} elseif ($rbping_server->Export == "email") {
			$this->ExportEmail($ExportDoc->Text);
			$this->Page_Terminate($rbping_server->ExportReturnUrl());
		} elseif ($rbping_server->Export == "pdf") {
			$this->ExportPDF($ExportDoc->Text);
		} else {
			echo $ExportDoc->Text;
		}
	}

	// Export PDF
	function ExportPDF($html) {
		global $gsExportFile;
		include_once "dompdf060b2/dompdf_config.inc.php";
		@ini_set("memory_limit", EW_PDF_MEMORY_LIMIT);
		set_time_limit(EW_PDF_TIME_LIMIT);
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		ob_end_clean();
		ew_DeleteTmpImages();
		$dompdf->stream($gsExportFile . ".pdf", array("Attachment" => 1)); // 0 to open in browser, 1 to download

//		exit();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
