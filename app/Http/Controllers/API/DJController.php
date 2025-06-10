<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User; // User modelini kullanacağımız için import ediyoruz
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Rule::unique kullanmak için

/**
 * @OA\Components(
 *     @OA\Schema(
 *         schema="User",
 *         title="User",
 *         description="Model representing a user in the system",
 *         @OA\Property(property="id", type="integer", format="int64", description="Unique identifier for the user", readOnly=true),
 *         @OA\Property(property="name", type="string", description="Name of the user"),
 *         @OA\Property(property="email", type="string", format="email", description="Email address of the user"),
 *         @OA\Property(property="password", type="string", format="password", description="Password for the user account"),
 *         @OA\Property(property="profile_photo", type="string", description="URL of the user's profile photo"),
 *         @OA\Property(property="bio", type="string", description="Short biography of the user"),
 *         @OA\Property(property="instagram", type="string", description="Instagram handle of the user"),
 *         @OA\Property(property="twitter", type="string", description="Twitter handle of the user"),
 *         @OA\Property(property="facebook", type="string", description="Facebook profile URL of the user"),
 *         @OA\Property(property="tiktok", type="string", description="TikTok handle of the user"),
 *         @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp of the user account", readOnly=true),
 *         @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp of the user account", readOnly=true)
 *     )
 * )
 */
class DJController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/djs",
     *     summary="Get all DJs",
     *     tags={"DJs"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of DJs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Spatie Permission ile "dj" rolüne sahip kullanıcıları filtreleyelim
        $djs = User::role('dj');

        // Son eklenen 10 DJ'i al, sayfalama yok
        $djs = $djs->latest()->take(10)->get();

        return response()->json($djs);
    }

    


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'profile_photo' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $dj = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Şifreyi hash'lemeyi unutmayın!
            'profile_photo' => $request->profile_photo,
            'bio' => $request->bio,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'facebook' => $request->facebook,
            'tiktok' => $request->tiktok,
        ]);

        $dj->assignRole('dj');

        return response()->json($dj, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/djs/{id}",
     *     summary="Get a DJ by ID",
     *     tags={"DJs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the DJ",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="DJ details",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="DJ not found"
     *     )
     * )
     */
    
    public function show($id)
{
    // Sadece 'dj' rolüne sahip kullanıcıyı çek
    $dj = User::role('dj')->find($id);

    if (!$dj) {
        return response()->json(['message' => 'DJ not found'], 404);
    }

    // Sadece istenen alanları döndür
    $response = [
        'id' => $dj->id,
        'name' => $dj->name,
        'bio' => $dj->bio,
        'profile_photo' => $dj->profile_photo ? url($dj->profile_photo) : null,
        'social_media' => [
            'instagram' => $dj->instagram ? "https://instagram.com/{$dj->instagram}" : null,
            'twitter' => $dj->twitter ? "https://twitter.com/{$dj->twitter}" : null,
            'facebook' => $dj->facebook ? $dj->facebook : null,
            'tiktok' => $dj->tiktok ? "https://tiktok.com/@{$dj->tiktok}" : null,
        ],
    ];

    return response()->json($response);
}

    public function update(Request $request, $id)
    {
        // Yalnızca belirli rollere sahip kullanıcıların DJ güncellemesine izin ver
        // if (!auth()->user()->hasRole('admin')) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $dj = User::role('dj')->find($id);

        if (!$dj) {
            return response()->json(['message' => 'DJ not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($dj->id)],
            'password' => 'nullable|string|min:8', // Password nullable olabilir
            'profile_photo' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'name',
            'email',
            'profile_photo',
            'bio',
            'instagram',
            'twitter',
            'facebook',
            'tiktok'
        ]);

        // Eğer şifre güncelleniyorsa, hash'le
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $dj->update($data);

        return response()->json($dj);
    }


    public function destroy($id)
    {
        // Yalnızca belirli rollere sahip kullanıcıların DJ silmesine izin ver
        // if (!auth()->user()->hasRole('admin')) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $dj = User::role('dj')->find($id);

        if (!$dj) {
            return response()->json(['message' => 'DJ not found'], 404);
        }

        $dj->delete();

        return response()->json(null, 204); // No Content
    }
}
