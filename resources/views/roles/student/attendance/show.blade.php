@extends("layouts.main-student")

@section("title")
	<h1>Attendance</h1>
@endsection

@section("breadcrumbs-extension")
	> <span>{{ $course_student->course->course_name }}</span>
@endsection

@section("content")
	<div class="rounded-3xl w-full py-5 px-8 mb-6 flex flex-col sm:text-base text-sm" style="background: #FEFEFEB2;">

		<div class="relative flex flex-col items-start w-60 dropdown-container">
			<button type="button" class="border-slate-400 py-2 px-4 rounded-2xl font-bold text-dark-blue w-full bg-white flex justify-between items-center dropdown-toggler" style="border-width: 3px;">{{ $course_student->course->course_name }} <img src="{{ asset('img/dropdown-arrow.svg') }}" class="w-5 h-5" alt="icon"></button>
			<div class="absolute bg-slate-100 top-12 w-full rounded-xl flex flex-col hidden overflow-hidden dropdown-menu" style="box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">
				@foreach(Auth::user()->enrolled_courses as $c)
					<a href="{{ route('student.attendance.show', $c->id) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">{{ $c->course_name }}</div></a>
				@endforeach
			</div>
		</div>
		<div class="w-full bg-slate-400 mt-4" style="height: 2px;"></div>

		<div class="flex flex-col md:flex-row w-full my-4">
			<div class="text-lg w-full md:w-1/4">Attendance Summary</div>

			<div class="flex grow">
				<div class="flex flex-col w-full md:w-1/5">
					<h2>Total Session</h2>
					<h1 class="font-extrabold text-xl">{{ $course_student->max_course_session }}</h1>
				</div>
				<div class="flex flex-col w-full md:w-1/5">
					<h2>Total Attendance</h2>
					<h1 class="font-extrabold text-xl">{{ $n_attend }}</h1>
				</div>
				<div class="flex flex-col w-full md:w-1/5">
					<h2>Minimal Attendance</h2>
					<h1 class="font-extrabold text-xl">N/A</h1>
				</div>
			</div>
		</div>
		<div class="w-full bg-slate-400" style="height: 2px;"></div>

		<div class="w-full overflow-x-auto">
			<table class="w-full">
				<thead>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Session</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Delivery</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">Start Date</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400">End Date</th>
					<th class="text-center py-3 px-4 border-b-2 border-slate-400">Attend</th>
					<th class="text-start py-3 px-4 border-b-2 border-slate-400" style="max-width: 300px;">Note</th>
				</thead>
				<tbody>
					@forelse($attendances as $atd)
						<tr class="@if($loop->iteration % 2 == 1) bg-white @endif">
							<td class="py-2 px-4">Session {{ $loop->iteration }}</td>
							<td class="py-2 px-4">Onsite/Online</td>
							<td class="py-2 px-4">{{ Carbon\Carbon::parse($atd->attendance->attendance_date)->format('d M Y') }}</td>
							<td class="py-2 px-4">{{ Carbon\Carbon::parse($atd->attendance->attendance_date)->format('d M Y') }}</td>
							<td class="py-2 px-4">
								<div class="w-full flex justify-center">
									@if($atd->is_attend == 1)
										<img src="{{ asset('img/yesbox.svg') }}" class="h-6 w-6" alt="icon">
									@else
										<img src="{{ asset('img/nobox.svg') }}" class="h-6 w-6" alt="icon">
									@endif
								</div>
							</td>
							<td class="py-2 px-4" style="max-width: 300px;">
								{{ $atd->attendance_detail }}
							</td>
						</tr>
					@empty
						<tr class="bg-white">
							<td class="py-2 px-4 text-center" colspan="6">- No data found -</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
@endsection
