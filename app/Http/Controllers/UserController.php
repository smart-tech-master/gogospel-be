<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use GuzzleHttp\Utils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // update or create user tags
    public function updateOrCreateTags(Request $request)
    {
        $request->validate(
            [
                'tags' => 'required|string',
            ]
        );
        $request->user()->fill([
            'tags' => $request->input('tags'),
        ]);

        $request->user()->save();

        return response()->json([
            'message' => 'Success'
        ]);
    }

    public function getVideos(Request $request)
    {
        return response()->json([
            'data' => $request->user()->videos()->where('is_profile', false)->take(4)
        ]);
    }

    /**
     * Get profile details of the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfileDetails(Request $request)
    {
        $user = $request->user();
        $videos = $user->load(['videos' => function ($query) {
            $query->where('is_profile', false)->orderBy('created_at', 'DESC')->take(4);
        }]);
        $profile_video = Video::where('user_id', $request->user()->id)
            ->where('is_profile', true)
            ->first();
        if (isset($profile_video) && $profile_video) {
            $user->video = $profile_video;
        } else {
            $user->video = '';
        }
        $user->videos = $videos;
        $user->tags = Utils::jsonDecode($user->tags);
        return response()->json($user);
    }
}
