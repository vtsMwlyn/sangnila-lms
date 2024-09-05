<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event as GoogleEvent;

class GoogleCalendarService
{
    public function createGoogleClient()
    {
        $client = new GoogleClient();
		$path = base_path(config('google-calendar.credentials_path'));
		if (!file_exists($path)) {
			throw new \Exception("File does not exist: $path");
		}
        $client->setAuthConfig($path);
        $client->addScope(GoogleCalendar::CALENDAR);

        return $client;
    }

    public function addEvent($eventData)
    {
        $client = $this->createGoogleClient();
        $service = new GoogleCalendar($client);

        $calendarId = config('google-calendar.calendar_id');

        $event = new GoogleEvent($eventData);
        $event = $service->events->insert($calendarId, $event);

        return $event;
    }
}
