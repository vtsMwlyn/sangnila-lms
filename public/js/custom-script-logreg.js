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
		$('#sidebar-toggler').show();
		$('#sidebar-toggler').css('left', $('#sidebar-container').outerWidth()).css('top', $(window).innerHeight() / 2);
	}
	else {
		$('#sidebar-toggler').hide();
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

    $(window).on('load', hideLoadingPopup);

	// Cancel confirmation
	$('.cancel-btn').on('click', function(){
		$('#cancel-popup').parent().show();
	});
});