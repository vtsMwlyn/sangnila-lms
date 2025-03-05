@extends("layouts.main-guest")

@section('title')
    <h1>Privacy Policy</h1>
@endsection

@section("content")
	<x-section-container>
        <x-page-title>Privacy Policy</x-page-title>
        <div class="w-full bg-slate-400 mt-2 mb-3" style="height: 2px;"></div>

        <div>
            <p>Welcome to Sangnila LMS, a learning managemen system made for admins, lecturers, and students in Sangnila Arts Academy. We appreciate your privacy so that's why this document is made to help you understand how we collect, use, and protect your information when you use our services that integrate with Google Calendar and Google Tasks.</p>
        </div>

        <div class="mt-5">
            <h1 class="font-bold text-dark-blue">1. Information We Collect</h1>
            <p class="mt-2">When you sync the calendar provided in dashboard page with your Google account, we will collect the following informations from the selected Google account:</p>
            <ul class="list-disc list-inside">
                <li>Email and/or name of the account</li>
                <li>Calendar events from main calendar of the account</li>
                <li>Tasks from the account</li>
            </ul>
            <p class="mt-2">The dashboard calendar syncing feature is currently available for lecturer accounts only.</p>
        </div>

        <div class="mt-5">
            <h1 class="font-bold text-dark-blue">2. How We Use the Information</h1>
            <p class="mt-2">When you syncing the calendar provided in dashboard page with your Google account, we will use the collected data for these purposes:</p>
            <ul class="list-disc list-inside">
                <li>To display the calendar events and tasks in the dashboard calendar</li>
                <li>To show the selected account name and/or email also in the dashboard calendar</li>
            </ul>
            <p class="mt-2">The current version of the web application has no feature to modify (add, edit, or delete) the calendar events or tasks. Only the dashboard will use the data.</p>
        </div>

        <div class="mt-5">
            <h1 class="font-bold text-dark-blue">3. Data Storage and Security</h1>
            <p class="mt-2">When you syncing the calendar provided in dashboard page with your Google account, we will use the collected data for these purposes:</p>
            <ul class="list-disc list-inside">
                <li>Your Google calendar events and tasks data are not stored on our servers. We fetch and display them dynamically.</li>
                <li>OAuth tokens are encrypted securely stored in our database to maintain ongoing access.</li>
                <li>We use industry-standard security practices to protect your data from unauthorized access.</li>
            </ul>
            <p class="mt-2">Our application integrates with Google Calendar API and Google Tasks API to access your events and tasks. OAuth tokens are securely stored and refreshed periodically to maintain uninterrupted access. These tokens will be automatically revoked when you choose to unsynchronize your Google account.</p>
        </div>

        <div class="mt-5">
            <h1 class="font-bold text-dark-blue">4. Data Sharing and Third-Party Access</h1>
            <p class="mt-2">We do not sell, trade, or share your Google calendar events or tasks data with third parties. Your data remains private and is only accessed when needed for synchronization with the dashboard calendar.</p>
        </div>

        <div class="mt-5">
            <h1 class="font-bold text-dark-blue">5. User Rights and Controls</h1>
            <p class="mt-2">You can disconnect Google calendar events and tasks at any time by revoking access in your Google account settings or by clicking 'Unsync from Google' button directly at the dashboard page.</p>
        </div>

        <div class="my-4">
            <h1 class="font-bold text-dark-blue">6. Our Contact Information</h1>
            <p class="mt-2">If you have any concern about our privacy policy, you may contact us by sending email to <a href="mailto:vannestheo.sangnila@gmail.com" class="text-blue-600 underline font-semibold">vannestheo.sangnila@gmail.com</a>. We may update this privacy policy from time to time. Any changes will be communicated through our website.</p>
        </div>
    </x-section-container>
@endsection
