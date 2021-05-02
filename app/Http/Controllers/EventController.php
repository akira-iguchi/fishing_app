<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(String $id)
    {
        $user = User::findOrFail($id);
        $events = Event::where('user_id', $user->id)->get();

        $newArr = [];
        foreach ($events as $item) {
            $newItem['id'] = $item['id'];
            $newItem['title'] = $item['spot'];
            $newItem['start'] = $item['date'];
            $newItem['fishing_type'] = $item['fishing_type'];
            $newItem['fishing_start_time'] = $item['fishing_start_time'];
            $newItem['fishing_end_time'] = $item['fishing_end_time'];
            $newItem['detail'] = $item['detail'];
            $newArr[] = $newItem;
        }

        return [$user, $newArr];
    }

    public function store(EventRequest $request, Event $event)
    {
        $event->fill($request->all());
        $event->user_id = Auth::id();
        $event->save();

        return response($event, 201);
    }

    public function editEventDate(Request $request)
    {
        $data = $request->all();
        $event = Event::find($data['id']);
        $event->date = $data['newDate'];
        $event->save();
        return $event;
    }

    public function edit(String $userId, String $eventId)
    {
        $event = Event::findOrFail($eventId);
        if (\Auth::id() === $event->user_id) {
            $user = User::findOrFail($userId);
            $events = Event::where('user_id', $user->id)->get();

            $newArr = [];
            foreach ($events as $item) {
                $newItem['id'] = $item['id'];
                $newItem['title'] = $item['spot'];
                $newItem['start'] = $item['date'];
                $newItem['fishing_type'] = $item['fishing_type'];
                $newItem['fishing_start_time'] = $item['fishing_start_time'];
                $newItem['fishing_end_time'] = $item['fishing_end_time'];
                $newItem['detail'] = $item['detail'];
                $newArr[] = $newItem;
            }

            return [$user, $event, $newArr];
        } else {
            response()->json();
        }
    }

    public function update(EventRequest $request, String $userId, String $eventId)
    {
        $event = Event::findOrFail($eventId);

        return DB::transaction(function () use ($event, $request) {
            $event->user_id = Auth::id();
            $event->fill($request->all())->save();

            return response($event, 201);
        });
    }

    public function destroy(String $userId, String $eventId)
    {
        $event = Event::findOrFail($eventId);
        if (\Auth::id() === $event->user_id) {
            $event->delete();
            return response()->json();
        } else {
            return response()->json();
        }
    }
}
