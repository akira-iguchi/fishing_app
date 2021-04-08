<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(User $user, Event $event)
    {
        $events = Event::where('user_id', $user->id)->get();

        return view('events.index', [
            'events' => $events,
            'event' => $event,
            'user' => $user,
        ]);
    }

    public function setEvents(Request $request, User $user)
    {
        $start = $this->formatDate($request->all()['start']);
        $end = $this->formatDate($request->all()['end']);
        //表示した月のカレンダーの始まりの日を終わりの日をそれぞれ取得。

        $events = Event::where('user_id', $user->id)->whereBetween('date', [$start, $end])->get();
        //カレンダーの期間内のイベントを取得

        $newArr = [];
        foreach ($events as $item) {
            $newItem["id"] = $item["id"];
            $newItem["title"] = $item["spot"];
            $newItem["start"] = $item["date"];
            $newItem["fishing_type"] = $item["fishing_type"];
            $newItem["bait"] = $item["bait"];
            $newItem["weather"] = $item["weather"];
            $newItem["fishing_start_time"] = $item["fishing_start_time"];
            $newItem["fishing_end_time"] = $item["fishing_end_time"];
            $newItem["detail"] = $item["detail"];
            $newArr[] = $newItem;
        }

        return response()->json($newArr);
    }

    public function addEvent(EventRequest $request, Event $event)
    {
        $event->fill($request->all());
        $event->user_id = Auth::id();
        $event->save();

        return response()->json($event);
    }

    public function editEventDate(Request $request)
    {
        $data = $request->all();
        $event = Event::find($data['id']);
        $event->date = $data['newDate'];
        $event->save();
        return null;
    }

    public function formatDate($date)
    {
        return str_replace('T00:00:00+09:00', '', $date);
    }

    public function editEvent(User $user, Event $event)
    {
        if (\Auth::id() === $event->user_id) {
            return view('events.edit', [
                'user' => $user,
                'event' => $event,
            ]);
        } else {
            session()->flash('error_message', '自信が投稿したイベントのみ編集できます');
            return redirect('/');
        }
    }

    public function updateEvent(EventRequest $request, User $user, Event $event)
    {
        return DB::transaction(function () use ($event, $request) {
            $event->user_id = Auth::id();
            $event->fill($request->all())->save();

            session()->flash('flash_message', 'イベントをを更新しました');
            return redirect()->route('events', ['user' => Auth::user()]);
        });
    }

    public function deleteEvent(User $user, $id)
    {
        $event = Event::findOrFail($id);
        if (\Auth::id() === $event->user_id) {
            $event->delete();
            return response()->json($event);
        } else {
            return redirect('/')->with('flash_message', '自信の投稿のみ削除できます');
        }
    }
}
