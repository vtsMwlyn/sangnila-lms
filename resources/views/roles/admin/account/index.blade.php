@extends("layouts.main-admin")

@section("title")
	<h1>Manage Accounts</h1>
@endsection

@section("popup")
	{{-- Reset password --}}
	<x-confirmation popup_title="Reset Password" id="reset-password-popup">
		Are you sure want to <span class="font-bold text-red">reset the password</span> for <span class="font-bold text-light-blue" id="reset-password-name"></span>'s account? <strong>This action will reset the account's password to the default password.</strong>
	</x-confirmation>

	{{-- Enable account --}}
	<x-confirmation popup_title="Enable Account" id="enable-account-popup">
		Are you sure want to <span class="font-bold text-red">enable</span> <span class="font-bold text-light-blue" id="enable-account-name"></span>'s account? <strong>This action will make the account can be used again in Sangnila LMS.</strong>
	</x-confirmation>

	{{-- Delete account --}}
	<x-confirmation method="delete" popup_title="Delete Account" id="delete-account-popup">
		Are you sure want to <span class="font-bold text-red">delete</span> <span class="font-bold text-light-blue" id="del-account-name"></span>'s account from Sangnila LMS? <strong>This action will erase all data related to the account and can't be undone! <i>(It's recommended to disable the account instead of deleting it!)</i></strong>
	</x-confirmation>

	{{-- Disable account --}}
	<x-popup popup_title="Disable Account" class="w-3/5 flex flex-col items-stretch justify-center overflow-y-auto" id="disable-account-popup">
		<div class="overflow-y-auto w-full" style="max-height: 50vh;">
			<form method="post" class="mt-4">
				@csrf

				<p>Are you sure want to <span class="font-bold text-red">disable</span> <span class="font-bold text-light-blue" id="disable-account-name"></span>'s account? <strong>This action will make the account cannot be used again in Sangnila LMS.</strong></p>

				<div class="flex flex-col mt-3">
					<label for="disable_reason">Disable Reason<span class="text-red">*</span></label>
					<x-input id="disable_reason" class="w-full mt-1" type="text" name="disable_reason" value="{{ old('disable_reason') }}" placeholder="Enter the reason for disabling account" autofocus />
				</div>

				<div class="flex items-center justify-center w-full mt-8 mb-3 gap-3">
					<x-button class="w-full md:w-40 lg:w-1/6">Submit</x-button>
					{{-- <x-button class="w-full md:w-40 lg:w-1/6">Cancel</x-button> --}}
				</div>

				{{-- Helper --}}
				<input type="hidden" name="h-last-popup" class="h-last-popup">
				<input type="hidden" name="h-route" class="h-route">
				<input type="hidden" name="h-account-name" id="h-account-name">
			</form>
		</div>
	</x-popup>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="text-center">{{ __("List of All Accounts") }}</x-page-title>

		<div class="flex items-center mt-6 mb-2">
			<div class="w-1/4">
				<x-anchor-button href="{{ route('admin.account.create') }}"><i class="bi bi-plus-lg"></i> Create New Account</x-anchor-button>
			</div>

			<form class="flex w-1/2 justify-center" action="{{ route("admin.account.index") }}">
				<x-input type="text" class="rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search..." :value="request('search')"/>
				<input type="hidden" name="role" value="{{ request('role') }}">
				<button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
			</form>
		</div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		{{-- For larger screen --}}
		<div class="lg:flex mt-4 w-full flex-wrap hidden">
			<a href="{{ route('admin.account.index', ['role' => 'admin']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('role') == 'admin' || !request('role')) border-bottom: 4px solid #1db9cf; @endif">
				Admin
			</a>

			<a href="{{ route('admin.account.index', ['role' => 'teacher']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('role') == 'teacher') border-bottom: 4px solid #1db9cf; @endif">
				Teacher
			</a>

			<a href="{{ route('admin.account.index', ['role' => 'student']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('role') == 'student') border-bottom: 4px solid #1db9cf; @endif">
				Student
			</a>

			<a href="{{ route('admin.account.index', ['role' => 'disabled']) }}"
				class="py-2 w-1/6 sm:w-40 text-center hover:bg-slate-200"
				style="@if(request('role') == 'disabled') border-bottom: 4px solid #1db9cf; @endif">
				Disabled
			</a>
		</div>

		{{-- For smaller screen --}}
		<div class="relative flex lg:hidden flex-col items-start w-96 first-letter:0 dropdown-container mt-4">
			<button type="button" class="border-slate-400 py-2 px-4 rounded-2xl font-bold text-dark-blue w-full bg-white flex justify-between items-center dropdown-toggler">{{ request('role') ? ucwords(request('role')) : 'Admin' }} <img src="{{ asset('img/dropdown-arrow.svg') }}" class="w-5 h-5" alt="icon"></button>
			<div class="absolute bg-white top-12 w-full rounded-xl flex flex-col hidden overflow-hidden dropdown-menu" style="">
				<a href="{{ route('admin.account.index', ['role' => 'admin']) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">Admin</div></a>
				<a href="{{ route('admin.account.index', ['role' => 'teacher']) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">Teacher</div></a>
				<a href="{{ route('admin.account.index', ['role' => 'student']) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">Student</div></a>
				<a href="{{ route('admin.account.index', ['role' => 'disabled']) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">Disabled</div></a>
			</div>
		</div>
		<div class="w-full bg-slate-400 mt-2" style="height: 2px;"></div>

		@if(request('role') == 'admin' || !request('role'))
			{{-- Admin accounts --}}
			<div class="w-full overflow-x-auto" style="max-height: 500px;" id="admin-accounts">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Full Name</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Email</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Role</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody>
						@forelse ($admin_accounts as $admin_acc)
							@if($admin_acc->email == 'sysadmin@sangnilaindonesia.com')
								@continue
							@endif
							<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
								<td class="py-3 px-4">
									<div class="flex w-full items-center gap-3">
										@if($admin_acc->details->profpic)
											<img src="{{ Storage::url("app/public/" . $admin_acc->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
										@else
											<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
										@endif
										{{ $admin_acc->full_name }}
									</div>
								</td>
								<td class="py-3 px-4">{{ $admin_acc->email }}</td>
								<td class="py-3 px-4">{{ ucwords($admin_acc->role->role_name) }}</td>
								<td class="py-3 px-4 font-semibold @if($admin_acc->status == "enabled") text-light-blue @else text-red @endif">{{ ucwords($admin_acc->status) }}</td>
								<td class="py-3 px-4">
									<div class="flex w-full items-stretch gap-1 justify-start">
										<button type="button" class="reset-password-btn h-8 w-8 flex items-center justify-center hover:scale-110" data-account_name="{{ $admin_acc->full_name }}" data-route="{{ route('admin.account.reset-password.proceed', $admin_acc->id) }}">
											<img src="{{ asset('img/reset-password.svg') }}">
										</button>
										<a href="{{ route('admin.account.acc_edit', $admin_acc->id) }}">
											<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</a>
										<button type="button" class="disable-account-btn" data-account_name="{{ $admin_acc->full_name }}" data-route="{{ route('admin.account.acc_disable', $admin_acc->id) }}">
											<img src="{{ asset('img/ban.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
										<button type="button" class="delete-account-btn" data-account_name="{{ $admin_acc->full_name }}" data-route="{{ route('admin.account.acc_delete', $admin_acc->id) }}">
											<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
									</div>
								</td>
							</tr>
						@empty
							<tr class="bg-white">
								<td class="py-3 px-4 text-center" colspan="5">- No data found -</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		@endif

		@if(request('role') == 'teacher')
			{{-- Teacher accounts --}}
			<div class="w-full overflow-x-auto" style="max-height: 500px;" id="teacher-accounts">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Full Name</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Email</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Role</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody>
						@forelse ($teacher_accounts as $teacher_acc)
							<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
								<td class="py-3 px-4">
									<div class="flex w-full items-center gap-3">
										@if($teacher_acc->details->profpic)
											<img src="{{ Storage::url("app/public/" . $teacher_acc->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
										@else
											<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
										@endif
										{{ $teacher_acc->details->gender == 1? 'Mr. ' : 'Ms. ' }} {{ $teacher_acc->full_name }}
									</div>

								</td>
								<td class="py-3 px-4">{{ $teacher_acc->email }}</td>
								<td class="py-3 px-4">{{ ucwords($teacher_acc->role->role_name) }}</td>
								<td class="py-3 px-4 font-semibold @if($teacher_acc->status == "enabled") text-light-blue @else text-red @endif">{{ ucwords($teacher_acc->status) }}</td>
								<td class="py-3 px-4">
									<div class="flex w-full items-stretch gap-1 justify-start">
										<button type="button" class="reset-password-btn h-8 w-8 flex items-center justify-center hover:scale-110" data-account_name="{{ $teacher_acc->full_name }}" data-route="{{ route('admin.account.reset-password.proceed', $teacher_acc->id) }}">
											<img src="{{ asset('img/reset-password.svg') }}">
										</button>
										<a href="{{ route('admin.account.acc_edit', $teacher_acc->id) }}">
											<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</a>
										<button type="button" class="disable-account-btn" data-account_name="{{ $teacher_acc->full_name }}" data-route="{{ route('admin.account.acc_disable', $teacher_acc->id) }}">
											<img src="{{ asset('img/ban.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
										<button type="button" class="delete-account-btn" data-account_name="{{ $teacher_acc->full_name }}" data-route="{{ route('admin.account.acc_delete', $teacher_acc->id) }}">
											<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
									</div>
								</td>
							</tr>
						@empty
							<tr class="bg-white">
								<td class="py-3 px-4 text-center" colspan="5">- No data found -</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		@endif

		@if(request('role') == 'student')
			{{-- Student accounts --}}
			<div class="w-full overflow-x-auto" style="max-height: 500px;" id="student-accounts">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Full Name</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Email</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Role</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody>
						@forelse ($student_accounts as $student_acc)
							<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
								<td class="py-3 px-4">
									<div class="flex w-full items-center gap-3">
										@if($student_acc->details->profpic)
											<img src="{{ Storage::url("app/public/" . $student_acc->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
										@else
											<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
										@endif
										{{ $student_acc->full_name }}
									</div>
								</td>
								<td class="py-3 px-4">{{ $student_acc->email }}</td>
								<td class="py-3 px-4">{{ ucwords($student_acc->role->role_name) }}</td>
								<td class="py-3 px-4 font-semibold @if($student_acc->status == "enabled") text-light-blue @else text-red @endif">{{ ucwords($student_acc->status) }}</td>
								<td class="py-3 px-4">
									<div class="flex w-full items-stretch gap-1 justify-start">
										<button type="button" class="reset-password-btn h-8 w-8 flex items-center justify-center hover:scale-110" data-account_name="{{ $student_acc->full_name }}" data-route="{{ route('admin.account.reset-password.proceed', $student_acc->id) }}">
											<img src="{{ asset('img/reset-password.svg') }}">
										</button>
										<a href="{{ route('admin.account.acc_edit', $student_acc->id) }}">
											<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</a>
										<button type="button" class="disable-account-btn" data-account_name="{{ $student_acc->full_name }}" data-route="{{ route('admin.account.acc_disable', $student_acc->id) }}">
											<img src="{{ asset('img/ban.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
										<button type="button" class="delete-account-btn" data-account_name="{{ $student_acc->full_name }}" data-route="{{ route('admin.account.acc_delete', $student_acc->id) }}">
											<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
									</div>
								</td>
							</tr>
						@empty
							<tr class="bg-white">
								<td class="py-3 px-4 text-center" colspan="5">- No data found -</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		@endif

		@if(request('role') == 'disabled')
			{{-- Disabled accounts --}}
			<div class="w-full overflow-x-auto" style="max-height: 500px;" id="disabled-accounts">
				<table class="w-full">
					<thead>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Full Name</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Email</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Role</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Status</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Reason</th>
						<th class="text-start py-3 px-4 border-b-2 border-slate-400">Actions</th>
					</thead>
					<tbody>
						@forelse ($disabled as $disabled_acc)
							<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
								<td class="py-3 px-4">
									<div class="flex w-full items-center gap-3">
										@if($disabled_acc->details->profpic)
											<img src="{{ Storage::url("app/public/" . $disabled_acc->details->profpic) }}" class="rounded-full w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center;">
										@else
											<img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
										@endif
										{{ $disabled_acc->full_name }}
									</div>
								</td>
								<td class="py-3 px-4">{{ $disabled_acc->email }}</td>
								<td class="py-3 px-4">{{ ucwords($disabled_acc->role->role_name) }}</td>
								<td class="py-3 px-4 font-semibold @if($disabled_acc->status == "enabled") text-light-blue @else text-red @endif">{{ ucwords($disabled_acc->status) }}</td>
								<td class="py-3 px-4">{{ $disabled_acc->disable_reason }}</td>
								<td class="py-3 px-4">
									<div class="flex w-full items-stretch gap-1 justify-start">
										<button type="button" class="reset-password-btn h-8 w-8 flex items-center justify-center hover:scale-110" data-account_name="{{ $disabled_acc->full_name }}" data-route="{{ route('admin.account.reset-password.proceed', $disabled_acc->id) }}">
											<img src="{{ asset('img/reset-password.svg') }}">
										</button>
										<a href="{{ route('admin.account.acc_edit', $disabled_acc->id) }}">
											<img src="{{ asset('img/edit.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</a>
										<button type="button" class="enable-account-btn" data-account_name="{{ $disabled_acc->full_name }}" data-route="{{ route('admin.account.acc_enable.conf', $disabled_acc->id) }}">
											<img src="{{ asset('img/unban.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
										<button type="button" class="delete-account-btn" data-account_name="{{ $disabled_acc->full_name }}" data-route="{{ route('admin.account.acc_delete', $disabled_acc->id) }}">
											<img src="{{ asset('img/delete-button.svg') }}" alt="icon" class="max-w-8 min-w-8 max-h-8 min-h-8 hover:scale-110">
										</button>
									</div>
								</td>
							</tr>
						@empty
							<tr class="bg-white">
								<td class="py-3 px-4 text-center" colspan="6">- No data found -</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		@endif
	</x-section-container>

	<script>
		function initializeDisableAccountPopup(route, whichpopup, account){
			$('#disable-account-name').text(account);
			$(".h-route").val(route);
			$("#h-account-name").val(account)
			$(".h-last-popup").val(whichpopup);
			$(`#${whichpopup}`).find('form').attr('action', route);

			$(`#${whichpopup}`).parent().show();
		}

		$('.reset-password-btn').click(function(){
			$('#reset-password-name').text($(this).data('account_name'));
			$('#reset-password-popup').find('form').attr('action', $(this).data('route'));
			$('#reset-password-popup').parent().show();
		});

		$('.enable-account-btn').click(function(){
			$('#enable-account-name').text($(this).data('account_name'));
			$('#enable-account-popup').find('form').attr('action', $(this).data('route'));
			$('#enable-account-popup').parent().show();
		});

		$('.delete-account-btn').click(function(){
			$('#del-account-name').text($(this).data('account_name'));
			$('#delete-account-popup').find('form').attr('action', $(this).data('route'));
			$('#delete-account-popup').parent().show();
		});

		$('.disable-account-btn').click(function(){
			initializeDisableAccountPopup($(this).data('route'), 'disable-account-popup', $(this).data('account_name'));
		});

		// Redisplay popup and fill with prev data (for invalidated data)
		@if ($errors->any())
			// Retrieve and re-save saved data
			const old_popup = @json(old('h-last-popup'));

			if(old_popup == "disable-account-popup"){
				const old_route = @json(old('h-route'));
				const account = @json(old('h-account-name'));

				initializeDisableAccountPopup(old_route, old_popup, account);
			}
		@endif
	</script>
@endsection
