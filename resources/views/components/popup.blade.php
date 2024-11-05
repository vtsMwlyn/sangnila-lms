<div class="popup-container w-full h-full fixed top-0 flex items-center justify-center" style="backdrop-filter: blur(5px); z-index: 60; background: rgba(0, 0, 0, 0.3); display: none;">
	<div {!! $attributes->merge(['class' => 'rounded-3xl bg-white py-5 px-6 popup']) !!}>
		{{ $slot }}
	</div>
</div>
