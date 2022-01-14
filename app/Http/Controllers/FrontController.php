<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Track;
use App\Visitor;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{

    public function index($id)
    {

        $track = Track::where('id', $id)->first();

        if (Visitor::ready()->count() == 0) {
            Visitor::create([
                'ip_address' => request()->ip(),
                'track_id' => $track->id
            ]);
            $track->visitor++;
            $track->save();
        }

        return view('frontpage', ['track' => $track]);
    }
    public function edit($id)
    {

        $track = Track::where('id', $id)->first();
        if ($track->user_id != Auth::user()->id and Auth::user()->roles != 0) {
            abort(404);
        }
        return view('edittrack', ['track' => $track]);
    }
    public function help()
    {

        return view('help');
    }
}
