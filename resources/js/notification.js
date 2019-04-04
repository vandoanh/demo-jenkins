(function (define) {
    define(['jquery'], function ($) {
        return (function () {
            var settings = {
                subscribe_link: '',
                unsubscribe_link: '',
                vapid_public_key: ''
            };

            var support = true;
            var blockNotification = false;
            var pushEnabled = false;
            var isSending = false;

            return notification = {
                isSupport: isSupport,
                isPushEnabled: isPushEnabled,
                isBlockNotification: isBlockNotification,
                init: init,
                subscribe: subscribe,
                unsubscribe: unsubscribe
            };

            function init(options) {
                if (typeof ServiceWorkerRegistration === 'undefined') {
                    support = false;
                    return;
                }

                settings = $.extend(settings, options || {});

                if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
                    console.log('Notifications aren\'t supported.');
                    support = false;
                    return;
                }

                if (!('PushManager' in window)) {
                    console.log('Push messaging isn\'t supported.');
                    support = false;
                    return;
                }

                if (Notification.permission === 'denied') {
                    console.log('The user has blocked notifications.');
                    blockNotification = true;
                    return;
                }

                navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
                    serviceWorkerRegistration.pushManager
                        .getSubscription()
                        .then(function(subscription) {
                            if (!subscription) {
                                return;
                            }

                            updateSubscription(subscription);
                            pushEnabled = true;
                        })
                        .catch(function(e) {
                            console.error('Error during getSubscription()', e);
                        });
                });
            }

            function isSupport() {
                return support;
            }

            function isPushEnabled() {
                return pushEnabled;
            }

            function isBlockNotification() {
                return blockNotification;
            }

            function subscribe() {
                if ('PushManager' in window) {
                    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
                        serviceWorkerRegistration.pushManager
                            .subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: urlBase64ToUint8Array(settings.vapid_public_key)
                            })
                            .then(function (subscription) {
                                updateSubscription(subscription);
                            })
                            .catch(function (e) {
                                if (Notification.permission === 'denied') {
                                    console.warn('Permission for Notifications was denied');
                                } else {
                                    console.error('Unable to subscribe to push.', e);
                                }
                            });
                    });
                }
            }

            function unsubscribe() {
                if ('PushManager' in window) {
                    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
                        serviceWorkerRegistration.pushManager
                            .getSubscription()
                            .then(function(subscription) {
                                if (!subscription) {
                                    return;
                                }

                                subscription.unsubscribe().then(function() {
                                    deleteSubscription(subscription);
                                }).catch(function(e) {
                                    console.log('Unsubscription error: ', e);
                                });
                            })
                            .catch(function(e) {
                                console.error('Error thrown while unsubscribing from push messaging.', e);
                            });
                    });
                }
            }

            function updateSubscription(subscription) {
                if (!isSending) {
                    var public_key = subscription.getKey('p256dh');
                    var auth_token = subscription.getKey('auth');

                    $.ajax({
                        url: settings.subscribe_link,
                        method: 'get',
                        data: {
                            endpoint: subscription.endpoint,
                            public_key: public_key ? btoa(String.fromCharCode.apply(null, new Uint8Array(public_key))) : null,
                            auth_token: auth_token ? btoa(String.fromCharCode.apply(null, new Uint8Array(auth_token))) : null,
                            content_encoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0]
                        },
                        beforeSend: function() {
                            isSending = true;
                        },
                        success: function() {
                            isSending = false;
                        },
                        error: function() {
                            isSending = false;
                        }
                    });
                }
            }

            function deleteSubscription(subscription) {
                $.ajax({
                    url: settings.unsubscribe_link,
                    method: 'get',
                    data: {
                        endpoint: subscription.endpoint
                    }
                });
            }

            function urlBase64ToUint8Array(base64String) {
                var padding = '='.repeat((4 - base64String.length % 4) % 4);
                var base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
                var rawData = window.atob(base64);
                var outputArray = new Uint8Array(rawData.length);

                for (var i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }

                return outputArray;
            }
        })();
    });
}(typeof define === 'function' && define.amd ? define : function(global, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'));
    } else {
        window.notification = factory(window.jQuery);
    }
}));

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js').then(function(swReg) {
            console.log('Service Worker is registered: ', swReg.scope);
        }, function(error) {
            // registration failed :(
            console.log('ServiceWorker registration failed: ', error);
        });
    });
}
