@extends("layouts.main-admin")

@section("title")
	<h1>Manage Accounts</h1>
@endsection

@section("content")
	<x-page-title>{{ __("List of All Accounts") }}</x-page-title>

	@if(session()->has("successCreateNewAccount"))
		<div class="w-full bg-green-500 px-5 py-3 rounded-lg">
			<p class="text-green-900">{{ session("successCreateNewAccount") }}</p>
		</div>
	@elseif(session()->has("successDeleteAccount"))
		<div class="w-full bg-yellow-300 px-5 py-3 rounded-lg">
			<p class="text-yellow-600" >{{ session("successDeleteAccount") }}</p>
		</div>
	@elseif(session()->has("successEnableAccount"))
		<div class="w-full bg-green-500 px-5 py-3 rounded-lg">
			<p class="text-green-900">{{ session("successEnableAccount") }}</p>
		</div>
	@elseif(session()->has("successDisableAccount"))
		<div class="w-full bg-yellow-300 px-5 py-3 rounded-lg">
			<p class="text-yellow-600" >{{ session("successDisableAccount") }}</p>
		</div>
	@endif

	<div class="mb-5">
		<x-anchor-button class="bg-orange-500 mt-5" href="{{ route('admin.account.create') }}"><i class="bi bi-plus-lg"></i> Create New Account</x-anchor-button>
	</div>

	<div class="rounded-3xl mt-8 px-10 py-8 bg-indigo-200 {{--overflow-y-auto--}}" {{-- style="max-height: 550px" --}}>

		@foreach (['admin_accounts', 'teacher_accounts', 'student_accounts'] as $index => $account_type)
			<h1 class="text-lg font-semibold">{{ App\Models\Role::where("id", $index + 1)->first()->role_name }} Accounts</h1>
			<div class="overflow-x-auto">
				<table class="min-w-full border-collapse sm:table text-sm" style="border-collapse: separate; border-spacing: 0 20px;">
					<thead>
						<tr class="bg-blue-900 text-white">
							<th class="font-bold px-4 py-5 rounded-l-xl">Full Name</th>
							<th class="font-bold px-4 py-5">Email</th>
							<th class="font-bold px-4 py-5">Role</th>
							<th class="font-bold px-4 py-5">Status</th>
							<th class="font-bold px-4 py-5 rounded-r-xl">Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($$account_type as $account)
							@if($account->id == auth()->user()->id)
								@continue
							@endif

							<tr class="bg-blue-800 text-white">
								<td class="px-4 py-5 rounded-l-xl text-center"><a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $account->full_name }}</a></td>
								<td class="px-4 py-5 text-center">{{ $account->email }}</td>
								<td class="px-4 py-5 text-center">{{ $account->role->role_name }}</td>
								<td class="px-4 py-5 text-center">{{ $account->status }}</td>
								<td class="px-4 py-5 rounded-r-xl">
									<div class="flex w-full items-stretch gap-1 justify-center">
										<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.show', $account->id) }}">
											<i class="bi bi-eye"></i>
										</x-anchor-button>

										<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_edit', $account->id) }}">
											<i class="bi bi-pencil-square"></i>
										</x-anchor-button>

										<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_disable.conf', $account->id) }}">
											<i class="bi bi-ban"></i>
										</x-anchor-button>

										<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_delete', $account->id) }}">
											<i class="bi bi-trash3"></i>
										</x-anchor-button>
									</div>

								</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="bg-white px-4 py-5 rounded-xl text-center">- No accounts available to use yet -</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			<div class="my-5 md:my-2 flex w-full justify-end">
				{{ $$account_type->links() }}
			</div>

		@endforeach
	</div>

	<x-page-title class="mt-10">{{ __("Disabled Accounts") }}</x-page-title>
	<div class="overflow-x-auto rounded-lg mt-8 px-10 py-5 bg-indigo-200">
		<table class="min-w-full border-collapse sm:table text-sm" style="border-collapse: separate;
		border-spacing: 0 20px;">
			<thead>
				<tr class="bg-blue-900 text-white">
					<th class="font-bold px-4 py-5 rounded-l-xl">Full Name</th>
					<th class="font-bold px-4 py-5">Email</th>
					<th class="font-bold px-4 py-5">Role</th>
					<th class="font-bold px-4 py-5">Status</th>
					<th class="font-bold px-4 py-5 rounded-r-xl">Actions</th>
				</tr>
			</thead>
			<tbody>
				@if($disabled->count())
					@foreach ($disabled as $account)
						@if($account->id == auth()->user()->id)
							@continue
						@endif

						<tr class="bg-blue-800 text-white">
							<td class="px-4 py-5 rounded-l-xl"><a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $account->full_name }}</a></td>
							<td class="px-4 py-5 text-center">{{ $account->email }}</td>
							<td class="px-4 py-5 text-center">{{ $account->role->role_name }}</td>
							<td class="px-4 py-5 text-center">{{ $account->status }}</td>
							<td class="px-4 py-5 rounded-r-xl">
								<div class="flex w-full items-stretch justify-center gap-1">
									<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.show', $account->id) }}">
										<i class="bi bi-eye"></i>
									</x-anchor-button>

									<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_edit', $account->id) }}">
										<i class="bi bi-pencil-square"></i>
									</x-anchor-button>

									<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_enable.conf', $account->id) }}">
										<i class="bi bi-check-circle"></i>
									</x-anchor-button>

									<x-anchor-button class="bg-orange-500" href="{{ route('admin.account.acc_delete', $account->id) }}">
										<i class="bi bi-trash3"></i>
									</x-anchor-button>
								</div>

							</td>
						</tr>
					@endforeach
				@else
					<tr><td colspan="5" class="bg-white px-4 py-5 rounded-xl text-center">- No accounts disabled yet -</td></tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection
