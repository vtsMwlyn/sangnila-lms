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

<body class="font-sans bg-cover bg-no-repeat h-screen flex flex-col items-center bg-fixed" style="background-image: url({{ asset('img/background.png') }});">
    <x-navbar.guest></x-navbar.guest>

    <!-- Content Section -->
    <div class="container mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-blue-900 mb-4">{{ $course->course_name }}</h1>
        <p class="text-gray-700 mb-8">{{ $course->course_description }}</p>

        <h2 class="text-xl font-semibold mb-2">Course Materials:</h2>
        <div class="overflow-x-auto mb-5">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="border-b border-blue-900 bg-blue-200">
                        <th class="bg-blue-300 border-b border-blue-400 font-bold px-4 py-2">Material Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($materials as $index => $material)
                        <tr class="hover:bg-gray-100 border-b border-blue-900">
                            <td class="{{ $index === 0 ? 'bg-green-400' : 'bg-blue-100' }} border-b border-blue-300 px-4 py-2">
                                @if ($index === 0)
                                    <a href="{{ $material->link }}" class="{{ $index === 0 ? 'text-white font-bold hover:underline' : 'text-blue-600 hover:underline' }}">
                                        {{ $material->title }}
                                    </a>
                                @else
                                    <span class="text-gray-500">
                                        {{ $material->title }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="1" class="px-4 py-2 text-center border-t border-blue-900">No materials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
