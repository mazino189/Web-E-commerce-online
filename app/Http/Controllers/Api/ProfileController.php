<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            // Upload to Cloudinary, store in 'avatars' folder
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'demo'),
                    'api_key'    => env('CLOUDINARY_API_KEY', 'demo'),
                    'api_secret' => env('CLOUDINARY_API_SECRET', 'demo'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            $uploadApi = new UploadApi();
            $result = $uploadApi->upload($request->file('avatar')->getRealPath(), [
                'folder' => 'avatars',
                'transformation' => [
                    'width' => 400,
                    'height' => 400,
                    'crop' => 'fill',
                    'gravity' => 'auto'
                ]
            ]);

            $uploadedFileUrl = $result['secure_url'];

            $user->update([
                'avatar' => $uploadedFileUrl
            ]);

            return response()->json([
                'message' => 'Avatar updated successfully',
                'avatar' => $uploadedFileUrl,
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}
