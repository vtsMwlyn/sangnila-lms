<button
	{{ $attributes->merge([
		'type' => 'submit',
		'class' => 'text-center px-5 py-2 border-transparent rounded-xl text-white font-semibold hover:bg-slate-800 hover:scale-105 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150'
	]) }}
	style="box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); background: linear-gradient(90deg, #1EB8CD 0%, #354D9B 100%);">

	{{ $slot }}

</button>
