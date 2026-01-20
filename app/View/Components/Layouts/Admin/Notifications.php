<?php

namespace App\View\Components\Layouts\Admin;

use App\Abstracts\View\Component;

class Notifications extends Component
{
    public $notifications;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        // Promotional notifications came from the Akaunting marketplace, which has been removed.
        $this->notifications = [];

        return view('components.layouts.admin.notifications');
    }
}
