<?php

namespace App\Http\Controllers;

use App\Claim;
use App\Point;
use App\Track;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Alchemy\Zippy\Zippy;

class ClaimController extends Controller
{
    var $folderClaimsZip = "claims_zip";

    public function claim($track_id, $waypoint_id)
    {
        $track = Track::find($track_id);
        $waypoint = Point::find($waypoint_id);
        if (!$track || !$waypoint) {
            return view('expiredClaim');
        }

        return view('claim', compact('track', 'waypoint'));
    }

    public function saveClaim(Request $request, $track_id, $waypoint_id)
    {
        $track = Track::find($track_id);
        $waypoint = Point::find($waypoint_id);

        if (!$track || !$waypoint)
            return view('expiredClaim');

        $code = $request->get('code');
        if ($waypoint->request_token && $code != $waypoint->code)
            return redirect()->back()->with("error", __('messages.claim_code_invalid'));

        if ($waypoint->upload_photo && (!$request->has('image_upload') || ($request->has('image_upload') && ($request->image_upload == null || $request->image_upload == ""))))
            return redirect()->back()->with("error", __('basic.upload_photo_required'));

        $comment = $request->get('comment') ?: ' ';
        // $remarks_questions = $request->get('remarks_questions') ?: ' ';

        DB::beginTransaction();

        $claim =  Claim::create([
            'user_id' => $track->user_id,
            'track_id' => $track_id,
            'point_id' => $waypoint_id,
            'remark' => $comment,
            // 'remark_question' => $remarks_questions,
            'visitor_ip' => $this->get_client_ip()
        ]);

        if ($request->has('image_upload')) {
            $file = $request->image_upload;
            $extensi = null;

            if (strpos($file, 'data:image/png;base64') !== false) {
                $file = str_replace('data:image/png;base64,', '', $file);
                $extensi = '.png';
            } else if (strpos($file, 'data:image/jpg;base64') !== false) {
                $file = str_replace('data:image/jpg;base64,', '', $file);
                $extensi = '.jpg';
            } else if (strpos($file, 'data:image/jpeg;base64') !== false) {
                $file = str_replace('data:image/jpeg;base64,', '', $file);
                $extensi = '.jpg';
            }

            if (!$extensi)
                return redirect()->back()->with("error", __('basic.upload_photo_not_support'));

            $file = str_replace(' ', '+', $file);

            $date = Carbon::now()->format('Ymd_Hm');
            $filename = $track->id . '_' . preg_replace("/[^a-zA-Z0-9]+/", "", $waypoint->title) . '_' . $claim->id . '_' . $date . $extensi;

            Storage::makeDirectory('claims/' . $track_id);

            $pathFolder = storage_path('app/claims/' . $track_id);
            $fullPath = $pathFolder . '/' . $filename;

            file_put_contents($fullPath, base64_decode($file));

            $claim->photo = 'claims/' . $track_id . '/' . $filename;
            $claim->save();
        }

        if (!$waypoint->next_point) {
            $waypoint->update(['stop' => Carbon::now()]);
        } elseif ($waypoint->next_point != $waypoint->id) {
            $waypoint->update(['stop' => Carbon::now()]);
            $nxt_point = explode(',', $waypoint->next_point);

            Point::wherein('id', $nxt_point)->update(['start' => Carbon::now()]);
        }

        DB::commit();

        return redirect('/track/' . $track_id)->with('success', __('messages.location_claim_success'));


        // if(!$track || !$waypoint){
        //     return view('expiredClaim');

        // }

        // return view('claim', compact('track', 'waypoint'));
    }

    private function  get_client_ip()
    {
        //whether ip is from the share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function logs(Request $request)
    {
        $data = [
            'single' => false
        ];
        return view('adminlogs', compact('data'));
    }
    public function singleLog(Request $request, $track_id)
    {
        $tracks = Track::where('id', $track_id)->first();
        $hasPhoto = Claim::where('track_id', $tracks->id)
            ->whereNotNull('photo')
            ->count() > 0;
        $data = [
            'single' => true,
            'has_photo' => $hasPhoto,
            'tracks' => $tracks
        ];

        return view('adminlogs', compact('data'));
    }
    public function logForFrontend(Request $request, $track_id)
    {
        $tracks = Track::where('id', $track_id)->first();

        if (!$tracks->show_log_public)
            return redirect()->route('frontend.track.view', ['id' => $track_id]);

        $data = [
            'tracks' => $tracks
        ];

        return view('publiclogs', compact('data'));
    }
    public function claims(Request $request)
    {
        // dd($request->all());
        $ids = $request->get('ids');
        $datetime = $request->get('datetime');

        if (Auth::check()) {
            if (!$ids) {
                if (Auth::user()->roles == 0) {
                    $tracks = Track::all()->pluck('id');
                } else {
                    $tracks = Track::where('user_id', Auth::id())->pluck('id');
                }
            } else {
                $tracks = explode(',', $ids);
            }
        } else {
            $tracks = [$ids];
        }

        $query = Claim::with('track', 'point')->whereIn('track_id', $tracks);
        if ($datetime) {
            $datetime = Carbon::parse($datetime);
            $query = $query->where('created_at', '>=', $datetime);
        }

        $claims = (Auth::check() ? $query : $query->select('id', 'user_id', 'track_id', 'point_id', 'remark', 'created_at', 'updated_at', 'photo'))->get();

        return response()->json($claims);
    }

    public function showPhoto($folder, $track_id, $filename)
    {
        return response()->download(storage_path('app/' . $folder . '/' . $track_id . '/' . $filename), null, [], null);
    }

    public function downloadPhotoClaim($track_id)
    {
        $claims = Claim::select('photo')
            ->whereNotNull('photo')
            ->get()
            ->filter(function ($item) {
                return file_exists(storage_path('app/' . $item->photo));
            })
            ->map(function ($item) {
                return storage_path('app/' . $item->photo);
            })
            ->toArray();

        $folder = $this->folderClaimsZip . "/" . $track_id;

        Storage::makeDirectory($folder);

        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $urlFileZip = storage_path('app/' . $folder . '/' . Carbon::now()->timestamp . uniqid() . '.zip');
        $this->makeZipWithFiles($urlFileZip, $claims);

        ob_end_clean();
        return response()->download($urlFileZip, null, [], null);
    }

    public function makeZipWithFiles(string $zipPathAndName, array $filesAndPaths)
    {
        $zip = new ZipArchive();
        $tempFile = tmpfile();
        $tempFileUri = stream_get_meta_data($tempFile)['uri'];
        if ($zip->open($tempFileUri, ZipArchive::CREATE) === TRUE) {
            // Add File in ZipArchive
            foreach ($filesAndPaths as $file) {
                if (!$zip->addFile($file, basename($file))) {
                    echo 'Could not add file to ZIP: ' . $file;
                }
            }
            // Close ZipArchive
            $zip->close();
        } else {
            echo 'Could not open ZIP file.';
        }
        echo 'Path:' . $zipPathAndName;
        rename($tempFileUri, $zipPathAndName);
    }

    public function deletePhotoClaim($track_id)
    {
        $claims = Claim::whereNotNull('photo')->get();

        $claims->each(function ($claim) {
            Storage::delete($claim->photo);
            $claim->update(['photo' => null]);
        });

        return redirect()->route('showlogssingle', ['track_id' => $track_id])
            ->with('status', __('basic.photo_delete_successfully'));
    }
}
