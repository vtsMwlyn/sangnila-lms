@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title class="mt-5">{{ __("Assign Teacher to Course") }}</x-page-title>

		<div class="rounded-xl py-5 px-10 mt-10 text-white bg-blue-950">Select a Course to Assign</div>
		<div class="mt-3 rounded-xl">
			@if($courses->count())
				<form action="#" id="foomu" class="mt-5">
					<div class="flex flex-col gap-3 w-full" id="serekushon">
						<div class="flex items-stretch gap-3">
							<x-boxed-label for="visibility" :value="__('Course Name')"/>
							<x-select name="course_name" id="course_name" class="w-full">
							</x-select>
						</div>

						<div class="flex gap-3 w-full justify-end">
							<x-button class="bg-orange-500 w-1/6 mt-5" type="button" id="botan">
								{{ __('Add to List') }}
							</x-button>
							<x-cancel-button class="w-1/6 mt-5" msg="The inputted data will be discarded, are you sure want to cancel?">
								{{ __('Back') }}
							</x-cancel-button>
						</div>
					</div>

					<div class="flex w-full" id="notifikeshon" style="display: none;">
						<div class="bg-white p-5 rounded-xl font-semibold text-center italic font-semibold">- No more courses to assign -</div>
						<div class="flex w-full justify-end">
							<x-cancel-button class="w-1/6 mt-5" msg="The inputted data will be discarded, are you sure want to cancel?'))">
								{{ __('Back') }}
							</x-cancel-button>
						</div>
					</div>

				</form>
			@else
				<div class="rounded-lg py-5 px-10 bg-blue-800">
					<p class="text-white italic">- No more courses to assign -</p>
					<x-button type="button" onclick="history.back()" class="bg-orange-500 mt-4">
						Return
					</x-button>
				</div>
			@endif
		</div>
	</x-section-container>

	<x-section-container class="mt-5">
		<div class="rounded-xl py-5 px-10 mt-10 flex items-center justify-between text-white bg-blue-950">
			<div>List of Courses to Assign to this Teacher</div>
			<form action="{{ route("admin.teacher.assign.store", $user->id) }}" method="post" id="riiru">
				@csrf
				<x-button class="bg-orange-500" style="display: none;" id="assain">Assign Courses</x-button>
			</form>
		</div>
		<div class="mt-3">
			<div class="flex w-full flex-wrap gap-x-10 overflow-x-auto" id="risuto">
				<div class="w-full bg-white text-center p-5 rounded-xl flex items-center justify-center font-semibold" id="emputii">
					- No courses added yet -
				</div>
			</div>
		</div>

		<div class="rounded-xl py-5 px-10 mt-10 text-white bg-blue-950">Courses Already Assigned to this Teacher</div>
		<div class="mt-3">
			<div class="flex gap-x-10 overflow-x-auto">
				@forelse ($user->teached_courses as $course)
					<div class="text-white border-2 border-white bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 200px; min-height: 100px; max-height: 100px;">
						{{ $course->course_name }}
					</div>
				@empty
					<div class="w-full bg-white text-center p-5 rounded-xl flex items-center justify-center font-semibold">
						- No courses assigned yet -
					</div>
				@endforelse
			</div>
		</div>
	</x-section-container>

	<script>
		const courseList = @json($courses);
		let alreadySelected = [];

		const ibento = new Event("data_added");
		document.addEventListener("data_added", () => {
			$("#course_name").html("");

			courseList.forEach(element => {
				let found = false;
				alreadySelected.forEach(ids => {
					if(ids == element.id){
						found = true;
						return;
					}
				});

				if(!found){
					const newOption = $("<option>").attr({"value": JSON.stringify(element)}).text(element.course_name);
					$("#course_name").append(newOption);
				}
			});

			if(alreadySelected.length > 0){
				$("#emputii").attr({"style": "display: none"});
				$("#assain").attr({"style": "display: block"});
			} else {
				$("#emputii").attr({"style": "display: block"});
				$("#assain").attr({"style": "display: none"});
			}

			if(alreadySelected.length != courseList.length){
				$("#serekushon").attr({"style": "display: flex"});
				$("#notifikeshon").attr({"style": "display: none"});
			} else {
				$("#serekushon").attr({"style": "display: none"});
				$("#notifikeshon").attr({"style": "display: block"});
			}
		});

		$(document).ready(() => {
			courseList.forEach(element => {
				const newOption = $("<option>").attr({"value": JSON.stringify(element)}).text(element.course_name);
				$("#course_name").append(newOption);
			});
		});

		$("#botan").click(() => {
			const beryu = JSON.parse($("#course_name").val());
			const nyuuAitemu = $("<div>").addClass("text-white border-2 border-white bg-blue-900 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-between font-semibold").attr({"style": "min-width: 200px; max-width: 200px; min-height: 100px; max-height: 100px;"});
			const tekusu = $("<div>").text(beryu.course_name);
			const kyanseru = $("<button>").html("<i class='bi bi-x-circle'></i>");

			nyuuAitemu.append(tekusu, kyanseru);
			$("#risuto").append(nyuuAitemu);

			const nyuuHiden = $("<input>").attr({"type": "hidden", "name": "courses_list[]", "value": beryu.id});
			$("#riiru").append(nyuuHiden);

			alreadySelected.push(beryu.id);

			$(kyanseru).click(() => {
				if(confirm("Are you sure want to remove this item from the list?")){
					nyuuAitemu.remove();
					nyuuHiden.remove();

					alreadySelected = alreadySelected.filter((value) => {
						return value != beryu.id;
					});

					document.dispatchEvent(ibento);
				}
			});

			document.dispatchEvent(ibento);
		});
	</script>
@endsection
