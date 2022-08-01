<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Sake;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class ApiController extends Controller
{
    /**
     * Get all posts at Top screen
     */
    public function index()
    {
        try {
            // Get all post with user_name
            $posts = Post::addSelect([
                'user_disp_id' => User::select('user_id')
                    ->whereColumn('id', 'posts.user_id')
                    ->limit(1),
                'user_name' => User::select('user_name')
                    ->whereColumn('id', 'posts.user_id')
                    ->limit(1),
                'kuramoto' => Sake::select('kuramoto')
                    ->whereColumn('id', 'posts.sake_id')
                    ->limit(1)
            ])->get();
            $result = [
                'status' => true,
                'message' => 'Success',
                'result' => [
                    'posts' => $posts
                ]
            ];
        } catch (Exception $e) {
            // output logs
            Log::error(__METHOD__. ' Exception Error');
            Log::error($e->getMessage());

            $result = [
                'status' => false,
                'message' => 'Failed'
            ];
        }

        return response()->json($result);
    }

    /**
     * Get user information at UserProfile screen
     * @param string $user_id 
     */
    public function getUserInfo(string $user_id)
    {
        try {
            // Get not deleted user's information
            $user = User::where([
                ['user_id', $user_id],
                ['deleted', false]
            ])->first();

            // Get posts the user have
            $user->posts;
            $result = [
                'status' => true,
                'message' => 'Success',
                'result' => [
                    'user' => $user,
                ]
            ];
        } catch (Exception $e) {
            // output logs
            Log::error(__METHOD__. ' Exception Error');
            Log::error($e->getMessage());

            $result = [
                'status' => false,
                'message' => 'Failed'
            ];
        }

        return response()->json($result);
    }

    /**
     * Save sake datas from Python
     * @param Request $request
     */
    public function saveSakeDatas(Request $request)
    {
        if(empty($request)) {
            throw new Exception();
        }

        $sake_info = $request->sake_info;    
        foreach($sake_info as $prefecture => $kuramotos)
        {
            foreach($kuramotos as $kuramoto => $sakes)
            {
                foreach($sakes as $sake_name)
                {
                    try {
                        $sake = new Sake;
                        $sake->fill([
                            'sake_name' => $sake_name,
                            'kuramoto' => $kuramoto,
                            'prefecture' => $prefecture
                        ]);
                        $sake->save();
                    } catch(Exception $e) {
                        Log::error(__METHOD__. ' Exception Error');
                        Log::error($e->getMessage());
                    }
                }
            }
        }
    }
}
