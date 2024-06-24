<table {{ $attributes->merge(['class' => 'template-tables min-w-full border-collapse sm:table']) }} style="border-collapse: separate; border-spacing: 0 20px;">
	<thead>
		<tr>
			{{ $head }}
		</tr>
	</thead>
	<tbody>
		{{ $slot }}
	</tbody>
</table>
