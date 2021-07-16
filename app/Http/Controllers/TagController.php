<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // create new tag
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
            ]
        );

        $tag = Tag::create(
            [
                'name' => strtolower($request->input('name')),
                'text' => $request->input('name')
            ]
        );

        $user = $request->user();
        $tag->users()->attach($user);

        return response()->json([
            'data' => $tag,
            'message' => 'Created successfully'
        ]);
    }
}
