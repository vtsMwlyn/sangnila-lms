// Register service worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sangnila-lms/service-worker.js')
		.then(function(registration) {
			console.log('Service Worker registered with scope:', registration.scope);
			return Notification.requestPermission()
				.then(function(permission) {
					if (permission === 'granted') {
						console.log('Notification permission granted.');
						return registration; // Return the registration object to the next then
					} else {
						throw new Error('Notification permission denied.');
					}
				});
		})
		.then(function(registration) {
            console.log('Service Worker is ready.');
            return registration.pushManager.getSubscription()
                .then(function(subscription) {
                    // Now we have the registration and subscription
                    if (subscription) {
                        console.log('Already subscribed:', subscription);
                        return subscription;
                    }
                    return registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array('BCdWMd593lp05hRwA5Rura6CkxyLtIk1aLl_f2q0VCR_nj8sz4UeOh4IW58SPG4U3SSwlz3TCDJE3ESIakW4OZk')
                    });
                });
        })
		.then(function(subscription) {
			console.log('Subscribed:', subscription);
			return fetch("/sangnila-lms/public/save-subscription", {
				method: 'post',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
				},
				body: JSON.stringify(subscription)
			})
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not ok.');
				}
				return response.json();
			})
			.then(data => {
				console.log('Success:', data);
			})
			.catch(error => {
				console.error('Error:', error);
			});
		})
	.catch(function(error) {
		console.error('Error during setup:', error);
	});

}

// Helper function to convert base64 to Uint8Array
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
