<a {{ $attributes->merge(['class' => 'inline-block text-white text-center hover:bg-slate-700 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 py-2 px-5 rounded-lg transition duration-300', 'style' => "cursor: url(" . asset('img/cursor2.cur') . "), pointer;"]) }}>
	{{ $slot }}
</a>
