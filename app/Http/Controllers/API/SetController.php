<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Set;

class SetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sets",
     *     summary="Get all sets",
     *     tags={"Sets"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of sets",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Set")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Fetch all sets with optional filters
       $sets=Set::query()
            ->when($request->has('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->input('user_id'));
            })
            ->when($request->has('is_premium'), function ($query) use ($request) {
                return $query->where('is_premium', $request->input('is_premium'));
            })
            ->with(['user', 'tracks'])
            ->paginate(10);

        return response()->json($sets);
    }
}
