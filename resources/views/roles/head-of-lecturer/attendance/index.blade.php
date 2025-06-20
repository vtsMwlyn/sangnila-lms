@extends("layouts.main-head-of-lecturer")

@section("title")
	<h1>Attendances</h1>
@endsection

@section("content")
    <div class="flex items-center justify-center w-full">
        <form class="flex w-1/2 justify-center" action="{{ route("head-of-lecturer.attendance.index") }}">
            <x-input type="text" class="rounded-l-lg rounded-r-none w-full" name="search" placeholder="Search course..." :value="request('search')"/>
            <button class="rounded-l-none rounded-r-lg border-slate-400 border-t-2 border-r-2 border-b-2 py-2 px-4 bg-white text-slate-400 hover:bg-slate-400 hover:text-white" style="border-width: 3px 3px 3px 0;"><i class="bi bi-search"></i></button>
        </form>
    </div>

	<div class="w-full flex flex-wrap gap-5 mt-6 items-stretch">
		@forelse ($courses as $course)
			<a href="{{ route('head-of-lecturer.attendance.show', $course->id) }}"  class="flex oneperthree transition duration-300 hover:scale-[102%] relative">
				<div class="rounded-3xl p-5 shadow-lg w-full" style="background-color: #FEFEFEB2;">
					{{-- Course information --}}
					<p class="font-bold text-dark-blue">{{ $course->course_name }} - {{ ucwords($course->level) }}</p>
					<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

					<div class="flex gap-2 items-center">
						<img src="{{ asset('img/lecturer.svg') }}" class="w-4 h-4" alt="icon">
                        @php
                            if($course->teachers->count()){
                                $teacherList = [];

                                foreach ($course->teachers as $teacher){
                                    $teacherList[] = ($teacher->details->gender == 1? 'Mr. ' : 'Ms. ') . explode(" ", $teacher->full_name)[0];
                                }

                                $lastItem = array_pop($teacherList);
                                $formattedString = implode(', ', $teacherList);

                                echo $formattedString . (empty($formattedString) ? '' : ', and ') . $lastItem;
                            }
                            else {
                                echo 'No teachers assigned';
                            }
                        @endphp
					</div>
					<div class="flex gap-2 items-center">
						<i class="bi bi-book-half text-slate-400"></i>{{ $course->self_attendances()->whereHas('user', function($query){
                            return $query->where('role_id', 2);
                        })->where('self_attendance_date', Carbon\Carbon::today()->format('Y-m-d'))->get()->count() }} Lecturer Check Ins Today
					</div>
				</div>
			</a>
		@empty
			<div class="bg-white rounded-xl text-center font-semibold w-full mt-5 p-5">- No courses found -</div>
		@endforelse
	</div>
@endsection
