<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationController extends Controller
{
    public function saveSubscription(Request $request)
	{
		$validated = $request->validate([
			'endpoint' => 'required|string',
			'keys' => 'required|array',
		]);

		// Save or update the subscription in the database
		$subscription = PushSubscription::updateOrCreate(
			['endpoint' => $validated['endpoint']],
			['keys' => json_encode($validated['keys'])]
		);

		// $this->sendPushNotification();

		return response()->json(['success' => true]);
	}

	public function sendPushNotification2()
    {
        $subscriptions = PushSubscription::all();

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => 'mailto:your-email@example.com',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);

        $notificationPayload = [
            'title' => 'Notification Title',
            'body' => 'This is a test notification.',
            'icon' => asset('img/Sangnila_Arts.png'),
        ];

        foreach ($subscriptions as $subscription) {
            $subscriptionData = json_decode($subscription->keys, true);

            $webPush->sendOneNotification(
                new Subscription(
                    $subscription->endpoint,
                    $subscriptionData['p256dh'],
                    $subscriptionData['auth']
                ),
                json_encode($notificationPayload)
            );
        }

        $webPush->flush();

        return response()->json(['success' => true], 200);
	}

	public function sendPushNotification()
	{
		$subscriptions = PushSubscription::all();

		foreach ($subscriptions as $subscription) {
			$payload = json_encode([
				'title' => 'Notification Title',
				'body' => 'This is a test notification.',
				'icon' => asset('img/Sangnila_Arts.png'),
				'actions' => [
					// Optional actions
				],
			]);

			$endpoint = $subscription->endpoint;
			// $keys = json_decode($subscription->keys, true);

			// dd($keys);

			$auth = [
				'VAPID' => [
					'subject' => 'mailto:your-email@example.com',
					'publicKey' => env('VAPID_PUBLIC_KEY'),
					'privateKey' => env('VAPID_PRIVATE_KEY'),
				],
			];

			// dd($auth);

			// Prepare headers and payload for the push
			$headers = [
				'Content-Type: application/json',
				'Authorization: vapid t=' . $auth['VAPID']['publicKey'] . ', k=' . $auth['VAPID']['privateKey'],
			];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $endpoint);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);

			dd($response);

			curl_close($ch);
		}

		return response()->json(['success' => true], 200);
	}


	// public function sendPushNotification()
	// {
	// 	$client = new Client();
	// 	$subscriptions = PushSubscription::all();

	// 	foreach ($subscriptions as $subscription) {
	// 		$endpoint = $subscription->endpoint;
	// 		$keys = json_decode($subscription->keys, true);

	// 		$response = $client->post($endpoint, [
	// 			'json' => [
	// 				'title' => 'Notification Title',
	// 				'body' => 'Notification Body',
	// 				'icon' => asset("img/Sangnila_Arts.png"),
	// 				'actions' => [
	// 					['action' => 'open_url', 'title' => 'Open URL'],
	// 				],
	// 			],
	// 			'headers' => [
	// 				'Authorization' => 'Bearer ' . $keys['auth'],
	// 				'Content-Encoding' => 'aes128gcm',
	// 				'TTL' => '60',
	// 				'Content-Type' => 'application/json',
	// 			],
	// 		]);

	// 		if ($response->getStatusCode() !== 201) {
	// 			Log::error('Failed to send push notification: ' . $response->getBody()->getContents());
	// 		} else {
	// 			Log::info('Push notification sent successfully');
	// 		}
	// 	}
	// }
}
