<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Track;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TracksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function index()
    {
        if (Auth::user()->roles == 0) {
            $u = User::with('tracks')->get();
        } else {
            $u = User::where('id', Auth::user()->id)->with('tracks')->get();
        }
        // dd($tracks);
        return view('admin', ['users' => $u]);
    }
    function allTracks()
    {
        if (Auth::user()->roles == 0) {
            $tracks = Track::all();
        } else {
            $tracks = Track::where('user_id', Auth::id())->get();
        }
        return response()->json($tracks);
    }
    function view($id)
    {

        $track = Track::where('id', $id)->first();
        if ($track->user_id != Auth::user()->id and Auth::user()->roles != 0) {
            abort(404);
        }


        return view('admintrack', ["id" => $id, 'track' => $track]);
    }
    function store(Request $request)
    {
        $validatedData = $request->validate([

            'title' => ['required', 'string', 'max:50']

        ]);
        Track::create([
            'title' => $request->input('title'),
            'user_id' => Auth::user()->id
        ]);

        return redirect('/admin');
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([

            'title' => ['required', 'string', 'max:50']

        ]);
        $track = Track::findOrFail($request->input('id'));

        if ($track->user_id != Auth::user()->id and Auth::user()->roles != 0) {
            abort(404);
        }
        $track->title = $request->input('title');
        $track->save();
        return redirect('/admin');
    }
    protected function removetrack($id)
    {
        $track = Track::find($id);

        if ($track->user_id != Auth::user()->id and Auth::user()->roles != 0) {
            abort(404);
        }
        $track->delete();

        return redirect('/admin');
    }

    /**
     * Duplicate track
     *
     * @param Request $request
     *
     * @return Response
     */
    public function duplicate(Request $request)
    {
        $this->validate($request, [
            'number_duplicate' => 'required|numeric|min:1',
            'track_id' => 'required|exists:' . Track::class . ',id'
        ]);

        DB::beginTransaction();

        $sourceTrack = Track::find($request->track_id);

        for ($i = 0; $i < $request->number_duplicate; $i++) {
            $newTrack = $sourceTrack->replicate();
            $newTrack->save();

            $sourceTrack->points->each(function ($point) use ($newTrack) {
                $newPoint = $point->replicate()->fill([
                    'track' => $newTrack->id
                ]);
                $newPoint->save();
            });
        }

        DB::commit();

        return redirect()
            ->route('admin.track.edit', ['id' => $request->track_id])
            ->with('status', __('basic.track_copied'));
    }

    /**
     * Move track
     *
     * @param Request $request
     *
     * @return Response
     */
    public function move(Request $request)
    {
        $this->validate($request, [
            'number_interval' => 'required|numeric|between:-99,99',
            'interval' => 'required|in:minutes,hours,days,weeks,years',
            'track_id' => 'required|exists:' . Track::class . ',id'
        ]);

        DB::beginTransaction();

        $sourceTrack = Track::find($request->track_id);
        $sourceTrack->points->each(function ($point) use ($request) {
            $point->start = Carbon::parse($point->start)->add($request->number_interval, $request->interval)->toDateTimeString();
            $point->stop = Carbon::parse($point->stop)->add($request->number_interval, $request->interval)->toDateTimeString();
            $point->save();
        });

        DB::commit();

        return redirect()
            ->route('admin.track.point', ['id' => $sourceTrack->id])
            ->with('status', __('basic.track_move'));
    }

    /**
     * Hide menu bar in front end
     *
     * @param Request $request
     *
     * @return Response
     */
    public function hideMenuBar(Request $request)
    {
        $this->validate($request, [
            'track_id' => 'required|exists:' . Track::class . ',id',
            'is_checked' => 'required|boolean'
        ]);

        $track = Track::find($request->track_id);
        $track->hide_menu_bar = $request->is_checked;
        $track->save();

        return response()->json([
            'status' => true,
            'msg' => __('basic.save_successfully')
        ]);
    }

    /**
     * Show log this track to public
     *
     * @param Request $request
     *
     * @return Response
     */
    public function showLogPublic(Request $request)
    {
        $this->validate($request, [
            'track_id' => 'required|exists:' . Track::class . ',id',
            'is_checked' => 'required|boolean'
        ]);

        $track = Track::find($request->track_id);
        $track->show_log_public = $request->is_checked;
        $track->save();

        return response()->json([
            'status' => true,
            'msg' => __('basic.save_successfully')
        ]);
    }
}
