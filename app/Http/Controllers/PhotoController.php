<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Generate unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Upload to R2
            $path = $file->storeAs('photos', $filename, 'r2');

            // Get public URL
            $url = Storage::disk('r2')->url($path);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $url,
                'message' => 'Photo uploaded successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ], 400);
    }

    public function delete($filename)
    {
        $path = 'photos/' . $filename;

        if (Storage::disk('r2')->exists($path)) {
            Storage::disk('r2')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Photo not found'
        ], 404);
    }
}
