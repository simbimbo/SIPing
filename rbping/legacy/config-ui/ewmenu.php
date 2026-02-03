<?php

// Menu
define("EW_MENUBAR_CLASSNAME", "yuimenu", TRUE);
define("EW_MENUBAR_ITEM_CLASSNAME", "yuimenuitem", TRUE);
define("EW_MENUBAR_ITEM_LABEL_CLASSNAME", "yuimenuitemlabel", TRUE);
define("EW_MENU_CLASSNAME", "yuimenu", TRUE);
define("EW_MENU_ITEM_CLASSNAME", "yuimenuitem", TRUE); // Vertical
define("EW_MENU_ITEM_LABEL_CLASSNAME", "yuimenuitemlabel", TRUE); // Vertical
?>
<?php

// Menu Rendering event
function Menu_Rendering(&$Menu) {

	// Change menu items here
}

// MenuItem Adding event
function MenuItem_Adding(&$Item) {

	//var_dump($Item);
	// Return FALSE if menu item not allowed

	return TRUE;
}
?>
<!-- Begin Main Menu -->
<div class="phpmaker">
<?php

// Generate all menu items
$RootMenu = new cMenu("RootMenu");
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "rbping_serverlist.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
<script type="text/javascript">
<!--

// init the menu
var RootMenu = new YAHOO.widget.Menu("RootMenu", { position: "static", hidedelay: 750, lazyload: true });
RootMenu.render();        

//-->
</script>
