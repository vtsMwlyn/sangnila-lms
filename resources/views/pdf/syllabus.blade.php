<html>
    <head>
        <title>{{ $course->course_name }} - {{ ucwords($course->level) }} Syllabus</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

			table th, td {
                border: solid 1px lightgray;
                padding: 5px 10px;
			}
		</style>
    </head>
    <body style="font-family: serif;">
        <!-- Header -->
		<div>
			<div style="width: 50%; display: inline-block;">
				<img src="{{ public_path('img/Sangnila_Arts.png') }}" width="100">
				<div class="font-bold">PT SANGNILA INTERAKTIF MEDIA</div>
				<div class="font-bold">DAN TEKNOLOGI</div>
			</div>
			<div style="width: 50%; display: flex; text-align: right; position: absolute; right: 0;">
				<div>Jl Pasir Kaliki No. 25-27 Ruko Paskal Hyper Square Blok B70</div>
				<div>+62 85693257411</div>
				<div><a href="mailto:admin@sangnilaindonesia.com">admin@sangnilaindonesia.com</a></div>
				<div><a href="https://www.sangnilaindonesia.com">www.sangnilaindonesia.com</a></div>
			</div>
		</div>
        <hr>

        <h2 style="margin-top: 40px;">{{ $course->course_name }} - {{ ucwords($course->level) }}</h2>

        <h3 style="margin-top: 40px;">Description</h3>
        <div>{{ $course->course_description }}</div>

        <h3 style="margin-top: 40px;">Learning Outcomes</h3>
        @forelse($course->learning_outcomes as $lo)
            <div>LO {{ $lo->number }}: {{ $lo->title }}</div>
        @empty
            N/A
        @endforelse
    </body>

    <body style="font-family: serif;">
        <h3>List of Topics and Activities</h3>
        <table>
            <thead>
                <tr>
                    <th style="background: lightgray; ">Session</th>
                    <th style="background: lightgray; ">Topic</th>
                    <th style="background: lightgray; ">Activity</th>
                    <th style="background: lightgray; ">Description</th>
                    <th style="background: lightgray; ">Learning Outcomes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->curriculum_topics as $topic)
                    @foreach($topic->curriculum_activities as $activity)
                        <tr>
                            <td>{{ $activity->session }}</td>
                            <td>{{ $topic->title }}</td>
                            <td>
                                @if($activity->link)
                                    <a href="{{ $activity->link }}">{{ $activity->title }}</a>
                                @else
                                    {{ $activity->title }}
                                @endif
                            </td>
                            <td>{{ $activity->desc }}</td>
                            <td>
                                @php
                                    $lolist = [];
                                    foreach ($activity->learning_outcomes as $leaout) {
                                        if (!in_array($leaout->number, $lolist)) {
                                            $lolist[] = $leaout->number;
                                        }
                                    }

                                    sort($lolist);
                                @endphp

                                @forelse($lolist as $los)
                                    <div class="w-full text-center">LO{{ $los }}@if(count($lolist) > 1 && $loop->index != count($lolist) - 1), @endif</div>
                                @empty
                                    <div class="w-full text-center">N/A</div>
                                @endforelse
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </body>
</html>