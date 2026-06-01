<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function status()
    {
        $activeShift = Shift::where('status', 'open')->first();

        if ($activeShift) {
            return response()->json([
                'status' => 'open',
                'initial_cash' => $activeShift->initial_cash,
                'open_time' => $activeShift->open_time->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'status' => 'closed',
            'initial_cash' => 0,
            'open_time' => null,
        ]);
    }

    public function open(Request $request)
    {
        $request->validate([
            'initial_cash' => 'required|numeric',
        ]);

        // Close any existing open shifts first to be safe
        Shift::where('status', 'open')->update([
            'status' => 'closed',
            'close_time' => Carbon::now(),
        ]);

        Shift::create([
            'status' => 'open',
            'initial_cash' => $request->initial_cash,
            'open_time' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shift opened',
        ]);
    }

    public function close()
    {
        $activeShift = Shift::where('status', 'open')->first();

        if ($activeShift) {
            $activeShift->update([
                'status' => 'closed',
                'close_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift closed',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No active shift found',
        ], 404);
    }
}
