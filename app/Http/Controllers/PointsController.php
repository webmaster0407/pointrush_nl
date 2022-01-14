<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Point;
use App\Track;
use App\Http\Resources\Point as PointResource;
use Illuminate\Support\Facades\Auth;


class PointsController extends Controller
{
    function getpoints($id)
    {

        $track = Track::where('id', $id)->first();
        if (!$track) {
            abort(404);
        }
        if ($track->user_id != Auth::user()->id and Auth::user()->roles != 0) {
            abort(404);
        }
        $points = Point::where('track', $id)->orderBy('id', 'asc')->get();

        return new PointResource($points);
    }
    function savepoints(Request $request, $id)
    {
        $track = Track::where('id', $id)->first();
        if ($track->user_id != Auth::user()->id and Auth::user()->roles != 0) {
            abort(404);
        }
        $ptodel = Point::where('track', $id);

        $data = $request->input('data');
        $errors = [];
        $ids = [];
        if ($data && count($data)) {
            foreach ($data as $key => $value) {
                if ($value['pid'] != -1) {
                    $ids[] = $value['pid'];
                }
                if (!$value['title']) {
                    $errors[] = '#ID ' . $value['id'] . ': ' . __('messages.name_empty');;
                }
                if (!$value['start']) {
                    $errors[] = '#ID ' . $value['id'] . ': ' . __('messages.start_date_empty');;
                }
                if (!$value['stop']) {
                    $errors[] = '#ID ' . $value['id'] . ': ' . __('messages.end_date_empty');;
                }
            }

            array_walk($data, function (&$item, $k, $id) {
                $item['start'] = date("Y-m-d H:i:s", strtotime($item['start']));
                $item['stop'] = date("Y-m-d H:i:s", strtotime($item['stop']));
                $item['track'] = $id;
                if (isset($item['time'])) {
                    $item['time'] = (int)filter_var($item['time'], FILTER_VALIDATE_BOOLEAN);
                } else {
                    $item['time'] = 0;
                }
                if (isset($item['showtitle'])) {
                    $item['showtitle'] = (int)filter_var($item['showtitle'], FILTER_VALIDATE_BOOLEAN);
                } else {
                    $item['showtitle'] = 0;
                }
                unset($item['loc']);
                unset($item['id']);
            }, $id);

            $status = count($errors) ? 0 : 1;
            $data = count($errors) ? $errors : $data;

            if (!count($errors)) {

                $ptodel->whereNotIn('id', $ids)->delete();

                foreach ($data as $key => $value) {
                    $pid = $value['pid'];

                    if ($value['pid'] != -1) {
                        unset($value['pid']);
                        $point =  Point::whereId($pid)->update($value);
                    } else {
                        unset($value['pid']);
                        $point =  Point::create($value);
                        $pid = $point->id;
                    }
                    $pointData = Point::find($pid);
                    //
                    $data[$key]['id'] = $pid;
                    $data[$key]['pid'] = $pid;
                    $data[$key]['code'] = $pointData->code;
                    $data[$key]['color'] = $pointData->color;
                    $data[$key]['transparant'] = $pointData->transparant;
                }

                // $ptodel->delete();
                // Point::insert($data);
            }
        } else {
            $status = $ptodel->delete();
        }


        return response()->json([
            'status' => $status,
            'data' => $data
        ]);
    }
    function getpoint($id)
    {

        $points = Point::where('track', $id)
            ->where('start', '<=', date('Y-m-d H:i:s', time()))
            ->where('stop', '>', date('Y-m-d H:i:s', time()))->get();

        return new PointResource($points);
    }

    public function saveClaimSetting(Request $request, $point_id)
    {
        $point =  Point::whereId($point_id)->update([
            "code" => $request->get('code'),
            "next_point" => $request->get('next_point'),
            "upload_photo" => $request->get('upload_photo'),
            "request_token" => $request->get('request_token'),
            "showrequest" => $request->get('remarks'),
            "remarks_questions" => $request->get('remarks_questions')
        ]);
        return response()->json([
            'status' => 1 //$point,
        ]);
    }
    public function getClaimSetting($point_id)
    {
        $point =  Point::find($point_id);
        return response()->json($point);
    }

    /**
     * Save color waypoint
     *
     * @param Request $request
     */
    public function saveColor(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:points,id',
            'color' => 'required',
            'transparant' => 'required|boolean',
        ]);

        $point = Point::find($request->id);
        $point->update($request->only(['color', 'transparant']));

        return response()->json([
            'status' => true
        ]);
    }
}
