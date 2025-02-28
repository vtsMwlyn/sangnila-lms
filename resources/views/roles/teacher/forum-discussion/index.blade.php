@extends("layouts.main-teacher")

@section("title")
	<h1>Forum Discussion</h1>
@endsection

@section("content")
    <div class="w-full flex" style="height: 75vh;">
        <div class="w-1/6 bg-slate-50">
            <div class="p-4 font-bold">
                My Courses
            </div>
            @foreach (Auth::user()->teached_courses as $course)
                <a href="{{ route('teacher.forum.index', ['course' => $course->id]) }}" class="px-4 py-3 block @if(request('course') == $course->id) bg-dark-blue text-white @else hover:bg-slate-200 @endif">{{ $course->course_name }} - {{ ucwords($course->level) }}</a>
            @endforeach
        </div>
        <div class="w-5/6">
            <div class="w-full bg-slate-300 p-4 overflow-y-auto" style="height: 85%;" id="message-container"></div>
            <form action="{{ route('teacher.forum.send', $course->id) }}" method="post" class="ajax-form w-full flex p-1.5 gap-3 items-center" style="height: 15%; background-color: #FEFEFEB2;" id="send-message-input">
                @csrf
                <x-textarea rows="2" type="text" name="message" id="message" class="grow" placeholder="Enter message..."></x-textarea>
            </form>
        </div>
    </div>

    <script>
        const baseUrl = '{{ url('/') }}';
        const courseId = '{{ $course_id }}';

        function isOnlyWhitespace(input) {
            return input.trim().length === 0;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-GB', {
                weekday: 'short',
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            }); // Adjust formatting
        }

        function scrollToBottom() {
            let container = $('#message-container');
            container.scrollTop(container[0].scrollHeight);
        }

        $(document).ready(() => {
            // Function to load posts
            function loadMessages() {
                $.ajax({
                    url: `${baseUrl}/teacher/forum/${courseId}/retrieve`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(messages) {
                        let messageList = '';
                        const blankProfpic = "{{ asset('img/tempblankprofpic.png') }}";

                        messages.forEach(msg => {
                            let userName = msg.user ? msg.user.full_name : "Unknown User"; // Prevent error
                            messageList += `<div class="message mb-5 rounded-xl bg-white w-fit p-4 shadow-md">
                                                <div class="flex gap-3">
                                                    <img src="${msg.user.details.profpic ? baseUrl + '/storage/' + msg.user.details.profpic : blankProfpic}" class="h-12 w-12 rounded-full">
                                                    <div>
                                                        <h3 class="font-bold">${userName}</h3>
                                                        <i class="italic">${formatDate(msg.created_at)} GMT+7</i>
                                                    </div>
                                                </div>
                                                <p class="mt-2">${msg.message.replace(/\n/g, '<br>')}</p>
                                            </div>`;
                        });
                        $('#message-container').html(messageList);
                        scrollToBottom();
                    }
                });
            }

            if(courseId != -1) {
                loadMessages();
                setInterval(loadMessages, 5000);
            }

            // Shift enter and enter mechanism
            $('#message').on('keydown', function(event){
                if (event.key === "Enter") {
                    if (event.shiftKey) {
                        event.preventDefault(); // Prevent form submission

                        let cursorPos = this.selectionStart;  // Get current cursor position
                        let text = $(this).val();

                        // Insert a newline at the cursor position
                        $(this).val(text.substring(0, cursorPos) + "\n" + text.substring(cursorPos));

                        // Move the cursor to the new line
                        this.selectionStart = this.selectionEnd = cursorPos + 1;

                        // Scroll the textarea down so the new line is visible
                        $(this).scrollTop(this.scrollHeight);
                    } else {
                        $('#send-message-input').submit();
                    }
                }
            });

            // Handle form submission
            $('#send-message-input').submit(function(e) {
                e.preventDefault();
                let msg = $('#message').val();

                $.ajax({
                    url: `${baseUrl}/teacher/forum/${courseId}/send`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        message: msg,
                    },
                    success: function(message) {
                        $('#send-message-input')[0].reset();
                        loadMessages();
                        hideLoadingPopup()
                    }
                });
            });

            $('#message').on('input', function(){
                if(!$(this).val() || isOnlyWhitespace($(this).val())){
                    $('#send-msg-btn').prop('disabled', true);
                }
                else {
                    $('#send-msg-btn').prop('disabled', false);
                }
            });
        });
    </script>
@endsection
