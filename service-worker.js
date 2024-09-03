self.addEventListener('push', function(event) {
    const data = event.data.json();
    const options = {
        body: data.body,
        icon: data.icon,
        actions: data.actions,
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});
