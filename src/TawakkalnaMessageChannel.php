<?php

namespace Abather\TawakkalnaMessage;

use Illuminate\Notifications\Notification;

class TawakkalnaMessageChannel
{
    protected TawakkalnaClient $client;

    public function send($notifiable, Notification $notification)
    {
        $receiver = $this->routeNotification($notifiable);

        if (! $receiver) {
            return;
        }

        $message = $notification->toTawakkalna($notifiable);

        if (! $message) {
            return;
        }

        if (is_string($message)) {
            $message = TawakkalnaMessage::make($message);
        } elseif (! $message instanceof TawakkalnaMessage) {
            return;
        }

        $message->validateMessage();

        TawakkalnaClient::make()
            ->sendMessage($message->getMessage(), $receiver, $message->getPhone());
    }

    private function routeNotification($notifiable): ?string
    {
        $receiver = $notifiable->routeNotificationFor('tawakkalna-message');

        if (! $receiver) {
            $receiver = $notifiable->routeNotificationFor(TawakkalnaMessageChannel::class);
        }

        if (! $receiver) {
            $receiver = $notifiable->routeNotificationForTawakkalna();
        }

        if (! $receiver) {
            $receiver = config('tawakkalna-message.identifier_attribute');
        }

        if (! $receiver) {
            return null;
        }

        return $notifiable->$receiver;
    }
}
