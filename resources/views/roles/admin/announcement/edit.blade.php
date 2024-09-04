@extends("layouts.main-admin")

@section("title")
	<h1>Edit Announcement</h1>
@endsection

@section("breadcrumbs-extension")
	> <a href="{{ route("admin.announcement.index") }}" class="font-bold text-yellow-500">Announcements</a>
	> <span>{{ $announcement->title }}</span>
	> <span>Edit</span>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Edit Announcement") }}</x-page-title>

		@if(session()->has("systemFail"))
			<x-badge-danger badge_text="{{ session('systemFail') }}" class="mb-5"></x-badge-danger>
		@endif

		<form action="{{ route("admin.announcement.update", $announcement->id) }}" method="post" id="foomu" enctype="multipart/form-data">
			@csrf
			<!-- Announcement Title -->
			<div class="mb-4 flex gap-3 items-start">
				<x-boxed-label for="title" :value="__('New title')" />
				<div class="flex w-full flex-col items-stretch">
					<x-input id="title" class="block w-full" type="text" name="title" placeholder="New announcement title" :value="$announcement->title" autofocus />
				</div>
			</div>

			<!-- Announcement Image -->
			<div class="mb-4 flex gap-3 @error("image") items-start @else items-stretch @enderror">
				<x-boxed-label for="image" :value="__('New Image')" />
				<div class="flex w-full flex-col items-stretch">
					<x-input id="image" class="bg-white w-full block" type="file" name="image" placeholder="New announcement image" />
				</div>
			</div>

			<!-- Announcement Start Date -->
			<div class="mb-4 flex gap-3 items-start">
				<x-boxed-label for="announce_from" :value="__('New Start Date')" />
				<div class="flex w-full flex-col items-stretch">
					<x-input id="announce_from" class="block w-full" onfocus="this.type='date';" onblur="this.type='text';" name="announce_from" placeholder="New announcement start date" :value="old('announce_from', $announcement->announce_from)" autofocus />
				</div>
			</div>

			<!-- Announcement End Date -->
			<div class="mb-4 flex gap-3 items-start">
				<x-boxed-label for="announce_until" :value="__('New End Date')" />
				<div class="flex w-full flex-col items-stretch">
					<x-input id="announce_until" class="block w-full" onfocus="this.type='date';" onblur="this.type='text';" name="announce_until" placeholder="New announcement end date" :value="old('announce_until', $announcement->announce_until)" autofocus />
				</div>
			</div>

			<x-label :value="__('This is the current image of your announcement:')" style="color: white;" class="mt-8" id="img-preview-label"></x-label>
			<img id="img-preview" src="{{ Storage::url("app/public/" . $announcement->image_path) }}" class="w-1/2 mt-5">

			<x-label :value="__('Announce this announcement to:')" style="color: white;" class="mt-8"></x-label>

			@error("receiver")
				<p class="text-red-800 font-bold mt-3"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
			@enderror

			@php
				$cbvals = json_decode($announcement->sent_to);
			@endphp

			<div class="flex flex-wrap gap-3 p-3 mt-5 border-2 border-blue-800 rounded-xl bg-white @error("receiver") border p-5 border-red-700 @enderror">
				<div class="flex items-center gap-3 p-5 checkbox-container" style="width: 23%;">
					<input type="checkbox" id="checkbox1" name="checkbox1"
					class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" @if(old("checkbox1", $cbvals[0]) == "on") checked @endif>
					<label for="checkbox1">Admin</label>
				</div>
				<div class="flex items-center gap-3 p-5 checkbox-container" style="width: 23%;">
					<input type="checkbox" id="checkbox2" name="checkbox2"
					class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" @if(old("checkbox2", $cbvals[1]) == "on") checked @endif>
					<label for="checkbox2">Teacher</label>
				</div>
				<div class="flex items-center gap-3 p-5 checkbox-container" style="width: 23%;">
					<input type="checkbox" id="checkbox3" name="checkbox3"
					class="mr-2 form-checkbox h-5 w-5 border rounded border-gray-300 text-blue-500 bg-gray-300" @if(old("checkbox3", $cbvals[2]) == "on") checked @endif>
					<label for="checkbox3">Student</label>
				</div>
			</div>

			<x-label :value="__('Announcement content')" style="color: white;" class="mt-8"></x-label>

			@error("content")
				<p class="text-red-800 font-bold mt-3"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
			@enderror

			<div class="bg-white p-8 border-2 @error("content") border-red-700 @else border-blue-800 @endif rounded-xl mt-5">
				<input type="hidden" id="content" name="content">
                <trix-editor input="content">{!! old("content", $announcement->content) !!}</trix-editor>
			</div>

			<div class="flex items-stretch gap-3 justify-center mt-20 mb-3">
				<x-button class="bg-orange-500 w-full md:w-1/5">
					{{ __('Submit') }}
				</x-button>
				<x-cancel-button msg="The filled data will be discarded, are you sure want to cancel?" class="w-full md:w-1/5">
					Cancel
				</x-cancel-button>
			</div>
		</form>

	</x-section-container>

	<script>
		document.addEventListener("trix-file-accept", function(e){
			e.preventDefault();
		});

		$("#image").on("change", function(){
			const oFReader = new FileReader();
			oFReader.readAsDataURL(image.files[0]);

			oFReader.onload = function(oFEvent){
				$("#img-preview").attr("src", oFEvent.target.result);
			}

			$("#img-preview-label").text("This will be the new image of your announcement");
		});

		$("#foomu").on("submit", function(e){
			e.preventDefault();

			const allcb = $('input[type="checkbox"]');
			allcb.each((index, cb) => {
				let cbv = ($(cb).is(":checked"))? "on" : "off";
				$(this).append($("<input>").attr({"type": "hidden", "name": "receiver[]", "value": cbv}));
			});

			this.submit();
		});

		$("#announce_from").on({
			"focus": function(){
				this.showPicker();
			},
			"click": function(){
				this.showPicker();
			}
		});

		$("#announce_until").on({
			"focus": function(){
				this.showPicker();
			},
			"click": function(){
				this.showPicker();
			}
		});
	</script>
@endsection
