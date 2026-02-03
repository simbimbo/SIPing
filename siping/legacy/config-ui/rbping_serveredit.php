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
$rbping_server_edit = new crbping_server_edit();
$Page =& $rbping_server_edit;

// Page init
$rbping_server_edit->Page_Init();

// Page main
$rbping_server_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">
<!--

// Create page object
var rbping_server_edit = new ew_Page("rbping_server_edit");

// page properties
rbping_server_edit.PageID = "edit"; // page ID
rbping_server_edit.FormID = "frbping_serveredit"; // form ID
var EW_PAGE_ID = rbping_server_edit.PageID; // for backward compatibility

// extend page with ValidateForm function
rbping_server_edit.ValidateForm = function(fobj) {
	ew_PostAutoSuggest(fobj);
	if (!this.ValidateRequired)
		return true; // ignore validation
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var i, elm, aelm, infix;
	var rowcnt = 1;
	for (i=0; i<rowcnt; i++) {
		infix = "";
		elm = fobj.elements["x" + infix + "_agent_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->agent_id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_host"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->host->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_ip_addr"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->ip_addr->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_protocol"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->protocol->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_port"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->port->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_port"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($rbping_server->port->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_tos"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->tos->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_tos"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($rbping_server->tos->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_enable"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->enable->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_down_enable"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->down_enable->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_polling_cycles"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($rbping_server->polling_cycles->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_polling_cycles"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($rbping_server->polling_cycles->FldErrMsg()) ?>");

		// Set up row object
		var row = {};
		row["index"] = infix;
		for (var j = 0; j < fobj.elements.length; j++) {
			var el = fobj.elements[j];
			var len = infix.length + 2;
			if (el.name.substr(0, len) == "x" + infix + "_") {
				var elname = "x_" + el.name.substr(len);
				if (ewLang.isObject(row[elname])) { // already exists
					if (ewLang.isArray(row[elname])) {
						row[elname][row[elname].length] = el; // add to array
					} else {
						row[elname] = [row[elname], el]; // convert to array
					}
				} else {
					row[elname] = el;
				}
			}
		}
		fobj.row = row;

		// Call Form Custom Validate event
		if (!this.Form_CustomValidate(fobj)) return false;
	}

	// Process detail page
	var detailpage = (fobj.detailpage) ? fobj.detailpage.value : "";
	if (detailpage != "") {
		return eval(detailpage+".ValidateForm(fobj)");
	}
	return true;
}

// extend page with Form_CustomValidate function
rbping_server_edit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
rbping_server_edit.SelectAllKey = function(elem) {
	ew_SelectAll(elem);
	ew_ClickAll(elem);
}
<?php if (EW_CLIENT_VALIDATE) { ?>
rbping_server_edit.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
rbping_server_edit.ValidateRequired = false; // no JavaScript validation
<?php } ?>

//-->
</script>
<script type="text/javascript">
<!--
var ew_DHTMLEditors = [];

//-->
</script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
//-->

</script>
<p class="phpmaker ewTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $rbping_server->TableCaption() ?></p>
<p class="phpmaker"><a href="<?php echo $rbping_server->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $rbping_server_edit->ShowPageHeader(); ?>
<?php
$rbping_server_edit->ShowMessage();
?>
<form name="frbping_serveredit" id="frbping_serveredit" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return rbping_server_edit.ValidateForm(this);">
<p>
<input type="hidden" name="a_table" id="a_table" value="rbping_server">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" class="ewTable">
<?php if ($rbping_server->agent_id->Visible) { // agent_id ?>
	<tr id="r_agent_id"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->agent_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->agent_id->CellAttributes() ?>><span id="el_agent_id">
<input type="text" name="x_agent_id" id="x_agent_id" size="30" maxlength="20" value="<?php echo $rbping_server->agent_id->EditValue ?>"<?php echo $rbping_server->agent_id->EditAttributes() ?>>
</span><?php echo $rbping_server->agent_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->host->Visible) { // host ?>
	<tr id="r_host"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->host->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->host->CellAttributes() ?>><span id="el_host">
<input type="text" name="x_host" id="x_host" size="30" maxlength="30" value="<?php echo $rbping_server->host->EditValue ?>"<?php echo $rbping_server->host->EditAttributes() ?>>
</span><?php echo $rbping_server->host->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->ip_addr->Visible) { // ip_addr ?>
	<tr id="r_ip_addr"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->ip_addr->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->ip_addr->CellAttributes() ?>><span id="el_ip_addr">
<input type="text" name="x_ip_addr" id="x_ip_addr" size="30" maxlength="20" value="<?php echo $rbping_server->ip_addr->EditValue ?>"<?php echo $rbping_server->ip_addr->EditAttributes() ?>>
</span><?php echo $rbping_server->ip_addr->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->protocol->Visible) { // protocol ?>
	<tr id="r_protocol"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->protocol->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->protocol->CellAttributes() ?>><span id="el_protocol">
<select id="x_protocol" name="x_protocol"<?php echo $rbping_server->protocol->EditAttributes() ?>>
<?php
if (is_array($rbping_server->protocol->EditValue)) {
	$arwrk = $rbping_server->protocol->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rbping_server->protocol->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span><?php echo $rbping_server->protocol->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->port->Visible) { // port ?>
	<tr id="r_port"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->port->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->port->CellAttributes() ?>><span id="el_port">
<input type="text" name="x_port" id="x_port" size="30" value="<?php echo $rbping_server->port->EditValue ?>"<?php echo $rbping_server->port->EditAttributes() ?>>
</span><?php echo $rbping_server->port->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->tos->Visible) { // tos ?>
	<tr id="r_tos"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->tos->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->tos->CellAttributes() ?>><span id="el_tos">
<input type="text" name="x_tos" id="x_tos" size="30" value="<?php echo $rbping_server->tos->EditValue ?>"<?php echo $rbping_server->tos->EditAttributes() ?>>
</span><?php echo $rbping_server->tos->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->enable->Visible) { // enable ?>
	<tr id="r_enable"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->enable->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->enable->CellAttributes() ?>><span id="el_enable">
<select id="x_enable" name="x_enable"<?php echo $rbping_server->enable->EditAttributes() ?>>
<?php
if (is_array($rbping_server->enable->EditValue)) {
	$arwrk = $rbping_server->enable->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rbping_server->enable->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span><?php echo $rbping_server->enable->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->down_enable->Visible) { // down_enable ?>
	<tr id="r_down_enable"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->down_enable->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->down_enable->CellAttributes() ?>><span id="el_down_enable">
<select id="x_down_enable" name="x_down_enable"<?php echo $rbping_server->down_enable->EditAttributes() ?>>
<?php
if (is_array($rbping_server->down_enable->EditValue)) {
	$arwrk = $rbping_server->down_enable->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rbping_server->down_enable->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span><?php echo $rbping_server->down_enable->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($rbping_server->polling_cycles->Visible) { // polling_cycles ?>
	<tr id="r_polling_cycles"<?php echo $rbping_server->RowAttributes() ?>>
		<td class="ewTableHeader"><?php echo $rbping_server->polling_cycles->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td>
		<td<?php echo $rbping_server->polling_cycles->CellAttributes() ?>><span id="el_polling_cycles">
<input type="text" name="x_polling_cycles" id="x_polling_cycles" size="30" value="<?php echo $rbping_server->polling_cycles->EditValue ?>"<?php echo $rbping_server->polling_cycles->EditAttributes() ?>>
</span><?php echo $rbping_server->polling_cycles->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($rbping_server->id->CurrentValue) ?>">
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<?php
$rbping_server_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php include_once "footer.php" ?>
<?php
$rbping_server_edit->Page_Terminate();
?>
<?php

//
// Page class
//
class crbping_server_edit {

	// Page ID
	var $PageID = 'edit';

	// Table name
	var $TableName = 'rbping_server';

	// Page object name
	var $PageObjName = 'rbping_server_edit';

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
	function crbping_server_edit() {
		global $conn, $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Table object (rbping_server)
		if (!isset($GLOBALS["rbping_server"])) {
			$GLOBALS["rbping_server"] = new crbping_server();
			$GLOBALS["Table"] =& $GLOBALS["rbping_server"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rbping_server', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		global $rbping_server;

		// Create form object
		$objForm = new cFormObj();

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $rbping_server;

		// Load key from QueryString
		if (@$_GET["id"] <> "")
			$rbping_server->id->setQueryStringValue($_GET["id"]);
		if (@$_POST["a_edit"] <> "") {
			$rbping_server->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$rbping_server->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$rbping_server->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		} else {
			$rbping_server->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($rbping_server->id->CurrentValue == "")
			$this->Page_Terminate("rbping_serverlist.php"); // Invalid key, return to list
		switch ($rbping_server->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("rbping_serverlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$rbping_server->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $rbping_server->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "rbping_serverview.php")
						$sReturnUrl = $rbping_server->ViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$rbping_server->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$rbping_server->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$rbping_server->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $rbping_server;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = 0;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm, $rbping_server;
		if (!$rbping_server->agent_id->FldIsDetailKey) {
			$rbping_server->agent_id->setFormValue($objForm->GetValue("x_agent_id"));
		}
		if (!$rbping_server->host->FldIsDetailKey) {
			$rbping_server->host->setFormValue($objForm->GetValue("x_host"));
		}
		if (!$rbping_server->ip_addr->FldIsDetailKey) {
			$rbping_server->ip_addr->setFormValue($objForm->GetValue("x_ip_addr"));
		}
		if (!$rbping_server->protocol->FldIsDetailKey) {
			$rbping_server->protocol->setFormValue($objForm->GetValue("x_protocol"));
		}
		if (!$rbping_server->port->FldIsDetailKey) {
			$rbping_server->port->setFormValue($objForm->GetValue("x_port"));
		}
		if (!$rbping_server->tos->FldIsDetailKey) {
			$rbping_server->tos->setFormValue($objForm->GetValue("x_tos"));
		}
		if (!$rbping_server->enable->FldIsDetailKey) {
			$rbping_server->enable->setFormValue($objForm->GetValue("x_enable"));
		}
		if (!$rbping_server->down_enable->FldIsDetailKey) {
			$rbping_server->down_enable->setFormValue($objForm->GetValue("x_down_enable"));
		}
		if (!$rbping_server->polling_cycles->FldIsDetailKey) {
			$rbping_server->polling_cycles->setFormValue($objForm->GetValue("x_polling_cycles"));
		}
		if (!$rbping_server->id->FldIsDetailKey)
			$rbping_server->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm, $rbping_server;
		$this->LoadRow();
		$rbping_server->id->CurrentValue = $rbping_server->id->FormValue;
		$rbping_server->agent_id->CurrentValue = $rbping_server->agent_id->FormValue;
		$rbping_server->host->CurrentValue = $rbping_server->host->FormValue;
		$rbping_server->ip_addr->CurrentValue = $rbping_server->ip_addr->FormValue;
		$rbping_server->protocol->CurrentValue = $rbping_server->protocol->FormValue;
		$rbping_server->port->CurrentValue = $rbping_server->port->FormValue;
		$rbping_server->tos->CurrentValue = $rbping_server->tos->FormValue;
		$rbping_server->enable->CurrentValue = $rbping_server->enable->FormValue;
		$rbping_server->down_enable->CurrentValue = $rbping_server->down_enable->FormValue;
		$rbping_server->polling_cycles->CurrentValue = $rbping_server->polling_cycles->FormValue;
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
		} elseif ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// agent_id
			$rbping_server->agent_id->EditCustomAttributes = "";
			$rbping_server->agent_id->EditValue = ew_HtmlEncode($rbping_server->agent_id->CurrentValue);

			// host
			$rbping_server->host->EditCustomAttributes = "";
			$rbping_server->host->EditValue = ew_HtmlEncode($rbping_server->host->CurrentValue);

			// ip_addr
			$rbping_server->ip_addr->EditCustomAttributes = "";
			$rbping_server->ip_addr->EditValue = ew_HtmlEncode($rbping_server->ip_addr->CurrentValue);

			// protocol
			$rbping_server->protocol->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array("icmp", $rbping_server->protocol->FldTagCaption(1) <> "" ? $rbping_server->protocol->FldTagCaption(1) : "icmp");
			$arwrk[] = array("tcp", $rbping_server->protocol->FldTagCaption(2) <> "" ? $rbping_server->protocol->FldTagCaption(2) : "tcp");
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$rbping_server->protocol->EditValue = $arwrk;

			// port
			$rbping_server->port->EditCustomAttributes = "";
			$rbping_server->port->EditValue = ew_HtmlEncode($rbping_server->port->CurrentValue);

			// tos
			$rbping_server->tos->EditCustomAttributes = "";
			$rbping_server->tos->EditValue = ew_HtmlEncode($rbping_server->tos->CurrentValue);

			// enable
			$rbping_server->enable->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array("1", $rbping_server->enable->FldTagCaption(1) <> "" ? $rbping_server->enable->FldTagCaption(1) : "1");
			$arwrk[] = array("0", $rbping_server->enable->FldTagCaption(2) <> "" ? $rbping_server->enable->FldTagCaption(2) : "0");
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$rbping_server->enable->EditValue = $arwrk;

			// down_enable
			$rbping_server->down_enable->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array("1", $rbping_server->down_enable->FldTagCaption(1) <> "" ? $rbping_server->down_enable->FldTagCaption(1) : "1");
			$arwrk[] = array("0", $rbping_server->down_enable->FldTagCaption(2) <> "" ? $rbping_server->down_enable->FldTagCaption(2) : "0");
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$rbping_server->down_enable->EditValue = $arwrk;

			// polling_cycles
			$rbping_server->polling_cycles->EditCustomAttributes = "";
			$rbping_server->polling_cycles->EditValue = ew_HtmlEncode($rbping_server->polling_cycles->CurrentValue);

			// Edit refer script
			// agent_id

			$rbping_server->agent_id->HrefValue = "";

			// host
			$rbping_server->host->HrefValue = "";

			// ip_addr
			$rbping_server->ip_addr->HrefValue = "";

			// protocol
			$rbping_server->protocol->HrefValue = "";

			// port
			$rbping_server->port->HrefValue = "";

			// tos
			$rbping_server->tos->HrefValue = "";

			// enable
			$rbping_server->enable->HrefValue = "";

			// down_enable
			$rbping_server->down_enable->HrefValue = "";

			// polling_cycles
			$rbping_server->polling_cycles->HrefValue = "";
		}
		if ($rbping_server->RowType == EW_ROWTYPE_ADD ||
			$rbping_server->RowType == EW_ROWTYPE_EDIT ||
			$rbping_server->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$rbping_server->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($rbping_server->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$rbping_server->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError, $rbping_server;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!is_null($rbping_server->agent_id->FormValue) && $rbping_server->agent_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->agent_id->FldCaption());
		}
		if (!is_null($rbping_server->host->FormValue) && $rbping_server->host->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->host->FldCaption());
		}
		if (!is_null($rbping_server->ip_addr->FormValue) && $rbping_server->ip_addr->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->ip_addr->FldCaption());
		}
		if (!is_null($rbping_server->protocol->FormValue) && $rbping_server->protocol->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->protocol->FldCaption());
		}
		if (!is_null($rbping_server->port->FormValue) && $rbping_server->port->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->port->FldCaption());
		}
		if (!ew_CheckInteger($rbping_server->port->FormValue)) {
			ew_AddMessage($gsFormError, $rbping_server->port->FldErrMsg());
		}
		if (!is_null($rbping_server->tos->FormValue) && $rbping_server->tos->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->tos->FldCaption());
		}
		if (!ew_CheckInteger($rbping_server->tos->FormValue)) {
			ew_AddMessage($gsFormError, $rbping_server->tos->FldErrMsg());
		}
		if (!is_null($rbping_server->enable->FormValue) && $rbping_server->enable->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->enable->FldCaption());
		}
		if (!is_null($rbping_server->down_enable->FormValue) && $rbping_server->down_enable->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->down_enable->FldCaption());
		}
		if (!is_null($rbping_server->polling_cycles->FormValue) && $rbping_server->polling_cycles->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $rbping_server->polling_cycles->FldCaption());
		}
		if (!ew_CheckInteger($rbping_server->polling_cycles->FormValue)) {
			ew_AddMessage($gsFormError, $rbping_server->polling_cycles->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language, $rbping_server;
		$sFilter = $rbping_server->KeyFilter();
		$rbping_server->CurrentFilter = $sFilter;
		$sSql = $rbping_server->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold =& $rs->fields;
			$rsnew = array();

			// agent_id
			$rbping_server->agent_id->SetDbValueDef($rsnew, $rbping_server->agent_id->CurrentValue, "", $rbping_server->agent_id->ReadOnly);

			// host
			$rbping_server->host->SetDbValueDef($rsnew, $rbping_server->host->CurrentValue, "", $rbping_server->host->ReadOnly);

			// ip_addr
			$rbping_server->ip_addr->SetDbValueDef($rsnew, $rbping_server->ip_addr->CurrentValue, "", $rbping_server->ip_addr->ReadOnly);

			// protocol
			$rbping_server->protocol->SetDbValueDef($rsnew, $rbping_server->protocol->CurrentValue, "", $rbping_server->protocol->ReadOnly);

			// port
			$rbping_server->port->SetDbValueDef($rsnew, $rbping_server->port->CurrentValue, 0, $rbping_server->port->ReadOnly);

			// tos
			$rbping_server->tos->SetDbValueDef($rsnew, $rbping_server->tos->CurrentValue, 0, $rbping_server->tos->ReadOnly);

			// enable
			$rbping_server->enable->SetDbValueDef($rsnew, $rbping_server->enable->CurrentValue, 0, $rbping_server->enable->ReadOnly);

			// down_enable
			$rbping_server->down_enable->SetDbValueDef($rsnew, $rbping_server->down_enable->CurrentValue, 0, $rbping_server->down_enable->ReadOnly);

			// polling_cycles
			$rbping_server->polling_cycles->SetDbValueDef($rsnew, $rbping_server->polling_cycles->CurrentValue, 0, $rbping_server->polling_cycles->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $rbping_server->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $conn->Execute($rbping_server->UpdateSQL($rsnew));
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
			} else {
				if ($rbping_server->CancelMessage <> "") {
					$this->setFailureMessage($rbping_server->CancelMessage);
					$rbping_server->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$rbping_server->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
