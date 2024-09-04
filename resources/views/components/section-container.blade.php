<div {{ $attributes->merge(['class' => 'w-full lg:w-5/6 rounded-3xl border border-white py-8 px-10', "style" => "background-color: rgba(255, 255, 255, 0.4); backdrop-filter: blur(3px);"]) }}>
	{{ $slot }}
</div>
