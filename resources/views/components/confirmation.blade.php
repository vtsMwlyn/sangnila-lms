@props(['popup_title' => 'Popup Title', 'method' => ''])

<div class="popup-container w-full h-full fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(5px); z-index: 60; background: rgba(0, 0, 0, 0.3); display: none;">
	<div {!! $attributes->merge(['class' => 'rounded-3xl bg-white py-5 px-6 popup w-full sm:w-1/2']) !!}>
		<!-- Popup header -->
		<div class="flex items-center w-full">
			<div class="font-bold text-2xl grow text-center">{!! $popup_title !!}</div>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		<!-- Popup content -->
		<div class="overflow-y-auto w-full">
			<form method="post" class="mt-4">
				@csrf

				@if($method && $method != '')
					@method($method)
				@endif

				<p class="text-center">
					{{ $slot }}
				</p>

				<div class="flex items-stretch gap-3 justify-center mt-6 mb-3">
					<x-button class=" w-full md:w-1/5">
						Yes
					</x-button>
					<x-cancel-button showCancelMsg="false" type="button" class="popup-dismiss w-full md:w-1/5">
						No
					</x-cancel-button>
				</div>
			</form>
		</div>
	</div>
</div>
