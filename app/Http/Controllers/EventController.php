<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::all();

        return view('events.index', [
            'events' => $events,
        ]);
    }

    public function setEvents(Request $request){

        $start = $this->formatDate($request->all()['start']);
        $end = $this->formatDate($request->all()['end']);
        //表示した月のカレンダーの始まりの日を終わりの日をそれぞれ取得。

        $events = Event::select('id', 'fishing_type', 'date')->whereBetween('date', [$start, $end])->get();
        //カレンダーの期間内のイベントを取得

        $newArr = [];
        foreach($events as $item){
            $newItem["id"] = $item["id"];
            $newItem["fishing_type"] = $item["fishing_type"];
            $newItem["start"] = $item["date"];
            $newArr[] = $newItem;
        }

        return response()->json($newArr);
    }

    public function addEvent(EventRequest $request, Event $event)
    {
        $event->fill($request->all());
        $event->user_id = Auth::id();
        $event->save();

        return response()->json(['id' => $event->id ]);
    }

    public function editEventDate(Request $request){
        $data = $request->all();
        $event = Event::find($data['id']);
        $event->date = $data['newDate'];
        $event->save();
        return null;
    }

    public function formatDate($date){
        return str_replace('T00:00:00+09:00', '', $date);
    }
}
