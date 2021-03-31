<?php

namespace Tests\Factories\Traits;

use App\Models\User;
use App\Models\Event;

trait CreateEvent
{
    private function createEvent(): Event
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $event = Event::factory()->for($user)->create(['fishing_type' => 'サビキ釣り']);

        return $event;
    }
}