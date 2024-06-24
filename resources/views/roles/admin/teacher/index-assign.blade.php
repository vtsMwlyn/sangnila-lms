@extends("layouts.main-admin")

@section("title")
	<h1>{{ $user->full_name }}</h1>
@endsection

@section("content")
	<x-page-title>{{ __("Assign Teacher to Course") }}</x-page-title>

	<div class="rounded-xl bg-indigo-200 p-5 mt-6">
		@if($courses->count())
			<form action="#" class="rounded-lg py-5 px-10 bg-blue-800" id="foomu">
				<div class="flex items-end gap-3 w-full" id="serekushon">
					<div class="w-5/6">
						<x-label for="visibility" :value="__('Select a course to assign')" style="color: white;"/>
						<x-select name="course_name" id="course_name" class="mt-1 w-full">
						</x-select>
					</div>

					<x-button class="bg-orange-500 w-1/6" type="button" id="botan">
						{{ __('Add') }}
					</x-button>
				</div>

				<div class="flex w-full" id="notifikeshon" style="display: none;">
					<x-label :value="__('Select a course to assign')" class="mb-5" style="color: white;"/>
					<div class="text-white italic font-semibold">- No more courses to assign -</div>
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

	<div class="rounded-xl py-5 px-10 mt-10 text-white bg-blue-900">List of Courses to Assign to this Teacher</div>
	<div class="rounded-xl bg-indigo-200 py-5 px-10 mt-3">
		<div class="flex w-full flex-wrap gap-x-10 overflow-x-auto" id="risuto">
			<div class="w-full text-center px-4 py-2 flex items-center justify-center font-semibold" id="emputii">
				- N/A -
			</div>
		</div>
		<form action="{{ route("admin.teacher.assign.store", $user->id) }}" method="post" class="mt-3" id="riiru">
			@csrf
			<x-button class="bg-orange-500" style="display: none;" id="assain">Assign Courses</x-button>
		</form>
	</div>

	<div class="rounded-xl py-5 px-10 mt-10 text-white bg-blue-900">Courses Already Assigned to this Teacher</div>
	<div class="rounded-xl bg-indigo-200 py-5 px-10 mt-3">
		<div class="flex gap-x-10 overflow-x-auto">
			@forelse ($user->teached_courses as $course)
				<div class="text-white border-4 border-white bg-yellow-500 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 200px; min-height: 50px; max-height: 50px;">
					{{ $course->course_name }}
				</div>
			@empty
				<div class="w-full text-center px-4 py-2 flex items-center justify-center font-semibold" style="min-width: 200px; min-height: 50px; max-height: 50px;">
					- N/A -
				</div>
			@endforelse
		</div>
	</div>

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
			const nyuuAitemu = $("<div>").addClass("text-white border-4 border-white bg-yellow-500 rounded-lg my-5 text-center px-4 py-2 flex items-center justify-between font-semibold").attr({"style": "min-width: 200px; max-width: 200px; min-height: 50px; max-height: 50px;"});
			const tekusu = $("<div>").text(beryu.course_name);
			const kyanseru = $("<button>").html("<i class='bi bi-x-circle'></i>");

			nyuuAitemu.append(tekusu, kyanseru);
			$("#risuto").append(nyuuAitemu);

			const nyuuHiden = $("<input>").attr({"type": "hidden", "name": "courses_list[]", "value": beryu.id});
			$("#riiru").append(nyuuHiden);

			alreadySelected.push(beryu.id);

			$(kyanseru).click(() => {
				nyuuAitemu.remove();
				nyuuHiden.remove();

				alreadySelected = alreadySelected.filter((value) => {
					return value != beryu.id;
				});

				document.dispatchEvent(ibento);
			});

			document.dispatchEvent(ibento);
		});
	</script>
@endsection
