$(function() {
	$(".sidebar .sidebar-tree span").on("click", function callAjax() {
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
});