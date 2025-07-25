<?php

namespace App\Http\Controllers\Barbershop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BreakController extends Controller
{
    public function index($barberId)
    {
        $barber = DB::table('bs_barbers')->where('id', $barberId)->first();
        $breaks = DB::table('bs_breaks')->where('barber_id', $barberId)->get();
        
        return view('barbershop.breaks.index', compact('barber', 'breaks'));
    }

    public function create($barberId)
    {
        $barber = DB::table('bs_barbers')->where('id', $barberId)->first();
        return view('barbershop.breaks.create', compact('barber'));
    }

    public function store(Request $request, $barberId)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        DB::table('bs_breaks')->insert([
            'barber_id' => $barberId,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('barbershop.breaks.index', $barberId)->with('success', 'Break time added successfully.');
    }

    public function edit($barberId, $id)
    {
        $barber = DB::table('bs_barbers')->where('id', $barberId)->first();
        $break = DB::table('bs_breaks')->where('id', $id)->first();
        
        return view('barbershop.breaks.edit', compact('barber', 'break'));
    }

    public function update(Request $request, $barberId, $id)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        DB::table('bs_breaks')->where('id', $id)->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'updated_at' => now(),
        ]);

        return redirect()->route('barbershop.breaks.index', $barberId)->with('success', 'Break time updated successfully.');
    }

    public function destroy($barberId, $id)
    {
        DB::table('bs_breaks')->where('id', $id)->delete();
        return redirect()->route('barbershop.breaks.index', $barberId)->with('success', 'Break time deleted successfully.');
    }
}