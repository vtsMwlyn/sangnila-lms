<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendarService;

class CalendarController extends Controller
{
    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function createEvent()
    {
        $eventData = [
			'summary' => 'Testus 2',  // Title of the event
			'description' => 'Discuss project updates and next steps.',  // Description of the event
			'start' => [
				'dateTime' => '2024-09-06T09:00:00+07:00',  // Start date and time in ISO 8601 format
				'timeZone' => 'Asia/Jakarta',  // Time zone for Indonesia
			],
			'end' => [
				'dateTime' => '2024-09-06T10:00:00+07:00',  // End date and time in ISO 8601 format
				'timeZone' => 'Asia/Jakarta',  // Time zone for Indonesia
			],
			'colorId' => 9
		];


        $event = $this->calendarService->addEvent($eventData);

        return back();
    }
}

