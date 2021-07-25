<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::where('starts_at', '>=', now())
            ->with(['tags', 'user'])
            ->orderBy('starts_at', 'asc')
            ->get();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authed_user = auth()->user();
        $amount = 1000;

        if($request->filled('premium')) $amount += 500;

        $authed_user->charge(
            $amount, $request->payment_method
        );

        $event = $authed_user->events()
            ->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . uniqid(),
                'content' => $request->content,
                'premium' => $request->filled('premium'),
                'starts_at' => $request->starts_at,
                'ends_at' => $request->ends_at
            ]);

        $tags = explode(',', $request->tags);

        foreach($tags as $inputTag) {
            $nameOrSlug = trim($inputTag);

            $tag = Tag::firstOrCreate([
                'slug' => $nameOrSlug,
            ], [
                'name' => $nameOrSlug
            ]);

            $event->tags()->attach($tag->id);
        }

        return redirect()->route('events.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
