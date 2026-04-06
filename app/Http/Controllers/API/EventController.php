<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Event::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->has('category')) {
            $query->where('category', $request->get('category'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('start_date')) {
            $query->where('start_datetime', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('end_datetime', '<=', $request->get('end_date'));
        }

        $events = $query->orderBy('start_datetime')
                       ->paginate($request->get('per_page', 15));

        return response()->json($events);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:Curricular,Extra-curricular',
            'category' => 'required|in:Academic,Sports,Cultural,Seminar,Workshop,Competition,Other',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'venue' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'target_audience' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'current_participants' => 'nullable|integer|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
            'requirements' => 'nullable|string',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'status' => 'required|in:Upcoming,Ongoing,Completed,Cancelled',
            'notes' => 'nullable|string',
        ]);

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json($event);
    }

    public function update(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:Curricular,Extra-curricular',
            'category' => 'required|in:Academic,Sports,Cultural,Seminar,Workshop,Competition,Other',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'venue' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'target_audience' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'current_participants' => 'nullable|integer|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
            'requirements' => 'nullable|string',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'status' => 'required|in:Upcoming,Ongoing,Completed,Cancelled',
            'notes' => 'nullable|string',
        ]);

        $event->update($validated);

        return response()->json($event);
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return response()->json(null, 204);
    }

    public function curricular(Request $request): JsonResponse
    {
        $events = Event::curricular()
            ->when($request->has('category'), function ($query) use ($request) {
                $query->where('category', $request->get('category'));
            })
            ->orderBy('start_datetime')
            ->paginate($request->get('per_page', 15));

        return response()->json($events);
    }

    public function extracurricular(Request $request): JsonResponse
    {
        $events = Event::extracurricular()
            ->when($request->has('category'), function ($query) use ($request) {
                $query->where('category', $request->get('category'));
            })
            ->orderBy('start_datetime')
            ->paginate($request->get('per_page', 15));

        return response()->json($events);
    }

    public function upcoming(Request $request): JsonResponse
    {
        $events = Event::upcoming()
            ->when($request->has('type'), function ($query) use ($request) {
                $query->where('type', $request->get('type'));
            })
            ->when($request->has('category'), function ($query) use ($request) {
                $query->where('category', $request->get('category'));
            })
            ->orderBy('start_datetime')
            ->paginate($request->get('per_page', 15));

        return response()->json($events);
    }
}
