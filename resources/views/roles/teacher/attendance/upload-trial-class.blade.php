@extends("layouts.main-teacher")

@section("title")
	<h1>Student Attendance</h1>
@endsection


@section("content")
	<x-section-container>
		<x-page-title>New Trial Class Attendance</x-page-title>
		<h1 class="font-bold text-lg text-blue mt-1">{{ $course->course_name }} - {{ ucwords($course->level) }}</h1>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route('teacher.attendance.trial-class.store', $course->id) }}" method="post">
			@csrf

            <div class="flex gap-5">
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="_attendance_date">Attendance Date<span class="text-red">*</span></x-label>
                    <x-input type="date" name="_attendance_date" id="_attendance_date" class="w-full date-input"/>
                    <p class="text-red font-bold mt-2 hidden" id="error-attendance-date"><i class="bi bi-exclamation-circle"></i> Please input trial class date.</p>
                </div>
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="_candidate_name">Candidate Name<span class="text-red">*</span></x-label>
                    <x-input type="text" name="_candidate_name" id="_candidate_name" class="w-full" placeholder="Enter candidate name"/>
                    <p class="text-red font-bold mt-2 hidden" id="error-candidate-name"><i class="bi bi-exclamation-circle"></i> Please input student candidate name.</p>
                </div>
            </div>

            <div class="flex gap-5">
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="_start_time">Start Time<span class="text-red">*</span></x-label>
                    <x-input type="time" name="_start_time" id="_start_time" class="w-full"/>
                    <p class="text-red font-bold mt-2 hidden" id="error-start-time"><i class="bi bi-exclamation-circle"></i> Please input start time.</p>
                </div>
                <div class="mt-3 w-full md:w-1/2">
                    <x-label class="mb-1" for="_end_time">End Time<span class="text-red">*</span></x-label>
                    <x-input type="time" name="_end_time" id="_end_time" class="w-full" placeholder="Enter candidate name"/>
                    <p class="text-red font-bold mt-2 hidden" id="error-end-time"><i class="bi bi-exclamation-circle"></i> Please input end time.</p>
                </div>
            </div>

            <div class="mt-4 flex flex-col w-full">
				<x-label for="_attendance_detail">Learning Details<span class="text-red">*</span></x-label>
				<x-textarea rows="4" id="_attendance_detail" class="w-full mt-1" type="text" name="_attendance_detail" placeholder="Enter candidate learning details"></x-textarea>
                <p class="text-red font-bold mt-2 hidden" id="error-attendance-detail"><i class="bi bi-exclamation-circle"></i> Please input learning details.</p>
			</div>

            <div class="flex w-full justify-end mt-8">
                <x-button class=" w-1/2 md:w-1/6" type="button" id="addBtn">Add Data</x-button>
            </div>

            <div class="flex mt-8">
                <h1 class="font-bold text-lg text-blue">Data to Add</h1>
            </div>
            <div class="overflow-x-auto mt-2">
                <table class="w-full">
                    <thead>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Attendance Date</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Candidate Name</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Learning Time</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Details</th>
                        <th class="text-start py-3 px-4 border-b-2 border-slate-400">Action</th>
                    </thead>
                    <tbody id="tableBody">
                        <tr class="bg-white" id="empty-placeholder">
                            <td colspan="5" class="p-4 text-center" id="empty-table-placeholder">- No data yet -</td>
                        </tr>
                    </tbody>
                </table>
            </div>

			<div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3" id="button-area">
				<x-cancel-button class="w-full md:w-40 xl:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button type="button" id="submit-btn" class="w-full md:w-40 xl:w-1/6">
					{{ __('Submit') }}
				</x-button>
			</div>
		</form>
	</x-section-container>

	<script>
        function formatDate(input) {
            const [year, month, day] = input.split('-');
            return `${day}/${month}/${year}`;
        }

        function restyle(){
            let i = 0;
            $('#tableBody').find('tr').each(function(){
                if(i % 2 == 1){
                    $(this).addClass('bg-white');
                }

                i++;
            });
        }

        $(document).ready(() => {
            $(document).on('click', '.remove-row-btn', function(){
                if(confirm('Are you sure want to remove this item?')){
                    $(this).closest('tr').remove();
                    restyle();
                }
            });

            $('#addBtn').on('click', function(){
                const inpDate = $('#_attendance_date');
                const inpName = $('#_candidate_name');
                const inpStartTime = $('#_start_time');
                const inpEndTime = $('#_end_time');
                const inpDetail = $('#_attendance_detail');

                const errDate = $('#error-attendance-date');
                const errName = $('#error-candidate-name');
                const errStartTime = $('#error-start-time');
                const errEndTime = $('#error-end-time');
                const errDetail = $('#error-attendance-detail');

                inpDate.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');
                inpName.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');
                inpStartTime.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');
                inpEndTime.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');
                inpDetail.removeClass('border-red focus:border-red-700 focus:ring-0').addClass('border-slate-400 focus:border-slate-600 focus:ring-0');

                errDate.hide();
                errName.hide();
                errStartTime.hide();
                errEndTime.hide();
                errDetail.hide();

                let invalid = false;

                if(!inpDate.val()){
                    inpDate.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
                    errDate.show();

                    invalid = true;
                }

                if(!inpName.val()){
                    inpName.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
                    errName.show();

                    invalid = true;
                }

                if(!inpStartTime.val()){
                    inpStartTime.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
                    errStartTime.show();

                    invalid = true;
                }

                if(!inpEndTime.val()){
                    inpEndTime.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
                    errEndTime.show();

                    invalid = true;
                }

                if(!inpDetail.val()){
                    inpDetail.removeClass('border-slate-400 focus:border-slate-600 focus:ring-0').addClass('border-red focus:border-red-700 focus:ring-0');
                    errDetail.show();

                    invalid = true;
                }

                if(invalid) return;

                $('#tableBody').find('#empty-table-placeholder').remove();

                $('#tableBody').append(
                    $('<tr>')
                        .append(
                            $('<td>').addClass('py-3 px-4').text(formatDate(inpDate.val()))
                        )
                        .append(
                            $('<td>').addClass('py-3 px-4').text(inpName.val())
                        )
                        .append(
                            $('<td>').addClass('py-3 px-4').text(`${inpStartTime.val()}-${inpEndTime.val()}`)
                        )
                        .append(
                            $('<td>').addClass('py-3 px-4').text(inpDetail.val())
                        )
                        .append(
                            $('<td>').addClass('py-3 px-4').append(
                                $('<div>')
                                    .append(
                                        $('<button>').html('<i class="bi bi-trash3"></i>').attr('type', 'button').addClass('text-center px-5 py-2 border border-transparent rounded-lg text-white bg-red hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:border-slate-900 focus:ring ring-slate-300 disabled:opacity-25 transition ease-in-out duration-150 remove-row-btn')
                                    )
                                    .append(
                                        $('<div>')
                                            .append(
                                                $('<input>').attr({'type': 'hidden', 'name': 'attendance_date[]', 'value': inpDate.val()})
                                            )
                                            .append(
                                                $('<input>').attr({'type': 'hidden', 'name': 'candidate_name[]', 'value': inpName.val()})
                                            )
                                            .append(
                                                $('<input>').attr({'type': 'hidden', 'name': 'start_time[]', 'value': inpStartTime.val()})
                                            )
                                            .append(
                                                $('<input>').attr({'type': 'hidden', 'name': 'end_time[]', 'value': inpEndTime.val()})
                                            )
                                            .append(
                                                $('<input>').attr({'type': 'hidden', 'name': 'attendance_detail[]', 'value': inpDetail.val()})
                                            )
                                    )
                            )
                        )
                );

                restyle();
            });

            $('#submit-btn').on('click', function(){
                if($('#tableBody').find('tr').length < 1 || $('#tableBody').find('#empty-table-placeholder').length == 1){
                    alert('You need to input minimum 1 entry to proceed.');
                }
                else {
                    $('form').submit();
                }
            });
        });
    </script>
@endsection
