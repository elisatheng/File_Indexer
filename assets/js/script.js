$(function() {

	/**
	* TABLESORTER
	* @plugin
	*/
	$(".body .body-section .tablesorter").tablesorter();


	/**
	* SCROLLBAR
	*/
	$(".sidebar .sidebar-tree").setScrollbar();
	$(".body .body-section").setScrollbar();


	/**
	* TOGGLE BARS
	*/
	$.toggleBars();


	/**
	* SIDEBAR TREE
	*/
	$(".sidebar .sidebar-tree > li").children().children().setFolderOpenIcon();
	$(".sidebar .sidebar-tree span").listSubfoldersOnClick();


	/**
	* TOOLBAR THEME
	*/
	$.handleTheme();

});