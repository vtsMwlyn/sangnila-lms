let loadingTimeout;

// Function to show the loading popup with a delay
function showLoadingPopupWithDelay() {
	loadingTimeout = setTimeout(function() {
		$('#loading-popup').parent().removeClass('hidden');
	}, 500); // Show loader only if loading takes longer than 500ms
}

// Function to hide the loading popup
function hideLoadingPopup() {
	clearTimeout(loadingTimeout);
	$('#loading-popup').parent().addClass('hidden');
}

$(document).ready(() => {
    $(document).on('submit', '.ajax-form', function (e) {
        e.preventDefault();
    });

    $(document).on('submit', 'form:not(.ajax-form)', function () {
        showLoadingPopupWithDelay();
        $(this).find(':submit').prop('disabled', true);
    });

	// Show loading popup on anchor link clicks that reload the page
	$('a[href]').on('click', function (e) {
		const href = $(this).attr('href');

		// If the href is "#" or opens in a different tab, skip showing the loading popup
		if (href === "#" || $(this).attr('target') && $(this).attr('target') !== '_self') {
			return;
		}

		showLoadingPopupWithDelay();
	});

	// Track back button click using popstate event
	$(window).on('popstate', function () {
		isPopState = true;  // Set flag to true on back navigation
		hideLoadingPopup();  // Ensure the popup is hidden
	});

	// Hide loading popup once the page is fully loaded
	$(window).on('pageshow', function () {
		isPopState = false;  // Reset flag on full page load
		hideLoadingPopup();
	});

	// Display back to top button on page scroll more than 100vh
	const backToTopButton = document.getElementById("back-to-top");

	window.addEventListener("scroll", function() {
		if (window.scrollY > window.innerHeight * 0.3) {
			backToTopButton.classList.remove("opacity-0");
			backToTopButton.classList.add("opacity-100");
		} else {
			backToTopButton.classList.remove("opacity-100");backToTopButton.classList.add("opacity-0");
		}
	});

	function adjustLayouts(){
		// Minimum height for sidebar
		$("#sidebar").css("height", ($(this).height() - $("#navbar").outerHeight()));

		// Set content and sidebar width
		if($(this).width() < 1024){
			$("#sidebar-container").css("display", "none");
			$("#content-container").css("width", "100%");
		}
		else {
			$("#sidebar-container").css("display", "block");
			$("#content-container").css("width", "83%");
		}

		$("#screen").text(`(Resolution: ${window.innerWidth}x${window.innerHeight})`);
	}

	adjustLayouts();

	$(window).on("resize", function(){
		adjustLayouts();
	});


	// Select2 initialization
	$('.select-2').select2({
		allowClear: false
	});

	// Apply resize observer to each container with class 'container-select2'
	$('.container-select2').each(function () {
		const container = this;
		const resizeObserver = new ResizeObserver(() => {
			$(container).find('.select-2').each(function () {
				$(this).select2({
					allowClear: false
				});
			});

			// stylingSelect2();
		});

		resizeObserver.observe(container);
	});


	// Datepicker mechanique
	const testinput = document.createElement('input');
	testinput.setAttribute('type', 'date');

	// If native date input is not supported, use jQuery UI Datepicker
	if (testinput.type !== 'date') {
		$('.date-input').datepicker({
			dateFormat: "yy-mm-dd", // Set the desired date format
			changeMonth: true, // Enable month dropdown
			changeYear: true,  // Enable year dropdown
			yearRange: "1900:+10", // Set the range of years
		});
	}
	else {
		$('.date-input').on({
			"focus": function(){
				this.showPicker();
			},
			"click": function(){
				this.showPicker();
			}
		});
	}

	// Announcement popups
	let popups = $(".announcement-popup-container").length;

	function remove_dismiss_announcement_popup(){
		popups--;
		if(popups == 0){
			$("#dismiss-announcements-btn").fadeOut();
		}
	}

	$(".announcement-popup-container").click(function(e){
		if (!$(e.target).closest(".announcement-popup").length) {
			remove_dismiss_announcement_popup();
			$(this).fadeOut();
		}

	});

	$("#dismiss-announcements-btn").click(function(){
		$(".announcement-popup-container").fadeOut();
		$(this).fadeOut();
	});

	$(".announcementContent a").each((index, anchor) => {
		$(anchor).attr("target", "blank");
	});

	// Other popups
	$(".popup-dismiss").click(function(){
		$(this).closest(".popup-container").fadeOut(function(){
			// Clear error messages and reset input classes
			$('input, select, textarea').removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0').val();
			$('.error-messages').remove(); // This removes any error messages displayed
		});

		// Clear inputs when any popup is closed
		$('input[name]:not([name="_token"]), select, textarea').val("");
	});

	// Dropdowns
	$(".dropdown-toggler").click(function (e) {
		e.stopPropagation();

		$(this).closest(".dropdown-container").find(".dropdown-menu").toggle();
	});

	$(document).click(function (e) {
		if (!$(e.target).closest(".dropdown-menu, .dropdown-toggler").length) {
			$(".dropdown-menu").hide();
		}
	});

	const testus = $("#large-sidebar").clone();
	$("#medsmallmenu-dropdown").empty().append(testus);
});

// Hide the loading popup once the page is fully loaded
$(window).on('load', hideLoadingPopup);
