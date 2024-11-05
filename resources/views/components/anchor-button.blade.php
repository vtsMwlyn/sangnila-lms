<a {{ $attributes->merge(['class' => 'inline-block text-white font-semibold text-center hover:bg-slate-700 focus:scale-95 focus:outline-none focus:border-slate-900 py-2 px-5 rounded-xl hover:scale-105']) }} style="box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3); background: linear-gradient(90deg, #1EB8CD 0%, #354D9B 100%);">
	{{ $slot }}
</a>
