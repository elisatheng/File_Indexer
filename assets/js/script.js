$(function() {

	/**
	* SIDEBAR TREE
	*/
	$(".sidebar .sidebar-tree > li").children().children().setFolderOpenIcon();
	$(".sidebar .sidebar-tree span").listSubfoldersOnClick();


	/**
	* TABLESORTER
	* @plugin
	*/
	$(".body .body-section .tablesorter").tablesorter();

});