@extends("layouts.main-teacher")

@section("title")
	<h1>Manage Students</h1>
@endsection

{{-- @section("breadcrumbs-extension")
	> <a href="{{ route('admin.course.show', $course->id) }}" class="font-bold text-yellow-500">{{ $course->course_name }}</a>
	> <span>Batch Assign</span>
@endsection --}}

@section("content")
	<x-section-container>
		<x-page-title>New Lecturer Invoice</x-page-title>
		<h1 class="text-xl font-semibold text-blue-900 mt-2">{{ $course->course_name }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<x-badge-danger id="emptyDataNotif" badge_text="Please input minimum 1 data to proceed." style="display: none;"></x-badge-danger>

        <form action="{{ route('teacher.attendance.lecturer-invoice.store', $course->id) }}" method="post" class="my-4">
            @csrf

            <input type="hidden" name="content" value="{{ request('content') }}">

            <div id="form-area">
                {{-- Invoice data --}}
                <h1 class="font-bold text-lg text-blue">Invoice Data</h1>
                <div class="flex gap-5">
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Invoice Date<span class="text-red">*</span></x-label>
                        <x-input type="date" name="date" id="date" class="w-full" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"/>
                        <p class="text-red font-bold mt-2 hidden" id="error-course"><i class="bi bi-exclamation-circle"></i> Please input invoice date.</p>
                    </div>
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Lecturer Name<span class="text-red">*</span></x-label>
                        <x-input type="text" name="_teacher_name" id="_teacher_name" class="w-full" disabled value="{{ Auth::user()->full_name }}"/>
                        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                    </div>
                </div>

                <div class="flex gap-5">
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Invoice Number<span class="text-red">*</span></x-label>
                        <x-input type="text" name="number" id="number" class="w-full" placeholder="(ex: 001, 002, etc)"/>
                        <p class="text-red font-bold mt-2 hidden" id="error-course"><i class="bi bi-exclamation-circle"></i> Please input invoice number.</p>
                    </div>
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Invoice Payment Info<span class="text-red">*</span></x-label>
                        <x-input type="text" name="bank_data" id="bank_data" class="w-full" placeholder="(ex: BCA 123456789 a/n Someone)"/>
                        <p class="text-red font-bold mt-2 hidden" id="error-course"><i class="bi bi-exclamation-circle"></i> Please input payment information.</p>
                    </div>
                </div>

                <div class="flex gap-5">
                    <div class="mt-3 w-full md:w-1/2">
                        <x-label class="mb-1">Rate<span class="text-red">*</span></x-label>
                        <x-input type="number" name="rate" id="rate" class="w-full" placeholder="(ex: 75000, 125000)" value="75000" />
                        <p class="text-red font-bold mt-2 hidden" id="error-course"><i class="bi bi-exclamation-circle"></i> Please input rate.</p>
                    </div>
                    <div class="mt-3 w-full md:w-1/2">
                    </div>
                </div>

                {{-- Invoice Items --}}
                <h1 class="font-bold text-lg text-blue mt-8">Invoice Items</h1>
                <p class="italic">- The system will generate invoice and its content based on your submitted attendance data -</p>
            </div>

            <div class="mt-10 w-full flex gap-3 justify-end items-center" method="post" id="leForm">
                <x-cancel-button class="w-1/2 md:w-1/6">Cancel</x-cancel-button>
                <x-button class=" w-1/2 md:w-1/6">Submit Data</x-button>
            </div>
        </form>
	</x-section-container>
@endsection

{{-- <div class="flex w-full flex-col gap-5 mt-4">
    @forelse ($student_attendances as $student_id => $studentAttendances)
        <div class="flex flex-col w-full bg-white rounded-xl p-6 invoice-item-card">
            <button type="button" class="w-full accordion-btn flex justify-between items-center pb-3">
                @php
                    $student = App\Models\User::find($student_id);
                @endphp
                <div class="flex gap-3 items-center">
                    @if($student->details->profpic)
                        <img src="{{ Storage::url("app/public/" . $student->details->profpic) }}" class="rounded-full w-12 h-12 card_profpic" alt="profpic" style="object-fit: cover; object-position: center;">
                    @else
                        <img src="{{ asset('img/tempblankprofpic.png') }}" class="rounded-full border-slate-400 w-12 h-12 card_profpic" alt="profpic" style="object-fit: cover; object-position: center; border-width: 3px;">
                    @endif
                    <div class="accordion-title font-bold">{{ $course->course_name }} - {{ ucwords($course->level) }} - {{ $student->full_name }}</div>
                </div>
                <div class="accordion-icon"><i class="bi bi-chevron-up text-slate-600"></i></div>
            </button>

            <div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

            <div class="flex flex-col w-full accordion-area">
                <div class="flex w-full justify-end">
                    <button type="button" class="bg-red text-white rounded-xl hover:bg-slate-600 py-2 px-4 remove-item-btn" onclick="return confirm('Are you sure want to remove this item from the new invoice?');"><i class="bi bi-trash3"></i> Discard</button>
                </div>

                <div class="w-full flex gap-5">
                    <div class="w-full md:w-1/2">
                        <x-label class="mb-1">Activity Date<span class="text-red">*</span></x-label>
                        <x-input type="date" name="_activity_date" id="_activity_date" class="w-full date-input" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"/>
                    </div>
                    <div class="w-full md:w-1/2 select2-container">
                        <x-label class="mb-1">Student Name<span class="text-red">*</span></x-label>
                        <x-select name="_student_name" id="_student_name" class="w-full select-2">
                            <option selected disabled>Pick a Student</option>
                            @forelse ($course->course_students as $cs)
                                <option value="{{ $cs->student->id }}">{{ $cs->student->full_name }}</option>
                            @empty
                            @endforelse
                        </x-select>
                    </div>
                </div>

                <div class="flex gap-5 w-full mt-4 attendance-detail-fields">

                    <div class="flex flex-col w-1/2">
                        <x-label for="_start_time">Start Time<span class="text-red">*</span></x-label>
                        <x-input class="_start_time" type="time" id="_start_time" value="00:00"/>
                    </div>


                    <div class="flex flex-col w-1/2">
                        <x-label for="_end_time">End Time<span class="text-red">*</span></x-label>
                        <x-input class="_end_time" type="time" id="_end_time" value="00:00"/>
                    </div>
                </div>

                <div class="flex gap-5 w-full mt-4 attendance-detail-fields">
    
                    <div class="flex flex-col w-1/2">
                        <x-label for="task">Task<span class="text-red">*</span></x-label>
                        <x-input class="task" type="text" id="task" value="Lecturer"/>
                    </div>

                    <div class="flex flex-col w-1/2">
                    </div>
                </div>

                <div class="flex w-full justify-end mt-8">
                    <x-button class=" w-1/2 md:w-1/6" type="button" id="addBtn">Add Data</x-button>
                </div>

                <div class="w-full overflow-x-auto mt-4">
                    <table class="w-full">
                        <thead>
                            <th class="text-start py-3 px-4 border-b-2 border-slate-400">Date</th>
                            <th class="text-start py-3 px-4 border-b-2 border-slate-400">Task</th>
                            <th class="text-start py-3 px-4 border-b-2 border-slate-400">Time Range</th>
                            <th class="text-start py-3 px-4 border-b-2 border-slate-400">Working Hours</th>
                            <th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
                        </thead>
                        <tbody class="session-details-tbody">
                            @forelse ($studentAttendances as $satd)
                                @php
                                    $start_time = Carbon\Carbon::parse($satd->start_time);
                                    $end_time = Carbon\Carbon::parse($satd->end_time);
                                    $working_hours = $start_time->diffInHours($end_time);
                                @endphp

                                <tr class="@if($loop->index % 2 == 0) bg-slate-100 @endif">
                                    <td class="py-3 px-4">{{ Carbon\Carbon::parse($satd->attendance->attendance_date)->format('l, d M') }}</td>
                                    <td class="py-3 px-4">Lecturer</td>
                                    <td class="py-3 px-4">{{ $start_time->format('H:i') }} - {{ $end_time->format('H:i') }}</td>
                                    <td class="py-3 px-4">{{ $working_hours }}</td>
                                    <td class="py-3 px-4">
                                        <button type="button" class="del-row-btn bg-red text-white rounded-xl hover:bg-slate-600 py-2 px-4"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-slate-100 empty-table-placeholder">
                                    <td colspan="5" class="py-2 px-4 text-center">- No attendance data inputted for this student -</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
    @endforelse
</div>

<script>
            $(document).on('click', '.accordion-btn', function(){
				if($(this).parent().find('.accordion-area').is(':visible')){
					$(this).find('.accordion-icon').html('<i class="bi bi-chevron-down text-slate-600"></i>');
				}
				else {
					$(this).find('.accordion-icon').html('<i class="bi bi-chevron-up text-slate-600"></i>');
				}
				$(this).parent().find('.accordion-area').slideToggle();
			});

            $(document).on('click', '.remove-student-btn', function(){
				const nCard = $('.invoice-item-card').length;
				if(nCard == 1){
					alert('Cannot delete the card since the invoice needs minimum 1 item to be created!');

					return;
				}

                $(this).closest('.invoice-item-card').fadeOut(500, function(){
					$(this).remove();
                });
			});

            $(document).on('click', '.del-row-btn', function(){
                if(confirm('Are you sure want to remove this item from the data list?')){
                    $(this).closest('tr').remove();
                }
            });
        </script>--}}