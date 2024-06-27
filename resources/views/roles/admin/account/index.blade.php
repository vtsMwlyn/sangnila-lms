@extends("layouts.main-admin")

@section("title")
	<h1>Manage Accounts</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("List of All Accounts") }}</x-page-title>

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

		@foreach (['admin_accounts', 'teacher_accounts', 'student_accounts'] as $index => $account_type)
			@php
				$role = App\Models\Role::where("id", $index + 1)->first();
			@endphp
			<div class="rounded-3xl px-5 py-4 @if($index != 0) mt-5 @endif @if(request("search") && request("role") == $role->id) bg-indigo-100 @endif">
				<div class="flex flex-col md:flex-row gap-5 md:gap-0 w-full justify-between items-center mb-6">
					@if(request("role") == $role->id)
						<h1 class="text-xl text-blue-950 font-bold">{{ $role->role_name }} Accounts <span class="italic">(Showing results for "{{ request("search") }}")</span></h1>
						<form class="flex" action="{{ route("admin.account.index") }}" onsubmit="handleFormSubmit();">
							<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none" name="search" placeholder="Search..." :value="request('search')"/>
							<input type="hidden" name="role" value="{{ $role->id }}">
							<x-button class="bg-white rounded-l-none rounded-r-lg border-blue-900 border-t-2 border-r-2 border-b-2 text-blue-900 hover:text-white"><i class="bi bi-search"></i></x-button>
						</form>
					@else
						<h1 class="text-xl text-blue-950 font-bold">{{ $role->role_name }} Accounts</h1>
						<form class="flex" action="{{ route("admin.account.index") }}" onsubmit="handleFormSubmit();">
							<x-input type="text" class="border-blue-900 border-2 rounded-l-lg rounded-r-none" name="search" placeholder="Search..." />
							<input type="hidden" name="role" value="{{ $role->id }}">
							<x-button class="bg-white rounded-l-none rounded-r-lg border-blue-900 border-t-2 border-r-2 border-b-2 text-blue-900 hover:text-white"><i class="bi bi-search"></i></x-button>
						</form>
					@endif
				</div>
				<div class="overflow-x-auto relative" style="max-height: 500px;">
					<table class="template-tables min-w-full border-collapse sm:table" style="border-collapse: separate; border-spacing: 0 20px;">
						<thead class="sticky top-0 z-10">
							<tr>
								<th class="template-heads rounded-l-xl">Full Name</th>
								<th class="template-heads">Email</th>
								<th class="template-heads">Role</th>
								<th class="template-heads">Status</th>
								<th class="template-heads rounded-r-xl">Actions</th>
							</tr>
						</thead>
						<tbody>
							@forelse($$account_type as $account)
								@if($account->id == auth()->user()->id)
									@continue
								@endif

								<tr>
									<td class="template-bodies rounded-l-xl" style="min-width: 200px; max-width: 200px; text-wrap: wrap;">
										@if($account->role_id == 2)
											<a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ ($account->details->gender == 1)? "Mr." : "Ms./Mrs." }} {{ $account->full_name }}</a>
										@else
											<a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $account->full_name }}</a>
										@endif
									</td>
									<td class="template-bodies" style="min-width: 200px; max-width: 200px; text-wrap: wrap; word-wrap: break-word;">{{ $account->email }}</td>
									<td class="template-bodies">{{ $account->role->role_name }}</td>
									<td class="template-bodies">{{ $account->status }}</td>
									<td class="template-bodies rounded-r-xl">
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
									<td colspan="5" class="bg-white p-5 rounded-xl text-center font-semibold">- No accounts available found for this role -</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				{{-- <div class="my-5 md:my-2 flex w-full justify-end">
					{{ $$account_type->links() }}
				</div> --}}
			</div>

		@endforeach
	</x-section-container>

	<x-section-container class="mt-10">
		<x-page-title class="mt-10">{{ __("Disabled Accounts") }}</x-page-title>
		<div class="overflow-x-auto">
			<table class="template-tables min-w-full border-collapse sm:table" style="border-collapse: separate; border-spacing: 0 20px;">
				<thead>
					<tr>
						<th class="template-heads rounded-l-xl">Full Name</th>
						<th class="template-heads">Email</th>
						<th class="template-heads">Role</th>
						<th class="template-heads">Status</th>
						<th class="template-heads rounded-r-xl">Actions</th>
					</tr>
				</thead>
				<tbody>
					@if($disabled->count())
						@foreach ($disabled as $account)
							@if($account->id == auth()->user()->id)
								@continue
							@endif

							<tr>
								<td class="template-bodies rounded-l-xl"><a href="{{ route('admin.account.show', $account->id) }}" class="font-bold text-blue-200 hover:text-blue-400 hover:underline">{{ $account->full_name }}</a></td>
								<td class="template-bodies">{{ $account->email }}</td>
								<td class="template-bodies">{{ $account->role->role_name }}</td>
								<td class="template-bodies">{{ $account->status }}</td>
								<td class="template-bodies rounded-r-xl">
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
						<tr><td colspan="5" class="bg-white p-5 rounded-xl text-center font-semibold">- No disabled accounts yet -</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</x-section-container>

	<script>
		function handleFormSubmit() {
			// Capture the current scroll position
			var scrollPosition = window.scrollY || window.pageYOffset;
			// Store the scroll position in local storage
			localStorage.setItem('scrollPosition', scrollPosition);
		}

		// Restore scroll position after the page loads
		window.onload = function() {
			var scrollPosition = localStorage.getItem('scrollPosition');
			if (scrollPosition !== null) {
				// Temporarily disable smooth scroll behavior
				document.documentElement.style.scrollBehavior = 'auto';
				window.scrollTo(0, parseInt(scrollPosition, 10));
				localStorage.removeItem('scrollPosition'); // Clean up
				// Re-enable smooth scroll behavior
				setTimeout(function() {
					document.documentElement.style.scrollBehavior = 'smooth';
				}, 100);
			}
		};
	</script>
@endsection
