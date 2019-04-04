self.addEventListener('install', function (event) {
    self.skipWaiting();
});
self.addEventListener('activate', function (event) {});

self.addEventListener('push', function(event) {
    console.log(event);
    var data = event.data.json();

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.message,
            icon: data.icon,
            data: {
                url: data.url
            }
        })['catch'](function (n) {
            return self.registration.showNotification('Something Has Happened', {
                body: 'Here\'s something you might want to check out.',
                icon: 'static/images/icon_512.png',
                data: {
                    url: '/'
                }
            })
        })
    );
});

self.addEventListener('notificationclick', function(event) {
    console.log(event);
    var data = event.notification.data;

    event.notification.close();
    event.waitUntil(
        clients.matchAll({
            type: 'window'
        })
        .then(function(clientList) {
            for (var i = 0; i < clientList.length; i++) {
                var client = clientList[i];

                if (client.url == '/' && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(data.url || '/');
            }
        })
    );
});
