(function($) {

	/**
	* Set colors in toolbar section
	* Store color selected in localstorage
	* Set border to color default/selected
	* Handle color click
	*
	* @return true at click on a theme's color
	*/
	$.handleTheme = function() {
		var toolbarSectionThemeColors_div = ".toolbar .toolbar-section .theme .colors div";
		var lstorage_colorlight = "fileindexer_theme_color-light";
		var lstorage_colordark = "fileindexer_theme_color-dark";

		// set colors in toolbar theme
		$.each($(toolbarSectionThemeColors_div), function(c, color) {
			$(color).css("background-color", $(color).attr("data-color-light"));
		});

		// store color selected in localstorage
		if (localStorage.getItem(lstorage_colorlight) === null || localStorage.getItem("fileindexer_theme_color-dark") === null) {
			localStorage.setItem(lstorage_colorlight, "#00cccc");
			localStorage.setItem(lstorage_colordark, "#009999");

			$.setTheme("#00cccc", "#009999");
		}
		else {
			$.setTheme(localStorage.getItem(lstorage_colorlight), localStorage.getItem(lstorage_colordark));
		}

		// set border to color default/selected
		$(toolbarSectionThemeColors_div + "[data-color-light='" + localStorage.getItem(lstorage_colorlight) + "']").css("border", "1px solid #f2f2f2");

		// handle color click
		$(toolbarSectionThemeColors_div).on("click", function() {
			var colorSelected_light = $(this).attr("data-color-light");
			var colorSelected_dark = $(this).attr("data-color-dark");

			localStorage.setItem(lstorage_colorlight, colorSelected_light);
			localStorage.setItem(lstorage_colordark, colorSelected_dark);

			$.setTheme(colorSelected_light, colorSelected_dark);

			$(toolbarSectionThemeColors_div).css("border", "1px solid #000");
			$(toolbarSectionThemeColors_div + "[data-color-light='" + colorSelected_light + "']").css("border", "1px solid #f2f2f2");

			return true;
		});
	};

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
		var lstorage_colorlight = "fileindexer_theme_color-light";
		var lstorage_colordark = "fileindexer_theme_color-dark";

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
								$.setTheme(localStorage.getItem(lstorage_colorlight), localStorage.getItem(lstorage_colordark));
								
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

			// reset scrollbar
			$(".sidebar .sidebar-tree").setScrollbar();
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
	* Set color of the theme selected on some parts of html
	*
	* @param {string} colorLight
	* @param {string} colorDark
	*/
	$.setTheme = function(colorLight, colorDark) {
		$(".body .body-header").css("background-color", colorLight);
		$(".body .body-section .fa, .sidebar .sidebar-tree li .fa").css("color", colorDark);
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