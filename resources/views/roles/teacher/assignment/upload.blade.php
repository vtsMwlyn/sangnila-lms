<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>Sangnila Academy | LMS</title>
</head>

<body class="font-sans bg-cover h-screen bg-center bg-no-repeat"
    style="background-image: url({{ asset('img/background.png') }});">
    <x-navbar.teacher></x-navbar.teacher>

    <!-- Content Section -->
    <div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-blue-900 mb-4">Upload New Assignment</h1>
        <form action="{{ route('teacher.assignment.store', $course->id) }}" method="post" class="mx-auto">
            @csrf
            <!-- Assignment Title -->
            <div class="mb-4">
                <x-label for="title" :value="__('Assignment Title')" />
                <x-input id="title" class="block mt-1 w-full" type="text" name="title"
                    :value="old('title')" autofocus />
            </div>

            <!-- Assignment Description -->
            <div class="mb-4">
                <x-label for="desc" :value="__('Assignment Description')" />
                <x-input id="desc" class="block mt-1 w-full" type="text" name="desc"
                    :value="old('desc')" />
			</div>

			<!-- Assignment Link -->
            <div class="mb-4">
                <x-label for="link" :value="__('Assignment Link')" />
                <x-input id="link" class="block mt-1 w-full" type="text" name="link"
                    :value="old('link')" />
			</div>

			<!-- Assignment Deadline Date -->
			<div class="mb-4">
                <x-label for="deadline_date" :value="__('Assignment Deadline Date')" />
                <x-input id="deadline_date" class="block mt-1 w-full" type="date" name="deadline_date"
                    :value="old('deadline_date')" autofocus />
            </div>

			<!-- Assignment Deadline Time -->
			<div class="mb-4">
                <x-label for="deadline_time" :value="__('Assignment Deadline Time')" />
                <x-input id="deadline_time" class="block mt-1 w-full" type="time" name="deadline_time"
                    :value="old('deadline_time')" autofocus />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-button>
                    {{ __('Submit') }}
                </x-button>
            </div>
        </form>
    </div>
</body>

</html>
