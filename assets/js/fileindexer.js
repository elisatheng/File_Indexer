(function($) {

	/**
	* List subfolders of current folder clicked
	* @if target has no subfolders yet
	* 	Do request to Ajax to cal listSubfolders method from php class file
	* 	Append the return putted in ajax block (cf end of index.php) in target child block
	*	Set theme for the new folders
	* @else
	*	remove target subfolders
	* Set scrollbar depending new/old subfolders
	*
	* @selector folder(s)
	*/
	$.fn.listSubfoldersOnClick = function() {
		var $target = $(this);

		$target.on("click", function callAjax() {
			var sidebarTree_child = $(this)[0].nextElementSibling;
			var ajaxurl = "/_Github/File_Indexer/";

			if ($(sidebarTree_child)[0].children.length == 0) {
				$.ajax({
					type : "POST",
					url : ajaxurl,
					data : { "action" : 'list', "path" : $(this).attr("data-path") },
					success : function(html) {
						var html = $.parseHTML(html);

						$.each(html, function(key, object) {
							// get ajax block having the new subfolders
							if ($(object).attr("id") == "ajax") {
								$(sidebarTree_child).append($(object)[0].innerHTML);

								$(sidebarTree_child).parent().setFolderOpenIcon();
								
								$(".sidebar .sidebar-tree span").off();
								$(".sidebar .sidebar-tree span").on("click", callAjax);
							}
						});
					}
				});
			}
			else {
				$(sidebarTree_child).parent().children("i").attr("class", "fa fa-folder");
				$($(sidebarTree_child)[0].children).remove();
			}
		});
	};

	/**
	*
	* @selector element to change folder icon to folder open icon
	*/
	$.fn.setFolderOpenIcon = function() {
		$.each($(this), function(index, element) {
			if ($(element).children("ul").children().length > 0) {
				$(element).children("i").attr("class", "fa fa-folder-open");
				$(element).children("ul").children().setFolderOpenIcon();
			}
		});
	};

	/**
	* Set target height
	* Set scrollbar if target child height is bigger than the target height
	*
	* @selector element to set scrollbar
	*/
	$.fn.setScrollbar = function() {
		// set height
		var targetHeader_height = $($(this)[0].previousElementSibling)[0].offsetHeight,
			marginBottom = ($(this).is(".body-section")) ? 70 : 20;

		$(this).css("height", "calc(100vh - " + (targetHeader_height + marginBottom) + "px)");

		// set scroll
		var target_height = $(this)[0].offsetHeight,
			target_first_height = $($(this).children()[0])[0].offsetHeight,
			target_first_height = ($(this).is(".body-section")) ? target_first_height + 25 : target_first_height;

		if (target_first_height > target_height)
			$(this).css("overflow-y", "scroll");
		else
			$(this).css("overflow-y", "hidden");
	};

	/**
	* Get sidebar width
	* Position toolbar next to sidebar
	* Handle toggle
	*/
	$.toggleBars = function() {
		var sidebar_width = $(".sidebar")[0].clientWidth;

		// put toolbar next to sidebar to be hidden
		$(".toolbar").css("margin-left", sidebar_width + "px");

		// handle toggle
		$(".sidebar .sidebar-header .fa-bars").on("click", function() {
			$(".toolbar").show().animate({ marginLeft: "0px" });
		});
		$(".toolbar .toolbar-header .fa-times").on("click", function() {
			$(".toolbar").animate({ marginLeft: sidebar_width + "px" });
		});
	};

}(jQuery));