<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event as GoogleEvent;

class GoogleCalendarService
{
    protected $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setAuthConfig(config('app.google_credentials_path'));
        $this->client->addScope(GoogleCalendar::CALENDAR_READONLY);
        $this->client->setRedirectUri(config('app.google_redirect_uri'));
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function fetchAccessTokenWithAuthCode($code)
    {
        return $this->client->fetchAccessTokenWithAuthCode($code);
    }

    public function setAccessToken($token)
    {
        $this->client->setAccessToken($token);
    }

    public function listEvents($calendarId = 'primary')
    {
        $service = new GoogleCalendar($this->client);
        return $service->events->listEvents($calendarId)->getItems();
    }

    // public function createGoogleClient()
    // {
    //     $client = new GoogleClient();
	// 	$path = base_path(config('google-calendar.credentials_path'));
	// 	if (!file_exists($path)) {
	// 		throw new \Exception("File does not exist: $path");
	// 	}
    //     $client->setAuthConfig($path);
    //     $client->addScope(GoogleCalendar::CALENDAR);

    //     return $client;
    // }

    // public function addEvent($eventData)
    // {
    //     $client = $this->createGoogleClient();
    //     $service = new GoogleCalendar($client);

    //     $calendarId = config('google-calendar.calendar_id');

    //     $event = new GoogleEvent($eventData);
    //     $event = $service->events->insert($calendarId, $event);

    //     return $event;
    // }
}
