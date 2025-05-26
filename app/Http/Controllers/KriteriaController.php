<?php

namespace App\Http\Controllers;

use App\Models\SubKriteria;
use Illuminate\Http\Request;
use App\Models\Kriteria; // Import the Kriteria model

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        // Fetch Kriteria and Sub Kriteria data from the database
        $query = $request->input('search');
        $kriteria = Kriteria::with('subKriteria')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%'); // Adjust 'name' to the appropriate column
            })
            ->get();
            $isLogin = session()->has('isLogin') ? true : false;

        return view('program/kriteria/kriteria', compact('kriteria', 'isLogin'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'sub_kriteria' => 'required|array',
            'sub_kriteria.*.nama_sub_kriteria' => 'required|string|max:255',
            'sub_kriteria.*.nilai_sub_kriteria' => 'required|numeric|min:0|max:1',
        ]);

        // Create Kriteria
        $kriteria = Kriteria::create([
            'nama_kriteria' => $validatedData['nama_kriteria'],
            'bobot' => $validatedData['bobot'],
        ]);
        // Save Sub Kriteria data into its own table
        foreach ($validatedData['sub_kriteria'] as $sub) {
            SubKriteria::create([
                'id_kriteria' => $kriteria->id_kriteria,
                'nama_sub_kriteria' => $sub['nama_sub_kriteria'],
                'nilai_sub_kriteria' => $sub['nilai_sub_kriteria'],
            ]);
        }

        return redirect()->route('kriteria')->with('success', 'Kriteria dan Sub Kriteria berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'sub_kriteria' => 'required|array',
            'sub_kriteria.*.nama_sub_kriteria' => 'required|string|max:255',
            'sub_kriteria.*.nilai_sub_kriteria' => 'required|numeric|min:0|max:1',
        ]);

        // Update Kriteria
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update([
            'nama_kriteria' => $validatedData['nama_kriteria'],
            'bobot' => $validatedData['bobot'],
        ]);

        // Delete all existing sub-kriteria
        SubKriteria::where('id_kriteria', $kriteria->id_kriteria)->delete();

        // Add new sub-kriteria
        foreach ($validatedData['sub_kriteria'] as $sub) {
            SubKriteria::create([
                'id_kriteria' => $kriteria->id_kriteria,
                'nama_sub_kriteria' => $sub['nama_sub_kriteria'],
                'nilai_sub_kriteria' => $sub['nilai_sub_kriteria'],
            ]);
        }

        return redirect()->route('kriteria')->with('success', 'Kriteria dan Sub Kriteria berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);

        // Delete related Sub Kriteria
        SubKriteria::where('id_kriteria', $kriteria->id_kriteria)->delete();

        // Delete Kriteria
        $kriteria->delete();

        return redirect()->route('kriteria')->with('success', 'Kriteria dan Sub Kriteria berhasil dihapus.');
    }
}
