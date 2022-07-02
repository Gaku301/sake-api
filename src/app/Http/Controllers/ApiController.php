<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::all();
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
