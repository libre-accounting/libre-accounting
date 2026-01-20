<?php

namespace App\Listeners\Menu;

use App\Events\Menu\NotificationsCreated as Event;
use App\Traits\Modules;
use App\Utilities\Versions;
use Illuminate\Notifications\DatabaseNotification;

class ShowInNotifications
{
    use Modules;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if (user()->cannot('read-notifications')) {
            return;
        }

        // Notification tables
        $notifications = collect();

        // Update notifications
        if (user()->can('read-install-updates')) {
            $updates = Versions::getUpdates();

            foreach ($updates as $key => $update) {
                $prefix = ($key == 'core') ? 'core' : 'module';

                if ($prefix == 'module' && ! module($key)) {
                    continue;
                }

                $name = ($prefix == 'core') ? config('app.name') : module($key)?->getName();

                $new = new DatabaseNotification();
                $new->id = $key;
                $new->type = 'updates';
                $new->notifiable_type = "users";
                $new->notifiable_id = user()->id;
                $new->data = [
                    'title' => $name . ' (v' . $update?->latest . ')',
                    'description' => trans('install.update.' . $prefix, ['module' => $name, 'url' => route('updates.index')]),
                ];
                $new->created_at = \Carbon\Carbon::now();

                $notifications->push($new);
            }
        }

        $unReadNotifications = user()->unReadNotifications;

        foreach ($unReadNotifications as $unReadNotification) {
            $notifications->push($unReadNotification);
        }

        $event->notifications->notifications = $notifications;
    }
}
