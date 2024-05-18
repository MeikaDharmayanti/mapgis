<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalController extends Controller
{
    public function index()
{
    $hospitals = Hospital::all();
    return view('welcome', compact('hospitals'));
}

public function destroy($id)
{
    try {
        $hospital = Hospital::findOrFail($id);
        $hospital->delete();
        return response()->json(['success' => 'Hospital deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete hospital: ' . $e->getMessage()], 500);
    }
}

    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'address' => 'required',
            ]);

            // Simpan data rumah sakit 
            $hospital = new Hospital();
            $hospital->name = $validatedData['name'];
            $hospital->latitude = $validatedData['latitude'];
            $hospital->longitude = $validatedData['longitude'];
            $hospital->address = $validatedData['address'];
            $hospital->save();

            return redirect('/hospitals')->with('success', 'Hospital data saved successfully!');
        } catch (\Exception $e) {
            return redirect('/hospitals')->with('error', 'Failed to save hospital data: ' . $e->getMessage());
        }
    }
}
