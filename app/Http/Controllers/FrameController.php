<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrameController extends Controller
{
    public function index()
    {
        // Data ini sudah sesuai dengan folder assets\frames kamu
        $series = [
            'season' => [
                ['name' => 'Summer', 'file' => 'assets/frames/season/Summer.png'],
                ['name' => 'Tropical', 'file' => 'assets/frames/season/Tropical.png'],
                ['name' => 'Pumpkin', 'file' => 'assets/frames/season/Pumpkin.png'],
                ['name' => 'Night Christmas', 'file' => 'assets/frames/season/NightChristmas.png'],
            ],
            'art' => [
                ['name' => 'Graffiti Glow', 'file' => 'assets/frames/art/Free Frame Boothlab 5.png'],
                ['name' => 'Blue Art', 'file' => 'assets/frames/art/BlueArt.png'],
                ['name' => 'Urban Trim', 'file' => 'assets/frames/art/barbershop.png'],
                ['name' => 'Peach Royale', 'file' => 'assets/frames/art/clean.png'],
            ],
            'nature' => [
                ['name' => 'Electric Purple', 'file' => 'assets/frames/nature/Free Frame boothlab 3.png'],
                ['name' => 'Earthy Touch', 'file' => 'assets/frames/nature/last.png'],
            ],
            'lifestyle' => [
                ['name' => 'Nature Luxe', 'file' => 'assets/frames/lifestyle/leaf.png'],
                ['name' => 'Emerald Glam', 'file' => 'assets/frames/lifestyle/Glam.png'],
                ['name' => 'Golden Blush', 'file' => 'assets/frames/lifestyle/pink.png'],
                ['name' => 'Winter', 'file' => 'assets/frames/lifestyle/winter.png'],
            ]
        ];

        return view('frames.index', compact('series'));
    }

    public function create(Request $request)
    {
        // Mengambil path frame dari URL
        $frame = $request->query('frame');

        if (!$frame) {
            return redirect()->route('frames.index');
        }

        return view('frames.create', compact('frame'));
    }
}
