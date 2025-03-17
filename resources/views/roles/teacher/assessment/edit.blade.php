@extends('layouts.main-teacher')

@section('title')
    <h1>Manage Students</h1>
@endsection

@section('content')
    <x-section-container>
        <x-back-button href="{{ route('teacher.student.show', [$assessment->student->id, $assessment->course->id]) }}"></x-back-button>
		<x-page-title>Edit Assessment</x-page-title>
        <span class="text-xl font-semibold text-blue-900 mt-2">{{ ucwords($assessment->student->full_name) }} in {{ $assessment->course->course_name }} - {{ ucwords($assessment->course->level) }}</span>
        <div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

        <form action="{{ route('teacher.student.update.assessment', $assessment->id) }}" method="post">
            @csrf

            {{-- Performance --}}
            <h3 class="text-blue-800 font-bold text-lg">Performance</h3>

			<div class="mt-2 flex flex-col w-full">
				<x-label for="performance_score">Score</x-label>
				<x-select id="performance_score" class="w-full mt-1" type="text" name="performance_score">
                    <option value="excellent" @if(old('performance_score', $assessment->performance_score) == 'excellent') selected @endif>Excellent</option>
                    <option value="very good" @if(old('performance_score', $assessment->performance_score) == 'very good') selected @endif>Very Good</option>
                    <option value="good" @if(old('performance_score', $assessment->performance_score) == 'good') selected @endif>Good</option>
                    <option value="rooms for growth" @if(old('performance_score', $assessment->performance_score) == 'rooms for growth') selected @endif>Rooms For Growth</option>
                </x-select>
			</div>

			<div class="mt-4 flex flex-col w-full">
				<x-label for="performance_description">Evaluation</x-label>
				<x-textarea rows="4" id="performance_description" class="w-full mt-1" type="text" name="performance_description" placeholder="Enter student's performance evaluation">{!! old('performance_description', $assessment->performance_description) !!}</x-textarea>
			</div>

            {{-- Technical Skill --}}
            <h3 class="text-blue-800 font-bold text-lg mt-8">Technical Skill</h3>

			<div class="mt-2 flex flex-col w-full">
				<x-label for="technical_skill_score">Score</x-label>
				<x-select id="technical_skill_score" class="w-full mt-1" type="text" name="technical_skill_score">
                    <option value="excellent" @if(old('technical_skill_score', $assessment->technical_skill_score) == 'excellent') selected @endif>Excellent</option>
                    <option value="very good" @if(old('technical_skill_score', $assessment->technical_skill_score) == 'very good') selected @endif>Very Good</option>
                    <option value="good" @if(old('technical_skill_score', $assessment->technical_skill_score) == 'good') selected @endif>Good</option>
                    <option value="rooms for growth" @if(old('technical_skill_score', $assessment->technical_skill_score) == 'rooms for growth') selected @endif>Rooms For Growth</option>
                </x-select>
			</div>

			<div class="mt-4 flex flex-col w-full">
				<x-label for="technical_skill_description">Evaluation</x-label>
				<x-textarea rows="4" id="technical_skill_description" class="w-full mt-1" type="text" name="technical_skill_description" placeholder="Enter student's technical skill evaluation">{!! old('technical_skill_description', $assessment->technical_skill_description) !!}</x-textarea>
			</div>

            {{-- Aesthetic Skill --}}
            <h3 class="text-blue-800 font-bold text-lg mt-8">Aesthetic Skill</h3>

			<div class="mt-2 flex flex-col w-full">
				<x-label for="aesthetical_skill_score">Score</x-label>
				<x-select id="aesthetical_skill_score" class="w-full mt-1" type="text" name="aesthetical_skill_score">
                    <option value="excellent" @if(old('aesthetical_skill_score', $assessment->aesthetical_skill_score) == 'excellent') selected @endif>Excellent</option>
                    <option value="very good" @if(old('aesthetical_skill_score', $assessment->aesthetical_skill_score) == 'very good') selected @endif>Very Good</option>
                    <option value="good" @if(old('aesthetical_skill_score', $assessment->aesthetical_skill_score) == 'good') selected @endif>Good</option>
                    <option value="rooms for growth" @if(old('aesthetical_skill_score', $assessment->aesthetical_skill_score) == 'rooms for growth') selected @endif>Rooms For Growth</option>
                </x-select>
			</div>

			<div class="mt-4 flex flex-col w-full">
				<x-label for="aesthetical_skill_description">Evaluation</x-label>
				<x-textarea rows="4" id="aesthetical_skill_description" class="w-full mt-1" type="text" name="aesthetical_skill_description" placeholder="Enter student's aesthetical skill evaluation">{!! old('aesthetical_skill_description', $assessment->aesthetical_skill_description) !!}</x-textarea>
			</div>

            {{-- Overall --}}
            <h3 class="text-blue-800 font-bold text-lg mt-8">Overall</h3>

			<div class="mt-2 flex flex-col w-full">
				<x-label for="overall_score">Score</x-label>
				<x-select id="overall_score" class="w-full mt-1" type="text" name="overall_score">
                    <option value="excellent" @if(old('overall_score', $assessment->overall_score) == 'excellent') selected @endif>Excellent</option>
                    <option value="very good" @if(old('overall_score', $assessment->overall_score) == 'very good') selected @endif>Very Good</option>
                    <option value="good" @if(old('overall_score', $assessment->overall_score) == 'good') selected @endif>Good</option>
                    <option value="rooms for growth" @if(old('overall_score', $assessment->overall_score) == 'rooms for growth') selected @endif>Rooms For Growth</option>
                </x-select>
			</div>

			<div class="mt-4 flex flex-col w-full">
				<x-label for="overall_description">Comments</x-label>
				<x-textarea rows="4" id="overall_description" class="w-full mt-1" type="text" name="overall_description" placeholder="Enter student's overall comments">{!! old('overall_description', $assessment->overall_description) !!}</x-textarea>
			</div>

            <div class="flex items-stretch gap-2 justify-end w-full mt-10 mb-3">
				<x-cancel-button class="w-full md:w-40 lg:w-1/6">
					Cancel
				</x-cancel-button>
				<x-button class="w-full md:w-40 lg:w-1/6">
					Submit
				</x-button>
			</div>
        </form>
    </x-section-container>
@endsection