(function($) {

	$.handleTheme = function() {
		const colorsDivTag = ".fi-toolbar__section-theme .colors div"
		const lsThemeColorLightKey = "fi_theme_color_light"
		const lsThemeColorDarkKey = "fi_theme_color_dark"

		// fill color divs
		document.querySelectorAll(colorsDivTag).forEach(($color) => {
			$color.style.setProperty('background-color', $color.dataset.colorLight)
		})

		// store color selected in localstorage
		if (localStorage.getItem(lsThemeColorLightKey) === null || localStorage.getItem(lsThemeColorDarkKey) === null) {
			const $firstColorDiv = document.querySelector(colorsDivTag + ':first-child')
			localStorage.setItem(lsThemeColorLightKey, $firstColorDiv.dataset.colorLight)
			localStorage.setItem(lsThemeColorDarkKey, $firstColorDiv.dataset.colorDark)
			$.setTheme($firstColorDiv.dataset.colorLight, $firstColorDiv.dataset.colorDark)
		}
		else {
			$.setTheme(localStorage.getItem(lsThemeColorLightKey), localStorage.getItem(lsThemeColorDarkKey))
		}

		// set target color border
		document.querySelector(colorsDivTag + "[data-color-light='" + localStorage.getItem(lsThemeColorLightKey) + "']")
			.style
			.setProperty('border', '1px solid #f2f2f2')

		// handle color click
		document.querySelectorAll(colorsDivTag).forEach(($colorDiv) => {
			$colorDiv.addEventListener('click', () => {
				const selectedColorLight = $colorDiv.dataset.colorLight
				const selectedColorDark = $colorDiv.dataset.colorDark

				localStorage.setItem(lsThemeColorLightKey, selectedColorLight)
				localStorage.setItem(lsThemeColorDarkKey, selectedColorDark)

				$.setTheme(selectedColorLight, selectedColorDark)

				document.querySelectorAll(colorsDivTag).forEach(($color) => {
					$color.style.setProperty('border', "1px solid #000000")
				})

				$colorDiv.style.setProperty('border', "1px solid #f2f2f2")

				return true
			})
		})
	}

	$.handleScrollbars = function() {
		// Sidebar tree
		const sidebarHeaderHeight = document.querySelector('.fi-sidebar__header').offsetHeight

		document.querySelector('.fi-sidebar__tree')
			.style
			.setProperty('height', 'calc(100% - '+(sidebarHeaderHeight + 30)+'px)')

		// Body section
		const bodyHeight = document.querySelector('.fi-body').offsetHeight
		const breadcrumbHeight = document.querySelector('.fi-body__header').offsetHeight

		// if table
		if (document.querySelector('.fi-body__section .table') !== null) {
			const tableHeadHeight = document.querySelector('.fi-body__section .table thead').offsetHeight
			const height = bodyHeight - (breadcrumbHeight + tableHeadHeight)
			document.querySelector('.fi-body__section').style.setProperty('height', (height - 30) + 'px')
		}
		// if file content
		else {
			const height = bodyHeight - breadcrumbHeight
			document.querySelector('.fi-body__section').style.setProperty('height', (height - 80) + 'px')
		}

	}

	$.setTheme = function(colorLight, colorDark) {
		document.querySelector('.fi-body__header').style.setProperty('background-color', colorLight)

		document.querySelectorAll('.fi-body__section .fa, .fi-sidebar__tree li .fa')
			.forEach(($icon) => {
				$icon.style.setProperty('color', colorDark)
			})
	}

	$.toggleBars = function() {
		const sidebarClientWidth = $(".fi-sidebar")[0].clientWidth

		// put toolbar next to sidebar to be hidden
		document.querySelector('.fi-toolbar').style.setProperty('margin-left', sidebarClientWidth + 'px')

		// handle toggle
		document.querySelector('.fi-sidebar__header .fa-bars').addEventListener('click', () => {
			$(".fi-toolbar").show().animate({ marginLeft: "0px" })
		})
		document.querySelector('.fi-toolbar__header .fa-times').addEventListener('click', () => {
			$(".fi-toolbar").animate({ marginLeft: sidebarClientWidth + "px" })
		})
	}

	$.fn.handleFolderIcons = function() {
		$.each($(this), function(index, element) {
			if ($(element).children("ul").length > 0) {
				$(element).children("i").attr("class", "fa fa-folder-open")
				$(element).children("ul").children().handleFolderIcons()
			}
		})
	}

}(jQuery))
