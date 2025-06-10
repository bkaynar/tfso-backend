<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // accessed_at için Carbon kütüphanesini kullanacağız

/**
 * @OA\Tag(
 * name="Access Logs",
 * description="API Endpoints for managing access logs"
 * )
 */
class AccessLogController extends Controller
{

    /**
     * @OA\Get(
     *    path="/api/access-logs",
     * summary="Get access logs",
     * description="Retrieve a list of access logs with optional filters",
     * tags={"Access Logs"},
     * @OA\Parameter(
     * name="user_id",
     * in="query",
     * description="Filter by user ID",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="content_type",
     * in="query",
     * description="Filter by content type (e.g., 'Post', 'Comment')",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="A list of access logs",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="data", type="array",
     * @OA\Items(ref="#/components/schemas/AccessLog")
     * ),
     * @OA\Property(property="last_page", type="integer", example=5),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="total", type="integer", example=75)
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="errors", type="object",
     * @OA\Property(property="user_id", type="array", @OA\Items(type="string", example="The user id field is required.")),
     * @OA\Property(property="content_type", type="array", @OA\Items(type="string", example="The content type field is required."))
     * )
     * )
     * )
     * )
     */
    public function index(Request $request)
    {
        $query = AccessLog::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('content_type')) {
            $query->where('content_type', $request->input('content_type'));
        }

        $accessLogs = $query->latest()->paginate(15); // En son eklenenleri ve sayfalama

        return response()->json($accessLogs);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id', // 'users' tablosu ve 'id' sütunu varsayılmıştır
            'content_type' => 'required|string|max:255',
            'content_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $accessLog = AccessLog::create([
            'user_id' => $request->user_id,
            'content_type' => $request->content_type,
            'content_id' => $request->content_id,
            'accessed_at' => Carbon::now(), // Erişim anını otomatik olarak kaydet
        ]);

        return response()->json($accessLog, 201);
    }


    public function show(AccessLog $accessLog)
    {
        return response()->json($accessLog);
    }
}
