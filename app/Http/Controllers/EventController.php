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

        $events = Event::select('event_id', 'title', 'date')->whereBetween('date', [$start, $end])->get();
        //カレンダーの期間内のイベントを取得

        $newArr = [];
        foreach($events as $item){
            $newItem["id"] = $item["event_id"];
            $newItem["title"] = $item["title"];
            $newItem["start"] = $item["date"];
            $newArr[] = $newItem;
        }

        return response()->json($newArr);
    }

    public function addEvent(Request $request)
    {
        $data = $request->all();
        $event = new Event();
        $event->event_id = 1;
        $event->date = $data['date'];
        $event->title = $data['title'];
        $event->save();

        return response()->json(['event_id' => $event->event_id ]);
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
