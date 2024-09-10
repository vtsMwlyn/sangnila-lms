<table {{ $attributes->merge(['class' => 'template-tables min-w-full border-collapse sm:table']) }} style="border-collapse: separate; border-spacing: 0 20px;">
	<thead>
		<tr class="uppercase text-sm">
			{{ $head }}
		</tr>
	</thead>
	<tbody class="text-sm">
		{{ $slot }}
	</tbody>
</table>
