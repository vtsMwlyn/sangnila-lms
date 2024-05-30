<button
	{{ $attributes->merge(['type' => 'submit', 'class' => 'text-center px-5 py-2 border border-transparent rounded-lg text-white hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150', 'style' => "cursor: url(" . asset('img/cursor2.cur') . "), pointer;"]) }}>
	{{ $slot }}
</button>
