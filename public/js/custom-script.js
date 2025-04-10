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

// Toggle sidebar toggler visibility
function resetSidebarToggler(){
	if($(window).width() <= 2000 && $(window).width() >= 1280){
		$('#sidebar-toggler-larger').show();
		$('#sidebar-toggler-larger').css('left', $('#sidebar-container').outerWidth()).css('top', $(window).innerHeight() / 2);
	}
	else {
		$('#sidebar-toggler-larger').hide();
	}
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
		$('#sidebar').css('top', $("#navbar").outerHeight())

		$('#content-wrapper').css('min-height', window.innerHeight - $("#navbar").outerHeight());
	}

	adjustLayouts();
	resetSidebarToggler();

	let isAnimating = false;

	$(window).on("resize", function(){
		if(!isAnimating){
			adjustLayouts();
			resetSidebarToggler();
		}
	});

	$('#sidebar-toggler-larger').click(function () {
		isAnimating = true;

		console.log(isAnimating);
	
		if ($('#sidebar-container').is(':visible')) {
			$('#sidebar-container').animate({ width: '0', opacity: '0' }, 300, function () {
				$(this).hide();
				isAnimating = false;
				adjustLayouts(); // Now safe to adjust

				console.log(isAnimating);
			});
			$('#content-container').animate({ width: '100%' }, 300);
			$('#sidebar-toggler-larger')
				.animate({ left: 0 }, 300)
				.html('<i class="bi bi-caret-right-fill"></i>');
		} else {
			$('#sidebar-container')
				.css('opacity', '0')
				.show()
				.animate({ width: '17%', opacity: '1' }, 300, function () {
					isAnimating = false;
					adjustLayouts();

					console.log(isAnimating);
				});
			$('#content-container').animate({ width: '83%' }, 300);
	
			setTimeout(function () {
				$('#sidebar-toggler-larger')
					.animate({ left: $('#sidebar-container').outerWidth() }, 100)
					.html('<i class="bi bi-caret-left-fill"></i>');
			}, 300);
		}
	});

	$('#sidebar-toggler-smaller').on('click', function(){
		$('#sidebar-container').toggle();
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
		});

		resizeObserver.observe(container);
	});

	// Cancel confirmation
	$('.cancel-btn').on('click', function(){
		$('#cancel-popup').parent().show();
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
        e.stopPropagation(); // Prevent click from bubbling up

        let $dropdownMenu = $(this).closest(".dropdown-container").find(".dropdown-menu");

        // Close all other dropdowns
        $(".dropdown-menu").not($dropdownMenu).hide();

        // Toggle the current one
        $dropdownMenu.toggle();
    });

	$(document).click(function (e) {
		if (!$(e.target).closest(".dropdown-menu, .dropdown-toggler").length) {
			$(".dropdown-menu").hide();
		}
	});

	// Action status bottom right notif
	$('.status-notif').animate({
		right: 0
	});

	setTimeout(() => {
		$('.status-notif').find('.progress-bar').animate({
			width: '100%'
		}, {
			duration: 5000,
			easing: 'linear'
		});
	}, 200);

	setTimeout(() => {
		$('.status-notif').fadeOut(300);
	}, 5200);

	$('.dismiss-status-notif').on('click', function(){
		$(this).closest('.status-notif').fadeOut(300);
	})
});

// Hide the loading popup once the page is fully loaded
$(window).on('load', hideLoadingPopup);
