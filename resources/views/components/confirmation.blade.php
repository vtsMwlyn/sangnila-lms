@props(['popup_title' => 'Popup Title', 'method' => '', 'type' => 'delete'])

<div class="popup-container w-full h-full fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(5px);  z-index: 60; background: rgba(0, 0, 0, 0.3); display: none;">
	<div {!! $attributes->merge(['class' => 'rounded-3xl bg-white py-5 px-6 popup w-11/12 xl:w-1/2']) !!}>
		{{-- Popup header --}}
		<div class="flex items-center w-full">
			<div class="font-bold text-2xl grow text-center">{!! $popup_title !!}</div>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		{{-- Popup content --}}
		<div class="overflow-y-auto w-full">
			@if($type == 'delete')
				<form method="post" class="mt-4">
					@csrf

					@if($method && $method != '')
						@method($method)
					@endif

					<p class="text-center">
						{{ $slot }}
					</p>

					<div class="flex items-stretch gap-3 justify-center mt-6 mb-3">
						<x-button class="w-full md:w-1/4">
							Yes
						</x-button>
						<x-button type="button" class="popup-no w-full md:w-1/4" style="background: rgb(148 163 184) !important;">
							No
						</x-button>
					</div>
				</form>
			@else
				<div class="mt-4">
					<p class="text-center">
						{{ $slot }}
					</p>

					<div class="flex items-stretch gap-3 justify-center mt-6 mb-3">
						<x-button class="proceed-cancel w-full md:w-1/4">
							Yes
						</x-button>
						<x-button type="button" class="popup-no w-full md:w-1/4" style="background: rgb(148 163 184) !important;">
							No
						</x-button>
					</div>
				</div>
			@endif
		</div>
	</div>

	{{-- Special Events Only --}}
	{{-- <img class="absolute bottom-0 right-0 w-40 xl:w-80" src="{{ asset('img/special-events/cakeandgift.png') }}"/>
	<img class="absolute bottom-0 left-0 w-28 xl:w-56" src="{{ asset('img/special-events/decobundle.png') }}"/> --}}

	<script>
		$('.popup-no').on('click', function(){
			$(this).closest('.popup-container').fadeOut();
		});

		$('.proceed-cancel').on('click', function(){
			history.back();
		})
	</script>
</div>
