<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function index()
    {
        try {
            // Get all post with user_name
            $posts = Post::addSelect([
                'user_name' => User::select('user_name')
                    ->whereColumn('id', 'posts.user_id')
                    ->limit(1),
            ])->get();
            $result = [
                'message' => 'Success',
                'posts' => $posts
            ];
        } catch (Exception $e) {
            // output logs
            Log::error(__METHOD__. ' Exception Error');
            Log::error($e->getMessage());

            $result = ['message' => 'Failed'];
        }

        return response()->json($result);
    }
}
