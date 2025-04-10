@extends("layouts.main-admin")

@section("title")
	<h1>Manage Announcement</h1>
@endsection

@section("content")
	<x-section-container>
		<x-page-title>{{ __("Upload New Announcement") }}</x-page-title>
		<div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

		@if(session()->has("success"))
			<x-badge-success badge_text="{{ session('success') }}"></x-badge-success>
		@elseif(session()->has("warning"))
			<x-badge-warning badge_text="{{ session('warning') }}"></x-badge-warning>
		@elseif(session()->has("danger"))
			<x-badge-danger badge_text="{{ session('danger') }}"></x-badge-danger>
		@endif

		<form action="{{ route("admin.announcement.store") }}" method="post" id="foomu" enctype="multipart/form-data" class="mt-3">
			@csrf

			<div class="flex w-full gap-5">
				{{-- Announcement Title --}}
				<div class="flex flex-col w-1/2">
					<x-label for="title">Announcement Title<span class="text-red">*</span></x-label>
					<x-input id="title" class="block w-full" type="text" name="title" placeholder="New announcement title" :value="old('title')" autofocus />
				</div>

				{{-- Announcement Image --}}
				<div class="flex flex-col w-1/2">
					<x-label for="image">Announcement Image</x-label>
					<x-input id="image" class="bg-white w-full block" type="file" name="image" accept="image/*" placeholder="New announcement image" />
				</div>
			</div>

			<div class="flex w-full gap-5 mt-3">
				{{-- Announcement Start Date --}}
				<div class="flex flex-col w-1/2">
					<x-label for="announce_from">Announce From<span class="text-red">*</span></x-label>
					<x-input id="announce_from" class="block w-full" onfocus="this.type='date';" onblur="this.type='text';" name="announce_from" placeholder="New announcement start date" :value="old('announce_from')" autofocus />
				</div>

				{{-- Announcement End Date --}}
				<div class="flex flex-col w-1/2">
					<x-label for="announce_until">Announce Until<span class="text-red">*</span></x-label>
					<x-input id="announce_until" class="block w-full" onfocus="this.type='date';" onblur="this.type='text';" name="announce_until" placeholder="New announcement end date" :value="old('announce_until')" autofocus />
				</div>
			</div>

			<x-label :value="__('This is the image of your announcement:')" style="display: none;" class="mt-8" id="img-preview-label"></x-label>
			<img id="img-preview" class="w-1/2 mt-5">

			<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Announce to<span class="text-red">*</span></h2>
			<div class="w-full bg-slate-400 " style="height: 2px;"></div>

			<div class="flex flex-wrap gap-3 mt-5 rounded-xl @error("receiver") border-2 p-5 border-red @enderror">
				<div class="flex items-center gap-3 p-5 checkbox-container" style="width: 23%;">
					<input type="checkbox" id="checkbox1" name="checkbox1"
					class="mr-2 h-5 w-5" @if(old("checkbox1") == "on") checked @endif>
					<label for="checkbox1">Admin</label>
				</div>
				<div class="flex items-center gap-3 p-5 checkbox-container" style="width: 23%;">
					<input type="checkbox" id="checkbox2" name="checkbox2"
					class="mr-2 h-5 w-5" @if(old("checkbox2") == "on") checked @endif>
					<label for="checkbox2">Teacher</label>
				</div>
				<div class="flex items-center gap-3 p-5 checkbox-container" style="width: 23%;">
					<input type="checkbox" id="checkbox3" name="checkbox3"
					class="mr-2 h-5 w-5" @if(old("checkbox3") == "on") checked @endif>
					<label for="checkbox3">Student</label>
				</div>
			</div>

			@error("receiver")
				<p class="text-red font-bold mt-3"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
			@enderror

			<div class="w-full bg-slate-400 mt-8" style="height: 2px;"></div>
			<h2 class="my-4 font-extrabold text-xl text-dark-blue">Announcement Content<span class="text-red">*</span></h2>
			<div class="w-full bg-slate-400 " style="height: 2px;"></div>

			<div class="bg-white p-6 border-2 @error("content") border-red @else border-slate-400 @endif rounded-xl mt-5">
				<input type="hidden" id="content" name="content">
                <trix-editor input="content" style="height: 300px;" class="overflow-y-auto">{!! old("content") !!}</trix-editor>
			</div>

			@error("content")
				<p class="text-red font-bold mt-3"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
			@enderror

			<div class="flex items-stretch gap-3 justify-end mt-10 mb-3">
				<x-cancel-button class="w-full md:w-1/4">
					Cancel
				</x-cancel-button>
				<x-button class=" w-full md:w-1/4">
					{{ __('Submit') }}
				</x-button>
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

			$("#img-preview-label").show();
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
