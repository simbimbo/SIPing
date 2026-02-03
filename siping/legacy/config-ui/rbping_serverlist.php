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
$rbping_server_list = new crbping_server_list();
$Page =& $rbping_server_list;

// Page init
$rbping_server_list->Page_Init();

// Page main
$rbping_server_list->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($rbping_server->Export == "") { ?>
<script type="text/javascript">
<!--

// Create page object
var rbping_server_list = new ew_Page("rbping_server_list");

// page properties
rbping_server_list.PageID = "list"; // page ID
rbping_server_list.FormID = "frbping_serverlist"; // form ID
var EW_PAGE_ID = rbping_server_list.PageID; // for backward compatibility

// extend page with ValidateForm function
rbping_server_list.ValidateForm = function(fobj) {
	ew_PostAutoSuggest(fobj);
	if (!this.ValidateRequired)
		return true; // ignore validation
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var i, elm, aelm, infix;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	var addcnt = 0;
	for (i=0; i<rowcnt; i++) {
		infix = (fobj.key_count) ? String(i+1) : "";
		var chkthisrow = true;
		if (fobj.a_list && fobj.a_list.value == "gridinsert")
			chkthisrow = !(this.EmptyRow(fobj, infix));
		else
			chkthisrow = true;
		if (chkthisrow) {
			addcnt += 1;
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
		} // End Grid Add checking
	}
	if (fobj.a_list && fobj.a_list.value == "gridinsert" && addcnt == 0) { // No row added
		alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Extend page with empty row check
rbping_server_list.EmptyRow = function(fobj, infix) {
	if (ew_ValueChanged(fobj, infix, "agent_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "host", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ip_addr", false)) return false;
	if (ew_ValueChanged(fobj, infix, "protocol", false)) return false;
	if (ew_ValueChanged(fobj, infix, "port", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tos", false)) return false;
	if (ew_ValueChanged(fobj, infix, "enable", false)) return false;
	if (ew_ValueChanged(fobj, infix, "down_enable", false)) return false;
	if (ew_ValueChanged(fobj, infix, "polling_cycles", false)) return false;
	return true;
}

// extend page with validate function for search
rbping_server_list.ValidateSearch = function(fobj) {
	ew_PostAutoSuggest(fobj);
	if (this.ValidateRequired) {
		var infix = "";
		elm = fobj.elements["x" + infix + "_port"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($rbping_server->port->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_tos"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($rbping_server->tos->FldErrMsg()) ?>");

		// Call Form Custom Validate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}
	for (var i=0; i<fobj.elements.length; i++) {
		var elem = fobj.elements[i];
		if (elem.name.substring(0,2) == "s_" || elem.name.substring(0,3) == "sv_")
			elem.value = "";
	}
	return true;
}

// extend page with Form_CustomValidate function
rbping_server_list.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
rbping_server_list.SelectAllKey = function(elem) {
	ew_SelectAll(elem);
	ew_ClickAll(elem);
}
<?php if (EW_CLIENT_VALIDATE) { ?>
rbping_server_list.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
rbping_server_list.ValidateRequired = false; // no JavaScript validation
<?php } ?>

//-->
</script>
<style type="text/css">

/* main table preview row color */
.ewTablePreviewRow {
	background-color: inherit; /* preview row */
}
</style>
<script language="JavaScript" type="text/javascript">
<!--

// PreviewRow extension
var ew_AjaxDetailsTimer = null;
var EW_PREVIEW_SINGLE_ROW = false;
var EW_PREVIEW_IMAGE_CLASSNAME = "ewPreviewRowImage";
var EW_PREVIEW_SHOW_IMAGE = "phpimages/expand.gif";
var EW_PREVIEW_HIDE_IMAGE = "phpimages/collapse.gif";
var EW_PREVIEW_LOADING_IMAGE = "phpimages/loading.gif";
var EW_PREVIEW_LOADING_TEXT = ewLanguage.Phrase("Loading"); // lang phrase for loading

// add row
function ew_AddRowToTable(r) {
	var row, cell;
	var tb = ewDom.getAncestorByTagName(r, "TBODY");
	if (EW_PREVIEW_SINGLE_ROW) {
		row = ewDom.getElementBy(function(node) { return ewDom.hasClass(node, EW_TABLE_PREVIEW_ROW_CLASSNAME)}, "TR", tb);
		ew_RemoveRowFromTable(row);
	}
	var sr = ewDom.getNextSiblingBy(r, function(node) { return node.tagName == "TR"});
	if (sr && ewDom.hasClass(sr, EW_TABLE_PREVIEW_ROW_CLASSNAME)) {
		row = sr; // existing sibling row
		if (row && row.cells && row.cells[0])
			cell = row.cells[0];
	} else {
		row = tb.insertRow(r.rowIndex); // new row
		if (row) {
			row.className = EW_TABLE_PREVIEW_ROW_CLASSNAME;
			var cell = row.insertCell(0);
			cell.style.borderRight = "0";
			var colcnt = r.cells.length;
			if (r.cells) {
				var spancnt = 0;
				for (var i = 0; i < colcnt; i++)
					spancnt += r.cells[i].colSpan;
				if (spancnt > 0)
					cell.colSpan = spancnt;
			}
			var pt = ewDom.getAncestorByTagName(row, "TABLE");
			if (pt) ew_SetupTable(pt);
		}
	}
	if (cell)
		cell.innerHTML = "<img src=\"" + EW_PREVIEW_LOADING_IMAGE + "\" style=\"border: 0; vertical-align: middle;\"> " + EW_PREVIEW_LOADING_TEXT;
	return row;
}

// remove row
function ew_RemoveRowFromTable(r) {
	if (r && r.parentNode)
		r.parentNode.removeChild(r);
}

// show results in new table row
var ew_AjaxHandleSuccess2 = function(o) {
	if (o.responseText !== undefined) {
		var row = o.argument.row;
		if (!row || !row.cells || !row.cells[0]) return;
		row.cells[0].innerHTML = o.responseText;
		var ct = ewDom.getElementBy(function(node) { return ewDom.hasClass(node, EW_TABLE_CLASS)}, "TABLE", row);
		if (ct) ew_SetupTable(ct);

		//clearTimeout(ew_AjaxDetailsTimer);
		//setTimeout("alert(ew_AjaxDetailsTimer);", 500);

	}
}

// show error in new table row
var ew_AjaxHandleFailure2 = function(o) {
	var row = o.argument.row;
	if (!row || !row.cells || !row.cells[0]) return;
	row.cells[0].innerHTML = o.responseText;
}

// show detail preview by table row expansion
function ew_AjaxShowDetails2(ev, link, url) {
	var img = ewDom.getElementBy(function(node) { return true; }, "IMG", link);
	var r = ewDom.getAncestorByTagName(link, "TR");
	if (!img || !r)
		return;
	var show = (img.src.substr(img.src.length - EW_PREVIEW_SHOW_IMAGE.length) == EW_PREVIEW_SHOW_IMAGE);
	if (show) {
		if (ew_AjaxDetailsTimer)
			clearTimeout(ew_AjaxDetailsTimer);		
		var row = ew_AddRowToTable(r);
		ew_AjaxDetailsTimer = setTimeout(function() { ewConnect.asyncRequest('GET', url, {success: ew_AjaxHandleSuccess2, failure: ew_AjaxHandleFailure2, argument:{id: link, row: row}}) }, 200);
		ewDom.getElementsByClassName(EW_PREVIEW_IMAGE_CLASSNAME, "IMG", r, function(node) {node.src = EW_PREVIEW_SHOW_IMAGE});
		img.src = EW_PREVIEW_HIDE_IMAGE;
	} else {	 
		var sr = ewDom.getNextSiblingBy(r, function(node) { return node.tagName == "TR"});
		if (sr && ewDom.hasClass(sr, EW_TABLE_PREVIEW_ROW_CLASSNAME))
			ew_RemoveRowFromTable(sr);
		img.src = EW_PREVIEW_SHOW_IMAGE;
	}
}

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
<?php } ?>
<?php if (($rbping_server->Export == "") || (EW_EXPORT_MASTER_RECORD && $rbping_server->Export == "print")) { ?>
<?php } ?>
<?php
if ($rbping_server->CurrentAction == "gridadd") {
	$rbping_server->CurrentFilter = "0=1";
	$rbping_server_list->StartRec = 1;
	$rbping_server_list->DisplayRecs = $rbping_server->GridAddRowCount;
	$rbping_server_list->TotalRecs = $rbping_server_list->DisplayRecs;
	$rbping_server_list->StopRec = $rbping_server_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$rbping_server_list->TotalRecs = $rbping_server->SelectRecordCount();
	} else {
		if ($rbping_server_list->Recordset = $rbping_server_list->LoadRecordset())
			$rbping_server_list->TotalRecs = $rbping_server_list->Recordset->RecordCount();
	}
	$rbping_server_list->StartRec = 1;
	if ($rbping_server_list->DisplayRecs <= 0 || ($rbping_server->Export <> "" && $rbping_server->ExportAll)) // Display all records
		$rbping_server_list->DisplayRecs = $rbping_server_list->TotalRecs;
	if (!($rbping_server->Export <> "" && $rbping_server->ExportAll))
		$rbping_server_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$rbping_server_list->Recordset = $rbping_server_list->LoadRecordset($rbping_server_list->StartRec-1, $rbping_server_list->DisplayRecs);
}
?>
<p class="phpmaker ewTitle" style="white-space: nowrap;"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $rbping_server->TableCaption() ?>
&nbsp;&nbsp;<?php $rbping_server_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($rbping_server->Export == "" && $rbping_server->CurrentAction == "") { ?>
<a href="javascript:ew_ToggleSearchPanel(rbping_server_list);" style="text-decoration: none;"><img id="rbping_server_list_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="rbping_server_list_SearchPanel">
<form name="frbping_serverlistsrch" id="frbping_serverlistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>" onsubmit="return rbping_server_list.ValidateSearch(this);">
<input type="hidden" id="t" name="t" value="rbping_server">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$rbping_server_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$rbping_server->RowType = EW_ROWTYPE_SEARCH;

// Render row
$rbping_server->ResetAttrs();
$rbping_server_list->RenderRow();
?>
<div id="xsr_1" class="ewCssTableRow">
	<span id="xsc_agent_id" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->agent_id->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_agent_id" id="z_agent_id" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" name="x_agent_id" id="x_agent_id" size="30" maxlength="20" value="<?php echo $rbping_server->agent_id->EditValue ?>"<?php echo $rbping_server->agent_id->EditAttributes() ?>>
</span>
	</span>
</div>
<div id="xsr_2" class="ewCssTableRow">
	<span id="xsc_host" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->host->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_host" id="z_host" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" name="x_host" id="x_host" size="30" maxlength="30" value="<?php echo $rbping_server->host->EditValue ?>"<?php echo $rbping_server->host->EditAttributes() ?>>
</span>
	</span>
</div>
<div id="xsr_3" class="ewCssTableRow">
	<span id="xsc_ip_addr" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->ip_addr->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ip_addr" id="z_ip_addr" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" name="x_ip_addr" id="x_ip_addr" size="30" maxlength="20" value="<?php echo $rbping_server->ip_addr->EditValue ?>"<?php echo $rbping_server->ip_addr->EditAttributes() ?>>
</span>
	</span>
</div>
<div id="xsr_4" class="ewCssTableRow">
	<span id="xsc_protocol" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->protocol->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_protocol" id="z_protocol" value="="></span>
		<span class="ewSearchField">
<select id="x_protocol" name="x_protocol"<?php echo $rbping_server->protocol->EditAttributes() ?>>
<?php
if (is_array($rbping_server->protocol->EditValue)) {
	$arwrk = $rbping_server->protocol->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rbping_server->protocol->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $rbping_server->protocol->OldValue = "";
?>
</select>
</span>
	</span>
</div>
<div id="xsr_5" class="ewCssTableRow">
	<span id="xsc_port" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->port->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_port" id="z_port" value="="></span>
		<span class="ewSearchField">
<input type="text" name="x_port" id="x_port" size="30" value="<?php echo $rbping_server->port->EditValue ?>"<?php echo $rbping_server->port->EditAttributes() ?>>
</span>
	</span>
</div>
<div id="xsr_6" class="ewCssTableRow">
	<span id="xsc_tos" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->tos->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tos" id="z_tos" value="="></span>
		<span class="ewSearchField">
<input type="text" name="x_tos" id="x_tos" size="30" value="<?php echo $rbping_server->tos->EditValue ?>"<?php echo $rbping_server->tos->EditAttributes() ?>>
</span>
	</span>
</div>
<div id="xsr_7" class="ewCssTableRow">
	<span id="xsc_enable" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->enable->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_enable" id="z_enable" value="="></span>
		<span class="ewSearchField">
<select id="x_enable" name="x_enable"<?php echo $rbping_server->enable->EditAttributes() ?>>
<?php
if (is_array($rbping_server->enable->EditValue)) {
	$arwrk = $rbping_server->enable->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rbping_server->enable->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $rbping_server->enable->OldValue = "";
?>
</select>
</span>
	</span>
</div>
<div id="xsr_8" class="ewCssTableRow">
	<span id="xsc_down_enable" class="ewCssTableCell">
		<span class="ewSearchCaption"><?php echo $rbping_server->down_enable->FldCaption() ?></span>
		<span class="ewSearchOprCell"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_down_enable" id="z_down_enable" value="="></span>
		<span class="ewSearchField">
<select id="x_down_enable" name="x_down_enable"<?php echo $rbping_server->down_enable->EditAttributes() ?>>
<?php
if (is_array($rbping_server->down_enable->EditValue)) {
	$arwrk = $rbping_server->down_enable->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rbping_server->down_enable->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $rbping_server->down_enable->OldValue = "";
?>
</select>
</span>
	</span>
</div>
<div id="xsr_9" class="ewCssTableRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($rbping_server->getSessionBasicSearchKeyword()) ?>">
	<input type="Submit" name="Submit" id="Submit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $rbping_server_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_10" class="ewCssTableRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value=""<?php if ($rbping_server->getSessionBasicSearchType() == "") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($rbping_server->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($rbping_server->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</form>
</div>
<?php } ?>
<?php $rbping_server_list->ShowPageHeader(); ?>
<?php
$rbping_server_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($rbping_server->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($rbping_server->CurrentAction <> "gridadd" && $rbping_server->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($rbping_server_list->Pager)) $rbping_server_list->Pager = new cPrevNextPager($rbping_server_list->StartRec, $rbping_server_list->DisplayRecs, $rbping_server_list->TotalRecs) ?>
<?php if ($rbping_server_list->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($rbping_server_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($rbping_server_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $rbping_server_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($rbping_server_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($rbping_server_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $rbping_server_list->Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $rbping_server_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $rbping_server_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $rbping_server_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($rbping_server_list->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($rbping_server_list->TotalRecs > 0) { ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><table border="0" cellspacing="0" cellpadding="0"><tr><td><?php echo $Language->Phrase("RecordsPerPage") ?>&nbsp;</td><td>
<input type="hidden" id="t" name="t" value="rbping_server">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" id="<?php echo EW_TABLE_REC_PER_PAGE ?>" onchange="this.form.submit();">
<option value="10"<?php if ($rbping_server_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($rbping_server_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($rbping_server_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="ALL"<?php if ($rbping_server->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select></td></tr></table>
		</td>
<?php } ?>
	</tr>
</table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($rbping_server->CurrentAction <> "gridadd" && $rbping_server->CurrentAction <> "gridedit") { // Not grid add/edit mode ?>
<a class="ewGridLink" href="<?php echo $rbping_server_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->InlineAddUrl ?>"><?php echo $Language->Phrase("InlineAddLink") ?></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->GridAddUrl ?>"><?php echo $Language->Phrase("GridAddLink") ?></a>&nbsp;&nbsp;
<?php if ($rbping_server_list->TotalRecs > 0) { ?>
<a class="ewGridLink" href="<?php echo $rbping_server_list->GridEditUrl ?>"><?php echo $Language->Phrase("GridEditLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php if ($rbping_server_list->TotalRecs > 0) { ?>
<a class="ewGridLink" href="" onclick="ew_SubmitSelected(document.frbping_serverlist, '<?php echo $rbping_server_list->MultiDeleteUrl ?>', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;"><?php echo $Language->Phrase("DeleteSelectedLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } else { // Grid add/edit mode ?>
<?php if ($rbping_server->CurrentAction == "gridadd") { ?>
<?php if ($rbping_server->AllowAddDeleteRow) { ?>
<a class="ewGridLink" href="javascript:void(0);" onclick="ew_AddGridRow(this);"><img src='phpimages/addblankrow.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<a class="ewGridLink" href="" onclick="return ew_SubmitForm(rbping_server_list, document.frbping_serverlist);"><img src='phpimages/insert.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridInsertLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridInsertLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->PageUrl() ?>a=cancel"><img src='phpimages/cancel.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<?php if ($rbping_server->CurrentAction == "gridedit") { ?>
<?php if ($rbping_server->AllowAddDeleteRow) { ?>
<a class="ewGridLink" href="javascript:void(0);" onclick="ew_AddGridRow(this);"><img src='phpimages/addblankrow.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<a class="ewGridLink" href="" onclick="return ew_SubmitForm(rbping_server_list, document.frbping_serverlist);"><img src='phpimages/update.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridSaveLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridSaveLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->PageUrl() ?>a=cancel"><img src='phpimages/cancel.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<form name="frbping_serverlist" id="frbping_serverlist" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" id="t" value="rbping_server">
<div id="gmp_rbping_server" class="ewGridMiddlePanel">
<?php if ($rbping_server_list->TotalRecs > 0 || $rbping_server->CurrentAction == "add" || $rbping_server->CurrentAction == "copy") { ?>
<table cellspacing="0" data-rowhighlightclass="ewTableHighlightRow" data-rowselectclass="ewTableSelectRow" data-roweditclass="ewTableEditRow" class="ewTable ewTableSeparate">
<?php echo $rbping_server->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$rbping_server_list->RenderListOptions();

// Render list options (header, left)
$rbping_server_list->ListOptions->Render("header", "left");
?>
<?php if ($rbping_server->agent_id->Visible) { // agent_id ?>
	<?php if ($rbping_server->SortUrl($rbping_server->agent_id) == "") { ?>
		<td><?php echo $rbping_server->agent_id->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->agent_id) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->agent_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($rbping_server->agent_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->agent_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->host->Visible) { // host ?>
	<?php if ($rbping_server->SortUrl($rbping_server->host) == "") { ?>
		<td><?php echo $rbping_server->host->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->host) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->host->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($rbping_server->host->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->host->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->ip_addr->Visible) { // ip_addr ?>
	<?php if ($rbping_server->SortUrl($rbping_server->ip_addr) == "") { ?>
		<td><?php echo $rbping_server->ip_addr->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->ip_addr) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->ip_addr->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($rbping_server->ip_addr->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->ip_addr->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->protocol->Visible) { // protocol ?>
	<?php if ($rbping_server->SortUrl($rbping_server->protocol) == "") { ?>
		<td><?php echo $rbping_server->protocol->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->protocol) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->protocol->FldCaption() ?></td><td style="width: 10px;"><?php if ($rbping_server->protocol->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->protocol->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->port->Visible) { // port ?>
	<?php if ($rbping_server->SortUrl($rbping_server->port) == "") { ?>
		<td><?php echo $rbping_server->port->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->port) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->port->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($rbping_server->port->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->port->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->tos->Visible) { // tos ?>
	<?php if ($rbping_server->SortUrl($rbping_server->tos) == "") { ?>
		<td><?php echo $rbping_server->tos->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->tos) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->tos->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($rbping_server->tos->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->tos->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->enable->Visible) { // enable ?>
	<?php if ($rbping_server->SortUrl($rbping_server->enable) == "") { ?>
		<td><?php echo $rbping_server->enable->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->enable) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->enable->FldCaption() ?></td><td style="width: 10px;"><?php if ($rbping_server->enable->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->enable->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->down_enable->Visible) { // down_enable ?>
	<?php if ($rbping_server->SortUrl($rbping_server->down_enable) == "") { ?>
		<td><?php echo $rbping_server->down_enable->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->down_enable) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $rbping_server->down_enable->FldCaption() ?></td><td style="width: 10px;"><?php if ($rbping_server->down_enable->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->down_enable->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($rbping_server->polling_cycles->Visible) { // polling_cycles ?>
	<?php if ($rbping_server->SortUrl($rbping_server->polling_cycles) == "") { ?>
		<td style="white-space: nowrap;"><?php echo $rbping_server->polling_cycles->FldCaption() ?></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $rbping_server->SortUrl($rbping_server->polling_cycles) ?>',1);">
			<table cellspacing="0" class="ewTableHeaderBtn" style="white-space: nowrap;"><thead><tr><td><?php echo $rbping_server->polling_cycles->FldCaption() ?></td><td style="width: 10px;"><?php if ($rbping_server->polling_cycles->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($rbping_server->polling_cycles->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$rbping_server_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<?php
	if ($rbping_server->CurrentAction == "add" || $rbping_server->CurrentAction == "copy") {
		$rbping_server_list->RowIndex = 1;
		$rbping_server_list->KeyCount = $rbping_server_list->RowIndex;
		if ($rbping_server->CurrentAction == "copy" && !$rbping_server_list->LoadRow())
				$rbping_server->CurrentAction = "add";
		if ($rbping_server->CurrentAction == "add")
			$rbping_server_list->LoadDefaultValues();
		if ($rbping_server->EventCancelled) // Insert failed
			$rbping_server_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$rbping_server->ResetAttrs();
		$rbping_server->CssClass = "ewTableEditRow";
		$rbping_server->RowAttrs = array('onmouseover'=>'this.edit=true;ew_MouseOver(event, this);', 'onmouseout'=>'ew_MouseOut(event, this);', 'onclick'=>'ew_Click(event, this);');
		if (!empty($rbping_server_list->RowIndex))
			$rbping_server->RowAttrs = array_merge($rbping_server->RowAttrs, array('data-rowindex'=>$rbping_server_list->RowIndex, 'id'=>'r' . $rbping_server_list->RowIndex . '_rbping_server'));
		$rbping_server->RowType = EW_ROWTYPE_ADD;

		// Render row
		$rbping_server_list->RenderRow();

		// Render list options
		$rbping_server_list->RenderListOptions();
?>
	<tr<?php echo $rbping_server->RowAttributes() ?>>
<?php

// Render list options (body, left)
$rbping_server_list->ListOptions->Render("body", "left");
?>
	<?php if ($rbping_server->agent_id->Visible) { // agent_id ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" id="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" size="30" maxlength="20" value="<?php echo $rbping_server->agent_id->EditValue ?>"<?php echo $rbping_server->agent_id->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_agent_id" id="o<?php echo $rbping_server_list->RowIndex ?>_agent_id" value="<?php echo ew_HtmlEncode($rbping_server->agent_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->host->Visible) { // host ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_host" id="x<?php echo $rbping_server_list->RowIndex ?>_host" size="30" maxlength="30" value="<?php echo $rbping_server->host->EditValue ?>"<?php echo $rbping_server->host->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_host" id="o<?php echo $rbping_server_list->RowIndex ?>_host" value="<?php echo ew_HtmlEncode($rbping_server->host->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->ip_addr->Visible) { // ip_addr ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" id="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" size="30" maxlength="20" value="<?php echo $rbping_server->ip_addr->EditValue ?>"<?php echo $rbping_server->ip_addr->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_ip_addr" id="o<?php echo $rbping_server_list->RowIndex ?>_ip_addr" value="<?php echo ew_HtmlEncode($rbping_server->ip_addr->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->protocol->Visible) { // protocol ?>
		<td>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_protocol" name="x<?php echo $rbping_server_list->RowIndex ?>_protocol"<?php echo $rbping_server->protocol->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->protocol->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_protocol" id="o<?php echo $rbping_server_list->RowIndex ?>_protocol" value="<?php echo ew_HtmlEncode($rbping_server->protocol->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->port->Visible) { // port ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_port" id="x<?php echo $rbping_server_list->RowIndex ?>_port" size="30" value="<?php echo $rbping_server->port->EditValue ?>"<?php echo $rbping_server->port->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_port" id="o<?php echo $rbping_server_list->RowIndex ?>_port" value="<?php echo ew_HtmlEncode($rbping_server->port->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->tos->Visible) { // tos ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_tos" id="x<?php echo $rbping_server_list->RowIndex ?>_tos" size="30" value="<?php echo $rbping_server->tos->EditValue ?>"<?php echo $rbping_server->tos->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_tos" id="o<?php echo $rbping_server_list->RowIndex ?>_tos" value="<?php echo ew_HtmlEncode($rbping_server->tos->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->enable->Visible) { // enable ?>
		<td>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_enable"<?php echo $rbping_server->enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->enable->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_enable" id="o<?php echo $rbping_server_list->RowIndex ?>_enable" value="<?php echo ew_HtmlEncode($rbping_server->enable->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->down_enable->Visible) { // down_enable ?>
		<td>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_down_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_down_enable"<?php echo $rbping_server->down_enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->down_enable->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_down_enable" id="o<?php echo $rbping_server_list->RowIndex ?>_down_enable" value="<?php echo ew_HtmlEncode($rbping_server->down_enable->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->polling_cycles->Visible) { // polling_cycles ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" id="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" size="30" value="<?php echo $rbping_server->polling_cycles->EditValue ?>"<?php echo $rbping_server->polling_cycles->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" id="o<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" value="<?php echo ew_HtmlEncode($rbping_server->polling_cycles->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$rbping_server_list->ListOptions->Render("body", "right");
?>
	</tr>
<?php
}
?>
<?php
if ($rbping_server->ExportAll && $rbping_server->Export <> "") {
	$rbping_server_list->StopRec = $rbping_server_list->TotalRecs;
} else {

	// Set the last record to display
	if ($rbping_server_list->TotalRecs > $rbping_server_list->StartRec + $rbping_server_list->DisplayRecs - 1)
		$rbping_server_list->StopRec = $rbping_server_list->StartRec + $rbping_server_list->DisplayRecs - 1;
	else
		$rbping_server_list->StopRec = $rbping_server_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = 0;
	if ($objForm->HasValue("key_count") && ($rbping_server->CurrentAction == "gridadd" || $rbping_server->CurrentAction == "gridedit" || $rbping_server->CurrentAction == "F")) {
		$rbping_server_list->KeyCount = $objForm->GetValue("key_count");
		$rbping_server_list->StopRec = $rbping_server_list->KeyCount;
	}
}
$rbping_server_list->RecCnt = $rbping_server_list->StartRec - 1;
if ($rbping_server_list->Recordset && !$rbping_server_list->Recordset->EOF) {
	$rbping_server_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $rbping_server_list->StartRec > 1)
		$rbping_server_list->Recordset->Move($rbping_server_list->StartRec - 1);
} elseif (!$rbping_server->AllowAddDeleteRow && $rbping_server_list->StopRec == 0) {
	$rbping_server_list->StopRec = $rbping_server->GridAddRowCount;
}

// Initialize aggregate
$rbping_server->RowType = EW_ROWTYPE_AGGREGATEINIT;
$rbping_server->ResetAttrs();
$rbping_server_list->RenderRow();
$rbping_server_list->RowCnt = 0;
$rbping_server_list->EditRowCnt = 0;
if ($rbping_server->CurrentAction == "edit")
	$rbping_server_list->RowIndex = 1;
if ($rbping_server->CurrentAction == "gridadd")
	$rbping_server_list->RowIndex = 0;
if ($rbping_server->CurrentAction == "gridedit")
	$rbping_server_list->RowIndex = 0;
while ($rbping_server_list->RecCnt < $rbping_server_list->StopRec) {
	$rbping_server_list->RecCnt++;
	if (intval($rbping_server_list->RecCnt) >= intval($rbping_server_list->StartRec)) {
		$rbping_server_list->RowCnt++;
		if ($rbping_server->CurrentAction == "gridadd" || $rbping_server->CurrentAction == "gridedit" || $rbping_server->CurrentAction == "F") {
			$rbping_server_list->RowIndex++;
			$objForm->Index = $rbping_server_list->RowIndex;
			if ($objForm->HasValue("k_action"))
				$rbping_server_list->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($rbping_server->CurrentAction == "gridadd")
				$rbping_server_list->RowAction = "insert";
			else
				$rbping_server_list->RowAction = "";
		}

		// Set up key count
		$rbping_server_list->KeyCount = $rbping_server_list->RowIndex;

		// Init row class and style
		$rbping_server->ResetAttrs();
		$rbping_server->CssClass = "";
		if ($rbping_server->CurrentAction == "gridadd") {
			$rbping_server_list->LoadDefaultValues(); // Load default values
		} else {
			$rbping_server_list->LoadRowValues($rbping_server_list->Recordset); // Load row values
		}
		$rbping_server->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($rbping_server->CurrentAction == "gridadd") // Grid add
			$rbping_server->RowType = EW_ROWTYPE_ADD; // Render add
		if ($rbping_server->CurrentAction == "gridadd" && $rbping_server->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$rbping_server_list->RestoreCurrentRowFormValues($rbping_server_list->RowIndex); // Restore form values
		if ($rbping_server->CurrentAction == "edit") {
			if ($rbping_server_list->CheckInlineEditKey() && $rbping_server_list->EditRowCnt == 0) { // Inline edit
				$rbping_server->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($rbping_server->CurrentAction == "gridedit") { // Grid edit
			if ($rbping_server->EventCancelled) {
				$rbping_server_list->RestoreCurrentRowFormValues($rbping_server_list->RowIndex); // Restore form values
			}
			if ($rbping_server_list->RowAction == "insert")
				$rbping_server->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$rbping_server->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($rbping_server->CurrentAction == "edit" && $rbping_server->RowType == EW_ROWTYPE_EDIT && $rbping_server->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$rbping_server_list->RestoreFormValues(); // Restore form values
		}
		if ($rbping_server->CurrentAction == "gridedit" && ($rbping_server->RowType == EW_ROWTYPE_EDIT || $rbping_server->RowType == EW_ROWTYPE_ADD) && $rbping_server->EventCancelled) // Update failed
			$rbping_server_list->RestoreCurrentRowFormValues($rbping_server_list->RowIndex); // Restore form values
		if ($rbping_server->RowType == EW_ROWTYPE_EDIT) // Edit row
			$rbping_server_list->EditRowCnt++;
		if ($rbping_server->RowType == EW_ROWTYPE_ADD || $rbping_server->RowType == EW_ROWTYPE_EDIT) { // Add / Edit row
			if ($rbping_server->CurrentAction == "edit") {
				$rbping_server->RowAttrs = array('onmouseover'=>'this.edit=true;ew_MouseOver(event, this);', 'onmouseout'=>'ew_MouseOut(event, this);', 'onclick'=>'ew_Click(event, this);');
				$rbping_server->CssClass = "ewTableEditRow";
			} else {
				$rbping_server->RowAttrs = array('onmouseover'=>'ew_MouseOver(event, this);', 'onmouseout'=>'ew_MouseOut(event, this);', 'onclick'=>'ew_Click(event, this);');
			}
			if (!empty($rbping_server_list->RowIndex))
				$rbping_server->RowAttrs = array_merge($rbping_server->RowAttrs, array('data-rowindex'=>$rbping_server_list->RowIndex, 'id'=>'r' . $rbping_server_list->RowIndex . '_rbping_server'));
		} else {
			$rbping_server->RowAttrs = array('onmouseover'=>'ew_MouseOver(event, this);', 'onmouseout'=>'ew_MouseOut(event, this);', 'onclick'=>'ew_Click(event, this);');
		}

		// Render row
		$rbping_server_list->RenderRow();

		// Render list options
		$rbping_server_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($rbping_server_list->RowAction <> "delete" && $rbping_server_list->RowAction <> "insertdelete" && !($rbping_server_list->RowAction == "insert" && $rbping_server->CurrentAction == "F" && $rbping_server_list->EmptyRow())) {
?>
	<tr<?php echo $rbping_server->RowAttributes() ?>>
<?php

// Render list options (body, left)
$rbping_server_list->ListOptions->Render("body", "left");
?>
	<?php if ($rbping_server->agent_id->Visible) { // agent_id ?>
		<td<?php echo $rbping_server->agent_id->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" id="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" size="30" maxlength="20" value="<?php echo $rbping_server->agent_id->EditValue ?>"<?php echo $rbping_server->agent_id->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_agent_id" id="o<?php echo $rbping_server_list->RowIndex ?>_agent_id" value="<?php echo ew_HtmlEncode($rbping_server->agent_id->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" id="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" size="30" maxlength="20" value="<?php echo $rbping_server->agent_id->EditValue ?>"<?php echo $rbping_server->agent_id->EditAttributes() ?>>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->agent_id->ViewAttributes() ?>><?php echo $rbping_server->agent_id->ListViewValue() ?></div>
<?php } ?>
<a name="<?php echo $rbping_server_list->PageObjName . "_row_" . $rbping_server_list->RowCnt ?>" id="<?php echo $rbping_server_list->PageObjName . "_row_" . $rbping_server_list->RowCnt ?>"></a>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_id" id="o<?php echo $rbping_server_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($rbping_server->id->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { ?>
<input type="hidden" name="x<?php echo $rbping_server_list->RowIndex ?>_id" id="x<?php echo $rbping_server_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($rbping_server->id->CurrentValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->host->Visible) { // host ?>
		<td<?php echo $rbping_server->host->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_host" id="x<?php echo $rbping_server_list->RowIndex ?>_host" size="30" maxlength="30" value="<?php echo $rbping_server->host->EditValue ?>"<?php echo $rbping_server->host->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_host" id="o<?php echo $rbping_server_list->RowIndex ?>_host" value="<?php echo ew_HtmlEncode($rbping_server->host->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_host" id="x<?php echo $rbping_server_list->RowIndex ?>_host" size="30" maxlength="30" value="<?php echo $rbping_server->host->EditValue ?>"<?php echo $rbping_server->host->EditAttributes() ?>>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->host->ViewAttributes() ?>><?php echo $rbping_server->host->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->ip_addr->Visible) { // ip_addr ?>
		<td<?php echo $rbping_server->ip_addr->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" id="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" size="30" maxlength="20" value="<?php echo $rbping_server->ip_addr->EditValue ?>"<?php echo $rbping_server->ip_addr->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_ip_addr" id="o<?php echo $rbping_server_list->RowIndex ?>_ip_addr" value="<?php echo ew_HtmlEncode($rbping_server->ip_addr->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" id="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" size="30" maxlength="20" value="<?php echo $rbping_server->ip_addr->EditValue ?>"<?php echo $rbping_server->ip_addr->EditAttributes() ?>>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->ip_addr->ViewAttributes() ?>><?php echo $rbping_server->ip_addr->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->protocol->Visible) { // protocol ?>
		<td<?php echo $rbping_server->protocol->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_protocol" name="x<?php echo $rbping_server_list->RowIndex ?>_protocol"<?php echo $rbping_server->protocol->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->protocol->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_protocol" id="o<?php echo $rbping_server_list->RowIndex ?>_protocol" value="<?php echo ew_HtmlEncode($rbping_server->protocol->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_protocol" name="x<?php echo $rbping_server_list->RowIndex ?>_protocol"<?php echo $rbping_server->protocol->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->protocol->OldValue = "";
?>
</select>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->protocol->ViewAttributes() ?>><?php echo $rbping_server->protocol->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->port->Visible) { // port ?>
		<td<?php echo $rbping_server->port->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_port" id="x<?php echo $rbping_server_list->RowIndex ?>_port" size="30" value="<?php echo $rbping_server->port->EditValue ?>"<?php echo $rbping_server->port->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_port" id="o<?php echo $rbping_server_list->RowIndex ?>_port" value="<?php echo ew_HtmlEncode($rbping_server->port->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_port" id="x<?php echo $rbping_server_list->RowIndex ?>_port" size="30" value="<?php echo $rbping_server->port->EditValue ?>"<?php echo $rbping_server->port->EditAttributes() ?>>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->port->ViewAttributes() ?>><?php echo $rbping_server->port->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->tos->Visible) { // tos ?>
		<td<?php echo $rbping_server->tos->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_tos" id="x<?php echo $rbping_server_list->RowIndex ?>_tos" size="30" value="<?php echo $rbping_server->tos->EditValue ?>"<?php echo $rbping_server->tos->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_tos" id="o<?php echo $rbping_server_list->RowIndex ?>_tos" value="<?php echo ew_HtmlEncode($rbping_server->tos->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_tos" id="x<?php echo $rbping_server_list->RowIndex ?>_tos" size="30" value="<?php echo $rbping_server->tos->EditValue ?>"<?php echo $rbping_server->tos->EditAttributes() ?>>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->tos->ViewAttributes() ?>><?php echo $rbping_server->tos->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->enable->Visible) { // enable ?>
		<td<?php echo $rbping_server->enable->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_enable"<?php echo $rbping_server->enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->enable->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_enable" id="o<?php echo $rbping_server_list->RowIndex ?>_enable" value="<?php echo ew_HtmlEncode($rbping_server->enable->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_enable"<?php echo $rbping_server->enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->enable->OldValue = "";
?>
</select>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->enable->ViewAttributes() ?>><?php echo $rbping_server->enable->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->down_enable->Visible) { // down_enable ?>
		<td<?php echo $rbping_server->down_enable->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_down_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_down_enable"<?php echo $rbping_server->down_enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->down_enable->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_down_enable" id="o<?php echo $rbping_server_list->RowIndex ?>_down_enable" value="<?php echo ew_HtmlEncode($rbping_server->down_enable->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_down_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_down_enable"<?php echo $rbping_server->down_enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->down_enable->OldValue = "";
?>
</select>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->down_enable->ViewAttributes() ?>><?php echo $rbping_server->down_enable->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($rbping_server->polling_cycles->Visible) { // polling_cycles ?>
		<td<?php echo $rbping_server->polling_cycles->CellAttributes() ?>>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" id="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" size="30" value="<?php echo $rbping_server->polling_cycles->EditValue ?>"<?php echo $rbping_server->polling_cycles->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" id="o<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" value="<?php echo ew_HtmlEncode($rbping_server->polling_cycles->OldValue) ?>">
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" id="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" size="30" value="<?php echo $rbping_server->polling_cycles->EditValue ?>"<?php echo $rbping_server->polling_cycles->EditAttributes() ?>>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div<?php echo $rbping_server->polling_cycles->ViewAttributes() ?>><?php echo $rbping_server->polling_cycles->ListViewValue() ?></div>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$rbping_server_list->ListOptions->Render("body", "right");
?>
	</tr>
<?php if ($rbping_server->RowType == EW_ROWTYPE_ADD) { ?>
<?php } ?>
<?php if ($rbping_server->RowType == EW_ROWTYPE_EDIT) { ?>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($rbping_server->CurrentAction <> "gridadd")
		if (!$rbping_server_list->Recordset->EOF) $rbping_server_list->Recordset->MoveNext();
}
?>
<?php
	if ($rbping_server->CurrentAction == "gridadd" || $rbping_server->CurrentAction == "gridedit") {
		$rbping_server_list->RowIndex = '$rowindex$';
		$rbping_server_list->LoadDefaultValues();

		// Set row properties
		$rbping_server->ResetAttrs();
		$rbping_server->RowAttrs = array('onmouseover'=>'ew_MouseOver(event, this);', 'onmouseout'=>'ew_MouseOut(event, this);', 'onclick'=>'ew_Click(event, this);');
		if (!empty($rbping_server_list->RowIndex))
			$rbping_server->RowAttrs = array_merge($rbping_server->RowAttrs, array('data-rowindex'=>$rbping_server_list->RowIndex, 'id'=>'r' . $rbping_server_list->RowIndex . '_rbping_server'));
		$rbping_server->RowType = EW_ROWTYPE_ADD;

		// Render row
		$rbping_server_list->RenderRow();

		// Render list options
		$rbping_server_list->RenderListOptions();

		// Add id and class to the template row
		$rbping_server->RowAttrs["id"] = "r0_rbping_server";
		ew_AppendClass($rbping_server->RowAttrs["class"], "ewTemplate");
?>
	<tr<?php echo $rbping_server->RowAttributes() ?>>
<?php

// Render list options (body, left)
$rbping_server_list->ListOptions->Render("body", "left");
?>
	<?php if ($rbping_server->agent_id->Visible) { // agent_id ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" id="x<?php echo $rbping_server_list->RowIndex ?>_agent_id" size="30" maxlength="20" value="<?php echo $rbping_server->agent_id->EditValue ?>"<?php echo $rbping_server->agent_id->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_agent_id" id="o<?php echo $rbping_server_list->RowIndex ?>_agent_id" value="<?php echo ew_HtmlEncode($rbping_server->agent_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->host->Visible) { // host ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_host" id="x<?php echo $rbping_server_list->RowIndex ?>_host" size="30" maxlength="30" value="<?php echo $rbping_server->host->EditValue ?>"<?php echo $rbping_server->host->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_host" id="o<?php echo $rbping_server_list->RowIndex ?>_host" value="<?php echo ew_HtmlEncode($rbping_server->host->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->ip_addr->Visible) { // ip_addr ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" id="x<?php echo $rbping_server_list->RowIndex ?>_ip_addr" size="30" maxlength="20" value="<?php echo $rbping_server->ip_addr->EditValue ?>"<?php echo $rbping_server->ip_addr->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_ip_addr" id="o<?php echo $rbping_server_list->RowIndex ?>_ip_addr" value="<?php echo ew_HtmlEncode($rbping_server->ip_addr->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->protocol->Visible) { // protocol ?>
		<td>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_protocol" name="x<?php echo $rbping_server_list->RowIndex ?>_protocol"<?php echo $rbping_server->protocol->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->protocol->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_protocol" id="o<?php echo $rbping_server_list->RowIndex ?>_protocol" value="<?php echo ew_HtmlEncode($rbping_server->protocol->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->port->Visible) { // port ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_port" id="x<?php echo $rbping_server_list->RowIndex ?>_port" size="30" value="<?php echo $rbping_server->port->EditValue ?>"<?php echo $rbping_server->port->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_port" id="o<?php echo $rbping_server_list->RowIndex ?>_port" value="<?php echo ew_HtmlEncode($rbping_server->port->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->tos->Visible) { // tos ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_tos" id="x<?php echo $rbping_server_list->RowIndex ?>_tos" size="30" value="<?php echo $rbping_server->tos->EditValue ?>"<?php echo $rbping_server->tos->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_tos" id="o<?php echo $rbping_server_list->RowIndex ?>_tos" value="<?php echo ew_HtmlEncode($rbping_server->tos->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->enable->Visible) { // enable ?>
		<td>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_enable"<?php echo $rbping_server->enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->enable->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_enable" id="o<?php echo $rbping_server_list->RowIndex ?>_enable" value="<?php echo ew_HtmlEncode($rbping_server->enable->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->down_enable->Visible) { // down_enable ?>
		<td>
<select id="x<?php echo $rbping_server_list->RowIndex ?>_down_enable" name="x<?php echo $rbping_server_list->RowIndex ?>_down_enable"<?php echo $rbping_server->down_enable->EditAttributes() ?>>
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
if (@$emptywrk) $rbping_server->down_enable->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_down_enable" id="o<?php echo $rbping_server_list->RowIndex ?>_down_enable" value="<?php echo ew_HtmlEncode($rbping_server->down_enable->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($rbping_server->polling_cycles->Visible) { // polling_cycles ?>
		<td>
<input type="text" name="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" id="x<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" size="30" value="<?php echo $rbping_server->polling_cycles->EditValue ?>"<?php echo $rbping_server->polling_cycles->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" id="o<?php echo $rbping_server_list->RowIndex ?>_polling_cycles" value="<?php echo ew_HtmlEncode($rbping_server->polling_cycles->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$rbping_server_list->ListOptions->Render("body", "right");
?>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($rbping_server->CurrentAction == "add" || $rbping_server->CurrentAction == "copy") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $rbping_server_list->KeyCount ?>">
<?php } ?>
<?php if ($rbping_server->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $rbping_server_list->KeyCount ?>">
<?php echo $rbping_server_list->MultiSelectKey ?>
<?php } ?>
<?php if ($rbping_server->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $rbping_server_list->KeyCount ?>">
<?php } ?>
<?php if ($rbping_server->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $rbping_server_list->KeyCount ?>">
<?php echo $rbping_server_list->MultiSelectKey ?>
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($rbping_server_list->Recordset)
	$rbping_server_list->Recordset->Close();
?>
<?php if ($rbping_server_list->TotalRecs > 0) { ?>
<?php if ($rbping_server->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($rbping_server->CurrentAction <> "gridadd" && $rbping_server->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($rbping_server_list->Pager)) $rbping_server_list->Pager = new cPrevNextPager($rbping_server_list->StartRec, $rbping_server_list->DisplayRecs, $rbping_server_list->TotalRecs) ?>
<?php if ($rbping_server_list->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($rbping_server_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($rbping_server_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $rbping_server_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($rbping_server_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($rbping_server_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $rbping_server_list->PageUrl() ?>start=<?php echo $rbping_server_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $rbping_server_list->Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $rbping_server_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $rbping_server_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $rbping_server_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($rbping_server_list->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($rbping_server_list->TotalRecs > 0) { ?>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td><table border="0" cellspacing="0" cellpadding="0"><tr><td><?php echo $Language->Phrase("RecordsPerPage") ?>&nbsp;</td><td>
<input type="hidden" id="t" name="t" value="rbping_server">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" id="<?php echo EW_TABLE_REC_PER_PAGE ?>" onchange="this.form.submit();">
<option value="10"<?php if ($rbping_server_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($rbping_server_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($rbping_server_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="ALL"<?php if ($rbping_server->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select></td></tr></table>
		</td>
<?php } ?>
	</tr>
</table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($rbping_server->CurrentAction <> "gridadd" && $rbping_server->CurrentAction <> "gridedit") { // Not grid add/edit mode ?>
<a class="ewGridLink" href="<?php echo $rbping_server_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->InlineAddUrl ?>"><?php echo $Language->Phrase("InlineAddLink") ?></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->GridAddUrl ?>"><?php echo $Language->Phrase("GridAddLink") ?></a>&nbsp;&nbsp;
<?php if ($rbping_server_list->TotalRecs > 0) { ?>
<a class="ewGridLink" href="<?php echo $rbping_server_list->GridEditUrl ?>"><?php echo $Language->Phrase("GridEditLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php if ($rbping_server_list->TotalRecs > 0) { ?>
<a class="ewGridLink" href="" onclick="ew_SubmitSelected(document.frbping_serverlist, '<?php echo $rbping_server_list->MultiDeleteUrl ?>', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;"><?php echo $Language->Phrase("DeleteSelectedLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } else { // Grid add/edit mode ?>
<?php if ($rbping_server->CurrentAction == "gridadd") { ?>
<?php if ($rbping_server->AllowAddDeleteRow) { ?>
<a class="ewGridLink" href="javascript:void(0);" onclick="ew_AddGridRow(this);"><img src='phpimages/addblankrow.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<a class="ewGridLink" href="" onclick="return ew_SubmitForm(rbping_server_list, document.frbping_serverlist);"><img src='phpimages/insert.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridInsertLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridInsertLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->PageUrl() ?>a=cancel"><img src='phpimages/cancel.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<?php if ($rbping_server->CurrentAction == "gridedit") { ?>
<?php if ($rbping_server->AllowAddDeleteRow) { ?>
<a class="ewGridLink" href="javascript:void(0);" onclick="ew_AddGridRow(this);"><img src='phpimages/addblankrow.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("AddBlankRow")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<a class="ewGridLink" href="" onclick="return ew_SubmitForm(rbping_server_list, document.frbping_serverlist);"><img src='phpimages/update.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridSaveLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridSaveLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $rbping_server_list->PageUrl() ?>a=cancel"><img src='phpimages/cancel.gif' alt='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' title='<?php echo ew_HtmlEncode($Language->Phrase("GridCancelLink")) ?>' width='16' height='16' border='0'></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($rbping_server->Export == "" && $rbping_server->CurrentAction == "") { ?>
<?php } ?>
<?php
$rbping_server_list->ShowPageFooter();
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
$rbping_server_list->Page_Terminate();
?>
<?php

//
// Page class
//
class crbping_server_list {

	// Page ID
	var $PageID = 'list';

	// Table name
	var $TableName = 'rbping_server';

	// Page object name
	var $PageObjName = 'rbping_server_list';

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
	function crbping_server_list() {
		global $conn, $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Table object (rbping_server)
		if (!isset($GLOBALS["rbping_server"])) {
			$GLOBALS["rbping_server"] = new crbping_server();
			$GLOBALS["Table"] =& $GLOBALS["rbping_server"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "rbping_serveradd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "rbping_serverdelete.php";
		$this->MultiUpdateUrl = "rbping_serverupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rbping_server', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();

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

		// Create form object
		$objForm = new cFormObj();

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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$rbping_server->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $RowCnt;
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $RestoreSearch;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $rbping_server;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";
		if ($this->IsPageRequest()) { // Validate request

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$rbping_server->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($rbping_server->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($rbping_server->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($rbping_server->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($rbping_server->CurrentAction == "add" || $rbping_server->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($rbping_server->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$rbping_server->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($rbping_server->CurrentAction == "gridupdate" || $rbping_server->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$this->GridUpdate();
						} else {
							$this->setFailureMessage($gsFormError);
							$rbping_server->EventCancelled = TRUE;
							$rbping_server->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($rbping_server->CurrentAction == "update" || $rbping_server->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($rbping_server->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($rbping_server->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$this->GridInsert();
						} else {
							$this->setFailureMessage($gsFormError);
							$rbping_server->EventCancelled = TRUE;
							$rbping_server->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide all options
			if ($rbping_server->Export <> "" ||
				$rbping_server->CurrentAction == "gridadd" ||
				$rbping_server->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($rbping_server->AllowAddDeleteRow) {
				if ($rbping_server->CurrentAction == "gridadd" ||
					$rbping_server->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session
			$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$rbping_server->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($rbping_server->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $rbping_server->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$rbping_server->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->SearchWhere <> "") {
			if ($sSrchBasic == "")
				$this->ResetBasicSearchParms();
			if ($sSrchAdvanced == "")
				$this->ResetAdvancedSearchParms();
			$rbping_server->setSearchWhere($this->SearchWhere); // Save to Session
			if (!$this->RestoreSearch) {
				$this->StartRec = 1; // Reset start record counter
				$rbping_server->setStartRecordNumber($this->StartRec);
			}
		} else {
			$this->SearchWhere = $rbping_server->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$rbping_server->setSessionWhere($sFilter);
		$rbping_server->CurrentFilter = "";

		// Export data only
		if (in_array($rbping_server->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			if ($rbping_server->Export <> "email")
				$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		global $rbping_server;
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$rbping_server->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$rbping_server->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		global $rbping_server;
		$rbping_server->setKey("id", ""); // Clear inline edit key
		$rbping_server->LastAction = $rbping_server->CurrentAction; // Save last action
		$rbping_server->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $rbping_server;
		$bInlineEdit = TRUE;
		if (@$_GET["id"] <> "") {
			$rbping_server->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$rbping_server->setKey("id", $rbping_server->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError, $rbping_server;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;	
			if ($this->CheckInlineEditKey()) { // Check key
				$rbping_server->SendEmail = TRUE; // Send email on update success
				$bInlineUpdate = $this->EditRow(); // Update record
			} else {
				$bInlineUpdate = FALSE;
			}
		}
		if ($bInlineUpdate) { // Update success
			$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$rbping_server->EventCancelled = TRUE; // Cancel event
			$rbping_server->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {
		global $rbping_server;

		//CheckInlineEditKey = True
		if (strval($rbping_server->getKey("id")) <> strval($rbping_server->id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $rbping_server;
		if ($rbping_server->CurrentAction == "copy") {
			if (@$_GET["id"] <> "") {
				$rbping_server->id->setQueryStringValue($_GET["id"]);
				$rbping_server->setKey("id", $rbping_server->id->CurrentValue); // Set up key
			} else {
				$rbping_server->setKey("id", ""); // Clear key
				$rbping_server->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError, $rbping_server;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 1;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$rbping_server->EventCancelled = TRUE; // Set event cancelled
			$rbping_server->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$rbping_server->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$rbping_server->EventCancelled = TRUE; // Set event cancelled
			$rbping_server->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError, $rbping_server;
		$bGridUpdate = TRUE;

		// Begin transaction
		$conn->BeginTrans();

		// Get old recordset
		$rbping_server->CurrentFilter = $this->BuildKeyFilter();
		$sSql = $rbping_server->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = 0;
		$rowcnt = strval($objForm->GetValue("key_count"));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue("k_key"));
			$rowaction = strval($objForm->GetValue("k_action"));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$rbping_server->CurrentFilter = $rbping_server->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$rbping_server->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$rbping_server->EventCancelled = TRUE; // Set event cancelled
			$rbping_server->CurrentAction = "gridedit"; // Stay in Grid Edit mode
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm, $rbping_server;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $rbping_server->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		global $rbping_server;
		$arrKeyFlds = explode(EW_COMPOSITE_KEY_SEPARATOR, $key);
		if (count($arrKeyFlds) >= 1) {
			$rbping_server->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($rbping_server->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError, $rbping_server;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = 0;
		$rowcnt = strval($objForm->GetValue("key_count"));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue("k_action"));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$rbping_server->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= EW_COMPOSITE_KEY_SEPARATOR;
					$sKey .= $rbping_server->id->CurrentValue;

					// Add filter for this record
					$sFilter = $rbping_server->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$rbping_server->CurrentFilter = $sWrkFilter;
			$sSql = $rbping_server->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
			$rbping_server->EventCancelled = TRUE; // Set event cancelled
			$rbping_server->CurrentAction = "gridadd"; // Stay in gridadd mode
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $rbping_server, $objForm;
		if ($objForm->HasValue("x_agent_id") && $objForm->HasValue("o_agent_id") && $rbping_server->agent_id->CurrentValue <> $rbping_server->agent_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_host") && $objForm->HasValue("o_host") && $rbping_server->host->CurrentValue <> $rbping_server->host->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ip_addr") && $objForm->HasValue("o_ip_addr") && $rbping_server->ip_addr->CurrentValue <> $rbping_server->ip_addr->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_protocol") && $objForm->HasValue("o_protocol") && $rbping_server->protocol->CurrentValue <> $rbping_server->protocol->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_port") && $objForm->HasValue("o_port") && $rbping_server->port->CurrentValue <> $rbping_server->port->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tos") && $objForm->HasValue("o_tos") && $rbping_server->tos->CurrentValue <> $rbping_server->tos->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_enable") && $objForm->HasValue("o_enable") && $rbping_server->enable->CurrentValue <> $rbping_server->enable->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_down_enable") && $objForm->HasValue("o_down_enable") && $rbping_server->down_enable->CurrentValue <> $rbping_server->down_enable->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_polling_cycles") && $objForm->HasValue("o_polling_cycles") && $rbping_server->polling_cycles->CurrentValue <> $rbping_server->polling_cycles->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = 0;
		$rowcnt = strval($objForm->GetValue("key_count"));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue("k_action"));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm, $rbping_server;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security, $rbping_server;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $rbping_server->agent_id, FALSE); // agent_id
		$this->BuildSearchSql($sWhere, $rbping_server->host, FALSE); // host
		$this->BuildSearchSql($sWhere, $rbping_server->ip_addr, FALSE); // ip_addr
		$this->BuildSearchSql($sWhere, $rbping_server->protocol, FALSE); // protocol
		$this->BuildSearchSql($sWhere, $rbping_server->port, FALSE); // port
		$this->BuildSearchSql($sWhere, $rbping_server->tos, FALSE); // tos
		$this->BuildSearchSql($sWhere, $rbping_server->enable, FALSE); // enable
		$this->BuildSearchSql($sWhere, $rbping_server->down_enable, FALSE); // down_enable

		// Set up search parm
		if ($sWhere <> "") {
			$this->SetSearchParm($rbping_server->agent_id); // agent_id
			$this->SetSearchParm($rbping_server->host); // host
			$this->SetSearchParm($rbping_server->ip_addr); // ip_addr
			$this->SetSearchParm($rbping_server->protocol); // protocol
			$this->SetSearchParm($rbping_server->port); // port
			$this->SetSearchParm($rbping_server->tos); // tos
			$this->SetSearchParm($rbping_server->enable); // enable
			$this->SetSearchParm($rbping_server->down_enable); // down_enable
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);		
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Set search parameters
	function SetSearchParm(&$Fld) {
		global $rbping_server;
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$rbping_server->setAdvancedSearch("x_$FldParm", $FldVal);
		$rbping_server->setAdvancedSearch("z_$FldParm", $Fld->AdvancedSearch->SearchOperator); // @$_GET["z_$FldParm"]
		$rbping_server->setAdvancedSearch("v_$FldParm", $Fld->AdvancedSearch->SearchCondition); // @$_GET["v_$FldParm"]
		$rbping_server->setAdvancedSearch("y_$FldParm", $FldVal2);
		$rbping_server->setAdvancedSearch("w_$FldParm", $Fld->AdvancedSearch->SearchOperator2); // @$_GET["w_$FldParm"]
	}

	// Get search parameters
	function GetSearchParm(&$Fld) {
		global $rbping_server;
		$FldParm = substr($Fld->FldVar, 2);
		$Fld->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_$FldParm");
		$Fld->AdvancedSearch->SearchOperator = $rbping_server->getAdvancedSearch("z_$FldParm");
		$Fld->AdvancedSearch->SearchCondition = $rbping_server->getAdvancedSearch("v_$FldParm");
		$Fld->AdvancedSearch->SearchValue2 = $rbping_server->getAdvancedSearch("y_$FldParm");
		$Fld->AdvancedSearch->SearchOperator2 = $rbping_server->getAdvancedSearch("w_$FldParm");
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		global $rbping_server;
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $rbping_server->agent_id, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $rbping_server->host, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $rbping_server->ip_addr, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $rbping_server->protocol, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $rbping_server->port, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $rbping_server->tos, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $rbping_server->enable, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $rbping_server->down_enable, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		$sFldExpression = ($Fld->FldVirtualExpression <> "") ? $Fld->FldVirtualExpression : $Fld->FldExpression;
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($lFldDataType == EW_DATATYPE_NUMBER) {
			$sWrk = $sFldExpression . " = " . ew_QuotedValue($Keyword, $lFldDataType);
		} else {
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", $lFldDataType));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security, $rbping_server;
		$sSearchStr = "";
		$sSearchKeyword = $rbping_server->BasicSearchKeyword;
		$sSearchType = $rbping_server->BasicSearchType;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
		}
		if ($sSearchKeyword <> "") {
			$rbping_server->setSessionBasicSearchKeyword($sSearchKeyword);
			$rbping_server->setSessionBasicSearchType($sSearchType);
		}
		return $sSearchStr;
	}

	// Clear all search parameters
	function ResetSearchParms() {
		global $rbping_server;

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$rbping_server->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		global $rbping_server;
		$rbping_server->setSessionBasicSearchKeyword("");
		$rbping_server->setSessionBasicSearchType("");
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		global $rbping_server;
		$rbping_server->setAdvancedSearch("x_agent_id", "");
		$rbping_server->setAdvancedSearch("x_host", "");
		$rbping_server->setAdvancedSearch("x_ip_addr", "");
		$rbping_server->setAdvancedSearch("x_protocol", "");
		$rbping_server->setAdvancedSearch("x_port", "");
		$rbping_server->setAdvancedSearch("x_tos", "");
		$rbping_server->setAdvancedSearch("x_enable", "");
		$rbping_server->setAdvancedSearch("x_down_enable", "");
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		global $rbping_server;
		$bRestore = TRUE;
		if ($rbping_server->BasicSearchKeyword <> "") $bRestore = FALSE;
		if ($rbping_server->agent_id->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		if ($rbping_server->host->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		if ($rbping_server->ip_addr->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		if ($rbping_server->protocol->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		if ($rbping_server->port->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		if ($rbping_server->tos->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		if ($rbping_server->enable->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		if ($rbping_server->down_enable->AdvancedSearch->SearchValue <> "") $bRestore = FALSE;
		$this->RestoreSearch = $bRestore;
		if ($bRestore) {

			// Restore basic search values
			$rbping_server->BasicSearchKeyword = $rbping_server->getSessionBasicSearchKeyword();
			$rbping_server->BasicSearchType = $rbping_server->getSessionBasicSearchType();

			// Restore advanced search values
			$this->GetSearchParm($rbping_server->agent_id);
			$this->GetSearchParm($rbping_server->host);
			$this->GetSearchParm($rbping_server->ip_addr);
			$this->GetSearchParm($rbping_server->protocol);
			$this->GetSearchParm($rbping_server->port);
			$this->GetSearchParm($rbping_server->tos);
			$this->GetSearchParm($rbping_server->enable);
			$this->GetSearchParm($rbping_server->down_enable);
		}
	}

	// Set up sort parameters
	function SetUpSortOrder() {
		global $rbping_server;

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$rbping_server->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$rbping_server->CurrentOrderType = @$_GET["ordertype"];
			$rbping_server->UpdateSort($rbping_server->agent_id); // agent_id
			$rbping_server->UpdateSort($rbping_server->host); // host
			$rbping_server->UpdateSort($rbping_server->ip_addr); // ip_addr
			$rbping_server->UpdateSort($rbping_server->protocol); // protocol
			$rbping_server->UpdateSort($rbping_server->port); // port
			$rbping_server->UpdateSort($rbping_server->tos); // tos
			$rbping_server->UpdateSort($rbping_server->enable); // enable
			$rbping_server->UpdateSort($rbping_server->down_enable); // down_enable
			$rbping_server->UpdateSort($rbping_server->polling_cycles); // polling_cycles
			$rbping_server->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		global $rbping_server;
		$sOrderBy = $rbping_server->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($rbping_server->SqlOrderBy() <> "") {
				$sOrderBy = $rbping_server->SqlOrderBy();
				$rbping_server->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {
		global $rbping_server;

		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];

			// Reset search criteria
			if (strtolower($sCmd) == "reset" || strtolower($sCmd) == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if (strtolower($sCmd) == "resetsort") {
				$sOrderBy = "";
				$rbping_server->setSessionOrderBy($sOrderBy);
				$rbping_server->agent_id->setSort("");
				$rbping_server->host->setSort("");
				$rbping_server->ip_addr->setSort("");
				$rbping_server->protocol->setSort("");
				$rbping_server->port->setSort("");
				$rbping_server->tos->setSort("");
				$rbping_server->enable->setSort("");
				$rbping_server->down_enable->setSort("");
				$rbping_server->polling_cycles->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$rbping_server->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language, $rbping_server;

		// "griddelete"
		if ($rbping_server->AllowAddDeleteRow) {
			$item =& $this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// "view"
		$item =& $this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item =& $this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item =& $this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "checkbox"
		$item =& $this->ListOptions->Add("checkbox");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = True;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" class=\"phpmaker\" onclick=\"rbping_server_list.SelectAllKey(this);\">";

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $rbping_server, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex)) {
			$objForm->Index = $this->RowIndex;
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_action\" id=\"k" . $this->RowIndex . "_action\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue("k_key");
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $rbping_server->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_blankrow\" id=\"k" . $this->RowIndex . "_blankrow\" value=\"1\">";
		}

		// "delete"
		if ($rbping_server->AllowAddDeleteRow) {
			if ($rbping_server->CurrentAction == "gridadd" || $rbping_server->CurrentAction == "gridedit") {
				$oListOpt =& $this->ListOptions->Items["griddelete"];
				$oListOpt->Body = "<a class=\"ewGridLink\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, rbping_server_list, " . $this->RowIndex . ");\">" . "<img src=\"phpimages/delete.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("DeleteLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("DeleteLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
			}
		}

		// "copy"
		$oListOpt =& $this->ListOptions->Items["copy"];
		if (($rbping_server->CurrentAction == "add" || $rbping_server->CurrentAction == "copy") &&
			$rbping_server->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink\" href=\"\" onclick=\"return ew_SubmitForm(rbping_server_list, document.frbping_serverlist);\">" . "<img src=\"phpimages/insert.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("InsertLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("InsertLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>&nbsp;" .
				"<a class=\"ewGridLink\" href=\"" . $this->PageUrl() . "a=cancel\">" . "<img src=\"phpimages/cancel.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("CancelLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("CancelLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt =& $this->ListOptions->Items["edit"];
		if ($rbping_server->CurrentAction == "edit" && $rbping_server->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink\" href=\"\" onclick=\"return ew_SubmitForm(rbping_server_list, document.frbping_serverlist, '" . $this->PageName() . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "');\">" . "<img src=\"phpimages/update.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("UpdateLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("UpdateLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>&nbsp;" .
					"<a class=\"ewGridLink\" href=\"" . $this->PageUrl() . "a=cancel\">" . "<img src=\"phpimages/cancel.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("CancelLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("CancelLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			return;
		}

		// "view"
		$oListOpt =& $this->ListOptions->Items["view"];
		if ($oListOpt->Visible)
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . "<img src=\"phpimages/view.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ViewLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ViewLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";

		// "edit"
		$oListOpt =& $this->ListOptions->Items["edit"];
		if ($oListOpt->Visible) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . "<img src=\"phpimages/edit.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("EditLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("EditLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink\" href=\"" . $this->InlineEditUrl . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "\">" . "<img src=\"phpimages/inlineedit.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("InlineEditLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("InlineEditLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		}

		// "copy"
		$oListOpt =& $this->ListOptions->Items["copy"];
		if ($oListOpt->Visible) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->CopyUrl . "\">" . "<img src=\"phpimages/copy.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("CopyLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("CopyLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink\" href=\"" . $this->InlineCopyUrl . "\">" . "<img src=\"phpimages/inlinecopy.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("InlineCopyLink")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("InlineCopyLink")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		}

		// "checkbox"
		$oListOpt =& $this->ListOptions->Items["checkbox"];
		if ($oListOpt->Visible)
			$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" id=\"key_m[]\" value=\"" . ew_HtmlEncode($rbping_server->id->CurrentValue) . "\" class=\"phpmaker\" onclick='ew_ClickMultiCheckbox(this);'>";
		if ($rbping_server->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . $rbping_server->id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language, $rbping_server;
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

	// Load default values
	function LoadDefaultValues() {
		global $rbping_server;
		$rbping_server->agent_id->CurrentValue = NULL;
		$rbping_server->agent_id->OldValue = $rbping_server->agent_id->CurrentValue;
		$rbping_server->host->CurrentValue = NULL;
		$rbping_server->host->OldValue = $rbping_server->host->CurrentValue;
		$rbping_server->ip_addr->CurrentValue = NULL;
		$rbping_server->ip_addr->OldValue = $rbping_server->ip_addr->CurrentValue;
		$rbping_server->protocol->CurrentValue = "icmp";
		$rbping_server->protocol->OldValue = $rbping_server->protocol->CurrentValue;
		$rbping_server->port->CurrentValue = 0;
		$rbping_server->port->OldValue = $rbping_server->port->CurrentValue;
		$rbping_server->tos->CurrentValue = 0;
		$rbping_server->tos->OldValue = $rbping_server->tos->CurrentValue;
		$rbping_server->enable->CurrentValue = 1;
		$rbping_server->enable->OldValue = $rbping_server->enable->CurrentValue;
		$rbping_server->down_enable->CurrentValue = 1;
		$rbping_server->down_enable->OldValue = $rbping_server->down_enable->CurrentValue;
		$rbping_server->polling_cycles->CurrentValue = 5;
		$rbping_server->polling_cycles->OldValue = $rbping_server->polling_cycles->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		global $rbping_server;
		$rbping_server->BasicSearchKeyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		$rbping_server->BasicSearchType = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm, $rbping_server;

		// Load search values
		// agent_id

		$rbping_server->agent_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_agent_id"]);
		$rbping_server->agent_id->AdvancedSearch->SearchOperator = @$_GET["z_agent_id"];

		// host
		$rbping_server->host->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_host"]);
		$rbping_server->host->AdvancedSearch->SearchOperator = @$_GET["z_host"];

		// ip_addr
		$rbping_server->ip_addr->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ip_addr"]);
		$rbping_server->ip_addr->AdvancedSearch->SearchOperator = @$_GET["z_ip_addr"];

		// protocol
		$rbping_server->protocol->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_protocol"]);
		$rbping_server->protocol->AdvancedSearch->SearchOperator = @$_GET["z_protocol"];

		// port
		$rbping_server->port->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_port"]);
		$rbping_server->port->AdvancedSearch->SearchOperator = @$_GET["z_port"];

		// tos
		$rbping_server->tos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tos"]);
		$rbping_server->tos->AdvancedSearch->SearchOperator = @$_GET["z_tos"];

		// enable
		$rbping_server->enable->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_enable"]);
		$rbping_server->enable->AdvancedSearch->SearchOperator = @$_GET["z_enable"];

		// down_enable
		$rbping_server->down_enable->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_down_enable"]);
		$rbping_server->down_enable->AdvancedSearch->SearchOperator = @$_GET["z_down_enable"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm, $rbping_server;
		if (!$rbping_server->agent_id->FldIsDetailKey) {
			$rbping_server->agent_id->setFormValue($objForm->GetValue("x_agent_id"));
		}
		$rbping_server->agent_id->setOldValue($objForm->GetValue("o_agent_id"));
		if (!$rbping_server->host->FldIsDetailKey) {
			$rbping_server->host->setFormValue($objForm->GetValue("x_host"));
		}
		$rbping_server->host->setOldValue($objForm->GetValue("o_host"));
		if (!$rbping_server->ip_addr->FldIsDetailKey) {
			$rbping_server->ip_addr->setFormValue($objForm->GetValue("x_ip_addr"));
		}
		$rbping_server->ip_addr->setOldValue($objForm->GetValue("o_ip_addr"));
		if (!$rbping_server->protocol->FldIsDetailKey) {
			$rbping_server->protocol->setFormValue($objForm->GetValue("x_protocol"));
		}
		$rbping_server->protocol->setOldValue($objForm->GetValue("o_protocol"));
		if (!$rbping_server->port->FldIsDetailKey) {
			$rbping_server->port->setFormValue($objForm->GetValue("x_port"));
		}
		$rbping_server->port->setOldValue($objForm->GetValue("o_port"));
		if (!$rbping_server->tos->FldIsDetailKey) {
			$rbping_server->tos->setFormValue($objForm->GetValue("x_tos"));
		}
		$rbping_server->tos->setOldValue($objForm->GetValue("o_tos"));
		if (!$rbping_server->enable->FldIsDetailKey) {
			$rbping_server->enable->setFormValue($objForm->GetValue("x_enable"));
		}
		$rbping_server->enable->setOldValue($objForm->GetValue("o_enable"));
		if (!$rbping_server->down_enable->FldIsDetailKey) {
			$rbping_server->down_enable->setFormValue($objForm->GetValue("x_down_enable"));
		}
		$rbping_server->down_enable->setOldValue($objForm->GetValue("o_down_enable"));
		if (!$rbping_server->polling_cycles->FldIsDetailKey) {
			$rbping_server->polling_cycles->setFormValue($objForm->GetValue("x_polling_cycles"));
		}
		$rbping_server->polling_cycles->setOldValue($objForm->GetValue("o_polling_cycles"));
		if (!$rbping_server->id->FldIsDetailKey && $rbping_server->CurrentAction <> "gridadd" && $rbping_server->CurrentAction <> "add")
			$rbping_server->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm, $rbping_server;
		if ($rbping_server->CurrentAction <> "gridadd" && $rbping_server->CurrentAction <> "add")
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

	// Load old record
	function LoadOldRecord() {
		global $rbping_server;

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($rbping_server->getKey("id")) <> "")
			$rbping_server->id->CurrentValue = $rbping_server->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$rbping_server->CurrentFilter = $rbping_server->KeyFilter();
			$sSql = $rbping_server->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language, $rbping_server;

		// Initialize URLs
		$this->ViewUrl = $rbping_server->ViewUrl();
		$this->EditUrl = $rbping_server->EditUrl();
		$this->InlineEditUrl = $rbping_server->InlineEditUrl();
		$this->CopyUrl = $rbping_server->CopyUrl();
		$this->InlineCopyUrl = $rbping_server->InlineCopyUrl();
		$this->DeleteUrl = $rbping_server->DeleteUrl();

		// Call Row_Rendering event
		$rbping_server->Row_Rendering();

		// Common render codes for all row types
		// id

		$rbping_server->id->CellCssStyle = "white-space: nowrap;";

		// agent_id
		// host
		// ip_addr
		// protocol
		// port
		// tos
		// enable
		// down_enable
		// polling_cycles

		$rbping_server->polling_cycles->CellCssStyle = "white-space: nowrap;";
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
		} elseif ($rbping_server->RowType == EW_ROWTYPE_ADD) { // Add row

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
		} elseif ($rbping_server->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// agent_id
			$rbping_server->agent_id->EditCustomAttributes = "";
			$rbping_server->agent_id->EditValue = ew_HtmlEncode($rbping_server->agent_id->AdvancedSearch->SearchValue);

			// host
			$rbping_server->host->EditCustomAttributes = "";
			$rbping_server->host->EditValue = ew_HtmlEncode($rbping_server->host->AdvancedSearch->SearchValue);

			// ip_addr
			$rbping_server->ip_addr->EditCustomAttributes = "";
			$rbping_server->ip_addr->EditValue = ew_HtmlEncode($rbping_server->ip_addr->AdvancedSearch->SearchValue);

			// protocol
			$rbping_server->protocol->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array("icmp", $rbping_server->protocol->FldTagCaption(1) <> "" ? $rbping_server->protocol->FldTagCaption(1) : "icmp");
			$arwrk[] = array("tcp", $rbping_server->protocol->FldTagCaption(2) <> "" ? $rbping_server->protocol->FldTagCaption(2) : "tcp");
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$rbping_server->protocol->EditValue = $arwrk;

			// port
			$rbping_server->port->EditCustomAttributes = "";
			$rbping_server->port->EditValue = ew_HtmlEncode($rbping_server->port->AdvancedSearch->SearchValue);

			// tos
			$rbping_server->tos->EditCustomAttributes = "";
			$rbping_server->tos->EditValue = ew_HtmlEncode($rbping_server->tos->AdvancedSearch->SearchValue);

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
			$rbping_server->polling_cycles->EditValue = ew_HtmlEncode($rbping_server->polling_cycles->AdvancedSearch->SearchValue);
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError, $rbping_server;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($rbping_server->port->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $rbping_server->port->FldErrMsg());
		}
		if (!ew_CheckInteger($rbping_server->tos->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $rbping_server->tos->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security, $rbping_server;
		$DeleteRows = TRUE;
		$sSql = $rbping_server->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $rbping_server->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= EW_COMPOSITE_KEY_SEPARATOR;
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $conn->Execute($rbping_server->DeleteSQL($row)); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($rbping_server->CancelMessage <> "") {
				$this->setFailureMessage($rbping_server->CancelMessage);
				$rbping_server->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$rbping_server->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security, $rbping_server;
		$rsnew = array();

		// agent_id
		$rbping_server->agent_id->SetDbValueDef($rsnew, $rbping_server->agent_id->CurrentValue, "", FALSE);

		// host
		$rbping_server->host->SetDbValueDef($rsnew, $rbping_server->host->CurrentValue, "", FALSE);

		// ip_addr
		$rbping_server->ip_addr->SetDbValueDef($rsnew, $rbping_server->ip_addr->CurrentValue, "", FALSE);

		// protocol
		$rbping_server->protocol->SetDbValueDef($rsnew, $rbping_server->protocol->CurrentValue, "", strval($rbping_server->protocol->CurrentValue) == "");

		// port
		$rbping_server->port->SetDbValueDef($rsnew, $rbping_server->port->CurrentValue, 0, strval($rbping_server->port->CurrentValue) == "");

		// tos
		$rbping_server->tos->SetDbValueDef($rsnew, $rbping_server->tos->CurrentValue, 0, strval($rbping_server->tos->CurrentValue) == "");

		// enable
		$rbping_server->enable->SetDbValueDef($rsnew, $rbping_server->enable->CurrentValue, 0, strval($rbping_server->enable->CurrentValue) == "");

		// down_enable
		$rbping_server->down_enable->SetDbValueDef($rsnew, $rbping_server->down_enable->CurrentValue, 0, strval($rbping_server->down_enable->CurrentValue) == "");

		// polling_cycles
		$rbping_server->polling_cycles->SetDbValueDef($rsnew, $rbping_server->polling_cycles->CurrentValue, 0, strval($rbping_server->polling_cycles->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $rbping_server->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $conn->Execute($rbping_server->InsertSQL($rsnew));
			$conn->raiseErrorFn = '';
		} else {
			if ($rbping_server->CancelMessage <> "") {
				$this->setFailureMessage($rbping_server->CancelMessage);
				$rbping_server->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$rbping_server->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $rbping_server->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$rbping_server->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		global $rbping_server;
		$rbping_server->agent_id->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_agent_id");
		$rbping_server->host->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_host");
		$rbping_server->ip_addr->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_ip_addr");
		$rbping_server->protocol->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_protocol");
		$rbping_server->port->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_port");
		$rbping_server->tos->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_tos");
		$rbping_server->enable->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_enable");
		$rbping_server->down_enable->AdvancedSearch->SearchValue = $rbping_server->getAdvancedSearch("x_down_enable");
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
		$item->Visible = FALSE;

		// Export to Email
		$item =& $this->ExportOptions->Add("email");
		$item->Body = "<a name=\"emf_rbping_server\" id=\"emf_rbping_server\" href=\"javascript:void(0);\" onclick=\"ew_EmailDialogShow({lnk:'emf_rbping_server',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.frbping_serverlist,sel:false});\">" . "<img src=\"phpimages/exportemail.gif\" alt=\"" . ew_HtmlEncode($Language->Phrase("ExportToEmail")) . "\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToEmail")) . "\" width=\"16\" height=\"16\" border=\"0\">" . "</a>";
		$item->Visible = FALSE;

		// Hide options for export/action
		if ($rbping_server->Export <> "" ||
			$rbping_server->CurrentAction <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		global $rbping_server;
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $rbping_server->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($rbping_server->ExportAll) {
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs < 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		if ($rbping_server->Export == "xml") {
			$XmlDoc = new cXMLDocument(EW_XML_ENCODING);
		} else {
			$ExportDoc = new cExportDocument($rbping_server, "h");
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
			$rbping_server->ExportXmlDocument($XmlDoc, ($ParentTable <> ""), $rs, $StartRec, $StopRec, "");
		} else {
			$sHeader = $this->PageHeader;
			$this->Page_DataRendering($sHeader);
			$ExportDoc->Text .= $sHeader;
			$rbping_server->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt =& $this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
