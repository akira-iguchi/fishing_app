<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function testFactoryable()
    {
        $eloquent = app(Event::class);
        $this->assertEmpty($eloquent->get());
        $user = User::factory()->create();
        $event = Event::factory()->for($user)->create();
        $this->assertNotEmpty($eloquent->get());
    }

    public function testEventBelongsToUser()
    {
        $user = User::factory()->create();
        $event = Event::factory()->for($user)->create();
        $this->assertNotEmpty($event->user);
    }
}
