<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBlogPost implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function broadcastOn()
    {
        return new Channel('my-channel1');
    }

    public function broadcastAs()
    {
        return 'my-event2';
    }

    // Optional: Implement broadcastWhen method for authentication
    // public function broadcastWhen()
    // {
    //     return auth()->check(); // Example: Only broadcast if the user is authenticated
    // }
}

