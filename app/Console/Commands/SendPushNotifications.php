<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;
use App\Models\PushSubscription;
use Minishlink\WebPush\Subscription;

class SendPushNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send push notifications to all subscribers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
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
			$keys = json_decode($subscription->keys, true);

			$auth = [
				'VAPID' => [
					'subject' => 'mailto:your-email@example.com',
					'publicKey' => 'BJOxG7nA6j0HVvBrejKANMllIVCECXLj2py56Nz1kRKAaGhDYgqX-EgmYxw0Srm5xrZ81oNpj2lc1rgQdiP1zbo',
					'privateKey' => 'Z1Z3PHPhC8MDbia927-P1kFCDz5JYsYt_kkN9SqtXVQ',
				],
			];

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

			curl_close($ch);
		}


        $this->info('Push notifications sent successfully!');
    }
}
