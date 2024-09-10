<div class="text-white grow py-5 text-lg">
	@php $link = ""; @endphp

	<a href="{{ route('home') }}" class="font-bold text-yellow-500">Home</a>

	<!-- Admins -->
	@if(Auth::user()->role_id == 1)
		@if(Request::is("*course*"))
			> <a href="{{ route('admin.course.index') }}" class="font-bold text-yellow-500">All Courses</a>
		@elseif(Request::is("*teacher*"))
			> <a href="{{ route('admin.teacher.index') }}" class="font-bold text-yellow-500">All Teachers</a>
		@elseif(Request::is("*student*"))
			> <a href="{{ route('admin.student.index') }}" class="font-bold text-yellow-500">All Students</a>
		@elseif(Request::is("*account*"))
			> <a href="{{ route('admin.account.index') }}" class="font-bold text-yellow-500">All Accounts</a>
		@endif

	@elseif(Auth::user()->role_id == 2)
		@if(Request::is("*my-course*") || Request::is("*topic*") || Request::is("*material*"))
			> <a href="{{ route('teacher.mycourse.index') }}" class="font-bold text-yellow-500">My Courses</a>
		@elseif(Request::is("*student*"))
			> <a href="{{ route('teacher.student.select-course') }}" class="font-bold text-yellow-500">Material Access</a>
		@elseif(Request::is("*assignment*"))
			> <a href="{{ route('teacher.assignment.index') }}" class="font-bold text-yellow-500">Assignment</a>
		@elseif(Request::is("*attendance*"))
			> <a href="{{ route('teacher.attendance.index') }}" class="font-bold text-yellow-500">Attendance</a>
		@endif

	@elseif(Auth::user()->role_id == 3)
		@if(Request::is("*my-course*"))
			> <a href="{{ route('student.mycourse.index') }}" class="font-bold text-yellow-500">My Courses</a>
		@elseif(Request::is("*assignment*"))
			> <a href="{{ route('student.assignment.index') }}" class="font-bold text-yellow-500">Assignment</a>
		@elseif(Request::is("*attendance*"))
			> <a href="{{ route('student.attendance.index') }}" class="font-bold text-yellow-500">Attendance</a>
		@endif

	@endif

	{{ $slot }}

</div>
