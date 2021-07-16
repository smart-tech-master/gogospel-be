<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    // list all videos
    public function index()
    {
        $videos = Video::all();

        return response()->json([
            'success' => true,
            'data' => $videos,
            'message' => 'Retrieved successfully'
        ]);
    }

    // create new video
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string',
                'link' => 'required|string',
            ]
        );

        $video = Video::create([
            'title' => $request->input('title'),
            'link' => $request->input('link'),
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'success' => true,
            'data' => $video,
            'message' => 'Created successfully'
        ]);
    }

    // update video
    public function update(Request $request, Video $video)
    {
        $request->validate(
            [
                'title' => 'required|string',
                'link' => 'required|string',
            ]
        );

        $video->update([
            'title' => $request->input('title'),
            'link' => $request->input('link'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }

    // delete video
    public function destroy(Video $video)
    {
        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
