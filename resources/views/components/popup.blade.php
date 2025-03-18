@props(['popup_title' => 'Popup Title'])

<div class="popup-container w-full h-full fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(10px);  z-index: 60; background: rgba(0, 0, 0, 0.3); display: none;">
	<div {!! $attributes->merge(['class' => 'rounded-3xl bg-white py-5 px-6 popup']) !!}>
		{{-- Popup header --}}
		<div class="flex items-center w-full">
			<div class="font-bold text-2xl grow text-center">{!! $popup_title !!}</div>
			<button type="button" class="popup-dismiss">
				<img src="{{ asset('img/close.svg') }}" alt="history-icon" class="w-6 h-6 hover:scale-110">
			</button>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		{{-- Popup content --}}
		{{ $slot }}
	</div>
</div>
