<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

        <title>Sangnila LMS</title>

        {{--Font--}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">

        {{--Styles--}}
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <link rel="stylesheet" href={{ asset("css/admin.css") }}>
        <link rel="stylesheet" href={{ asset("css/color-pallete.css") }}>

        {{-- Jquery --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>

    <body class="flex flex-col w-full min-h-screen text-xs sm:text-lg" style="background: url({{ asset('img/loginbg.png') }}) no-repeat right center; background-size: cover;">
		<div class="fixed text-white bottom-0 left-0 m-2">
			{{ trans("strings.version") }} <span id="screen"></span>
		</div>

		{{-- Loading popup --}}
		<div class="popup-container hidden w-full h-full fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(5px); z-index: 100; background: rgba(0, 0, 0, 0.3);">
			<div class="rounded-3xl bg-white py-5 px-6 popup w-1/3 h-1/4 flex gap-3 items-center justify-center" id="loading-popup">
				<div class="loader w-12 h-12 border-8 border-t-transparent border-light-blue rounded-full animate-spin"></div>
				<p class="font-extrabold text-xl animate-pulse">Please Wait...</p>
			</div>
		</div>

        {{-- Content --}}
        <div class="text-red sm:text-6xl text-3xl font-extrabold w-full flex flex-col items-center justify-center gap-5 grow px-10 h-screen" >
			<div class="flex flex-col p-20 rounded-xl items-center" style="background: rgba(254, 254, 254, 0.7);">
				<i class="bi bi-tools"></i>
				<p>This website is under maintenance.</p>
				<p class="text-blue text-xl sm:text-3xl">Sorry for any inconveniences caused.</p>

				<div class="shadow-lg rounded-xl flex flex-col items-start text-dark-blue sm:text-lg text-sm p-5 border sm:mt-6 mt-4 sm:w-1/2 w-full bg-white">
					<p class="underline">Developer's notes:</p>
					<p>Maintenance is held for updates. Estimated time for this maintenance until done is about 30 minutes. Thank you for your patience.</p>
				</div>
			</div>
        </div>

        <x-footer></x-footer>
    </body>

	<script>
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
			// Show loading popup on form submission
			$('form').on('submit', function () {
				showLoadingPopupWithDelay();
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
		});

		$("#screen").text(`(Resolution: ${window.innerWidth}x${window.innerHeight})`);

		$(window).on("resize", function(){
			$("#screen").text(`(Resolution: ${window.innerWidth}x${window.innerHeight})`);
		});

		// Hide the loading popup once the page is fully loaded
		$(window).on('load', hideLoadingPopup);
	</script>
</html>
