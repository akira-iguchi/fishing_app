<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->event = Event::factory()->for($this->user)->create();

    }

    public function testFactoryable()
    {
        $eloquent = app(Event::class);
        $this->assertNotEmpty($eloquent->get());
    }

    public function testEventBelongsToUser()
    {
        $this->assertNotEmpty($this->event->user);
    }
}
