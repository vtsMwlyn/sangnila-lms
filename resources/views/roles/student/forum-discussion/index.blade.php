@extends("layouts.main-student")

@section("title")
	<h1>Forum Discussion</h1>
@endsection

@section("content")
    <style>
        /* Triangle for received message (left side) */
        .message:not(.self-end)::before {
            content: "";
            position: absolute;
            top: 0; /* Moves it above the chat bubble */
            left: -10px; /* Adjust horizontal position */
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid white; /* Match chat bubble color */
        }

        /* Triangle for sent message (right side) */
        .self-end::before {
            content: "";
            position: absolute;
            top: 0;
            right: -10px; /* Adjust horizontal position */
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid #BCEADF; /* Match sent message bubble color */
        }

    </style>

    <div class="w-full flex rounded-xl overflow-hidden h-[75vh]">
        <div class="xl:w-1/6 bg-slate-50 hidden xl:block">
            <div class="p-4 font-bold">
                My Courses
            </div>

            {{-- For large devices --}}
            @foreach (Auth::user()->enrolled_courses as $course)
                <a href="{{ route('student.forum.index', ['course' => $course->id]) }}" class="px-4 py-3 block @if(request('course') != $course->id) hover:bg-slate-200 @endif" style="@if(request('course') == $course->id) border-right: solid #1DB9CF 4px; @endif">{{ $course->course_name }} - {{ ucwords($course->level) }}</a>
            @endforeach
        </div>

        <div class="w-full xl:w-5/6 relative">
            {{-- For mobiles and tablets --}}
            <div class="xl:hidden sticky top-0 flex flex-col items-start w-full first-letter:0 dropdown-container bg-slate-50 p-2 h-[10%] xl:h-[0px]" style="z-index: 8;">
                <button type="button" class="border-slate-400 py-2 px-4 rounded-2xl font-bold text-dark-blue w-full bg-white flex justify-between items-center dropdown-toggler" style="border-width: 3px;">Pick a course <img src="{{ asset('img/dropdown-arrow.svg') }}" class="w-5 h-5" alt="icon"></button>
                <div class="absolute bg-slate-100 top-16 w-full rounded-xl flex flex-col hidden overflow-hidden dropdown-menu">
                    @foreach(Auth::user()->enrolled_courses as $c)
                        <a href="{{ route('student.forum.index', ['course' => $course->id]) }}" class="w-full"><div class="w-full py-2 px-4 text-start hover:bg-slate-300">{{ $c->course_name }} - {{ ucwords($c->level) }}</div></a>
                    @endforeach
                </div>
            </div>

            @if(!request('course'))
                <div class="w-full h-full bg-slate-300 p-4 overflow-y-auto flex items-center justify-center">
                    - Please select a course -
                </div>
            @endif

            @if(request('course'))
                <div class="w-full bg-slate-300 p-4 overflow-y-auto flex flex-col align-items-start h-[75%] xl:h-[85%]" id="message-container"></div>
                <div class="absolute bottom-0 w-full p-4 hidden" id="extra-area" style="background-color: rgba(255, 255, 255, 0.6);"></div>
                <div class="absolute bottom-0 w-full p-4 flex justify-center" id="go-bottom-helper" style="display: none;">
                    <button type="button" class="py-1 px-2 rounded-lg bg-indigo-600 text-white">Go to latest messages</button>
                </div>
                <div class="absolute bottom-0 w-full p-4 flex justify-center z-10" id="message-status" style="display: none;">
                    <div class="py-2 px-4 rounded-lg text-white flex items-center gap-1" id="status-text"><div class="loader w-4 h-4 border-t-transparent border-white rounded-full animate-spin" style="border-width: 3px;"></div> Sending message...</div>
                </div>
                <form action="{{ route('student.forum.send', $course->id) }}" method="post" class="ajax-form w-full bg-slate-300 flex p-1.5 gap-2 items-center h-[15%]" id="send-message-input">
                    @csrf
                    <x-textarea rows="2" type="text" name="message" id="message" class="grow" placeholder="Enter message..." autofocus></x-textarea>
                    <x-button type="button" id="send-msg-btn"><i class="bi bi-send-fill"></i> Send</x-button>
                </form>
            @endif
        </div>

        <div id="error-container"></div>
        
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
            
            // First, instantly move to the bottom (prevents stopping in the middle)
            container.scrollTop(container.prop("scrollHeight"));

            // Then, smoothly animate any remaining distance
            container.animate({ scrollTop: container.prop("scrollHeight") }, 500);
        }

        function displayGoToLatestMessages(){
            const msgCont = $('#message-container');
            let scrollTop = msgCont.scrollTop();
            let scrollHeight = msgCont[0].scrollHeight;
            let containerHeight = msgCont.innerHeight();

            if (scrollTop + containerHeight < scrollHeight - 70) {
                $('#go-bottom-helper').fadeIn();
            }
            else {
                $('#go-bottom-helper').hide();
            }
        }


        let firstTimeLoad = true;

        $(document).ready(() => {
            // Function to load posts
            function loadMessages() {
                $.ajax({
                    url: `${baseUrl}/student/forum/${courseId}/retrieve`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(messages) {
                        let messageList = '';
                        const userGender = {{ Auth::user()->details->gender }};
                        const blankProfpic = userGender == 1? "{{ asset('img/tempblankprofpicmale.png') }}" : "{{ asset('img/tempblankprofpicfemale.png') }}";
                        const currUser = '{{ Auth::user()->id }}';

                        messages.forEach(msg => {
                            let userName = msg.user ? msg.user.full_name : "Unknown User";
                            let preprocessedMsg = msg.message.replace(/\n/g, '<br>').replace(
                                /(https?:\/\/[^\s]+|(?:www\.)?[\w-]+\.\w{2,}([^\s]*)?)/gi,
                                '<a href="$1" target="_blank" class="underline text-blue-600 font-semibold">$1</a>'
                            );

                            messageList += `<div class="message relative mb-5 rounded-xl w-fit max-w-[85%] md:max-w-[60%] p-4 shadow-md ${msg.user.id == currUser? 'self-end bg-pale-blue' : 'bg-white'}">
                                                <div class="flex gap-3 items-center">
                                                    <img src="${msg.user.details.profpic ? baseUrl + '/storage/' + msg.user.details.profpic : blankProfpic}" class="h-8 w-8 rounded-full object-cover object-center">
                                                    <div>
                                                        <h3 class="font-bold">${userName}</h3>
                                                        <i class="italic">${formatDate(msg.created_at)} GMT+7</i>
                                                    </div>
                                                </div>
                                                ${msg.attachment_path ? '<img src="' + baseUrl + '/storage/' + msg.attachment_path + '" class="max-h-[250px] mt-2">' : ''}
                                                <p class="mt-2 break-words">${preprocessedMsg}</p>
                                            </div>`;
                        });
                        $('#message-container').html(messageList);

                        if(firstTimeLoad){
                            setTimeout(scrollToBottom, 100);
                            firstTimeLoad = false;
                        }

                        displayGoToLatestMessages();

                        $('#message-status').find('#status-text').removeClass('bg-light-blue bg-red').addClass('bg-green-500').html(`<i class="bi bi-check-lg"></i> Message sent!`);
                        setTimeout(() => $('#message-status').fadeOut(), 1000);
                    },
                    error: function(xhr, status, error) {
                        $('#message-status').find('#status-text').removeClass('bg-light-blue bg-green-500').addClass('bg-red').html(`<i class="bi bi-exclamation-triangle-fill"></i> Failed to send the message`);
                        setTimeout(() => $('#message-status').fadeOut(), 1000);
                    },
                });
            }

            if(courseId != -1) {
                loadMessages();
                setInterval(loadMessages, 5000);
            }

            $('#extra-area').css('bottom', $('#send-message-input').outerHeight());
            $('#go-bottom-helper').css('bottom', $('#send-message-input').height());
            $('#message-status').css('bottom', $('#send-message-input').height());


            $('#go-bottom-helper').on('click', scrollToBottom);

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
                        if($('#message').val() && !isOnlyWhitespace($('#message').val())){
                            $('#send-message-input').submit();
                        }
                    }
                }
            });

            $('#send-msg-btn').on('click', function(){
                if($('#message').val() && !isOnlyWhitespace($('#message').val())){
                    $('#send-message-input').submit();
                }
            });

            // Handle form submission
            $('#send-message-input').submit(function(e) {
                e.preventDefault();

                $('#message-status').show().find('#status-text').removeClass('bg-red bg-green-500').addClass('bg-light-blue').html(`<div class="loader w-4 h-4 border-t-transparent border-white rounded-full animate-spin" style="border-width: 3px;"></div> Sending message...`);

                let msg = $('#message').val();

                $.ajax({
                    url: `${baseUrl}/student/forum/${courseId}/send`,
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,  // Required for FormData
                    contentType: false,  // Required for FormData
                    success: function(message) {
                        $('#send-message-input')[0].reset();
                        
                        loadMessages();
                        hideLoadingPopup();
                        scrollToBottom();

                        $('#extra-area').hide();
                        $('#pasted-img').remove();
                        $('#attachment').remove();
                        $('#go-bottom-helper').hide();
                    },
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

            $('#message-container').on('scroll', displayGoToLatestMessages);

        });

        $(document).on('paste', function(event) {
            let items = (event.originalEvent.clipboardData || event.clipboardData).items;

            $.each(items, function(index, item) {
                if (item.type.startsWith('image')) {
                    let blob = item.getAsFile();
                    let url = URL.createObjectURL(blob);

                    $('#pasted-img').remove();
                    $('#attachment').remove();

                    // Create a hidden file input element
                    let fileInput = $('<input>').attr({
                        type: 'file',
                        name: 'attachment',
                        id: 'attachment'
                    }).css('display', 'none');

                    // Create a new FileList object and assign the blob
                    let dataTransfer = new DataTransfer();
                    let file = new File([blob], "pasted-image.png", { type: item.type });
                    dataTransfer.items.add(file);
                    fileInput[0].files = dataTransfer.files;

                    // Append the hidden input to the form
                    $('#send-message-input').append(fileInput);

                    let img = $('<div>').addClass('relative flex items-center justify-between w-full').attr('id', 'pasted-img')
                        .append(
                            $('<img>').attr('src', url).css('max-height', '150px'))
                        .append(
                            $('<button>').attr('type', 'button').addClass('px-4 py-2').html(`<i class="bi bi-x-lg"></i>`).on('click', function(){
                                $(this).parent().remove();
                                $('#attachment').remove();
                                $('#extra-area').hide();
                            })
                        );

                    $('#extra-area').append(img).show();
                }
            });
        });

    </script>
@endsection
