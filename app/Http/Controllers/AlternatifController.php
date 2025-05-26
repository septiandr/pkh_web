<?php
namespace App\Http\Controllers;

use App\Helpers\AlternatifHelper;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AlternatifController extends Controller
{

    function hitungBobot($x, $min, $max)
    {
        if ($max == $min)
            return 0;
        return round(($max - $x) / ($max - $min), 2);
    }

    public function index(Request $request)
    {

        $isLogin = session()->has('isLogin') ? true : false;
        $search = $request->input('search');
        $alternatif = Alternatif::with([
            'penilaians.kriteria',
            'penilaians.subKriteria',
        ])->when($search, function ($query, $search) {
            return $query->where('nama_lengkap', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%');
        })->get()->map(function ($alt) {
            return [
                'id_alternatif' => $alt->id_alternatif,
                'nik' => $alt->nik,
                'nama_lengkap' => $alt->nama_lengkap,
                'alamat' => $alt->alamat,
                'dokumen' => $alt->dokumen,
                'pendapatan' => $alt->pendapatan,
                'eligible' => $alt->eligible,
                'luas_tanah' => $alt->luas_tanah,
                'penilaian' => $alt->penilaians->map(function ($penilaian) {
                    return [
                        'kriteria' => $penilaian->kriteria->nama_kriteria ?? null,
                        'sub_kriteria' => [
                            'nama_sub_kriteria' => $penilaian->subKriteria->nama_sub_kriteria ?? null,
                            'nilai_sub_kriteria' => $penilaian->subKriteria->nilai_sub_kriteria ?? null,
                        ],
                        'nilai' => $penilaian->subKriteria->nilai_sub_kriteria ?? null,
                    ];
                })->concat([
                            [
                                'kriteria' => 'Pendapatan',
                                'sub_kriteria' => [
                                    'nama_sub_kriteria' => $alt->pendapatan,
                                    'nilai_sub_kriteria' => $this->hitungBobot($alt->pendapatan, 600000, 1000000),
                                ],
                                'nilai' => $alt->pendapatan,
                            ],
                            [
                                'kriteria' => 'Luas Tanah',
                                'sub_kriteria' => [
                                    'nama_sub_kriteria' => $alt->luas_tanah,
                                    'nilai_sub_kriteria' => $this->hitungBobot($alt->luas_tanah, 8, 50),
                                ],
                                'nilai' => $alt->luas_tanah,
                            ],
                        ]),
            ];
        });
        return view('/program/alternatif/alternatif', ['alternatif' => $alternatif, 'isLogin' => $isLogin]);
    }

    public function destroy($id)
    {
        $alternatif = Alternatif::findOrFail($id);

        // Delete associated Penilaian records
        Penilaian::where('id_alternatif', $id)->delete();

        // Optionally delete associated files if needed
        if ($alternatif->dokumen) {
            Storage::delete('public/' . $alternatif->dokumen);
        }

        // Delete the Alternatif record
        $alternatif->delete();

        return response()->json(['success' => true, 'message' => 'Alternatif accepted successfully.']);
    }

    public function accept($id)
    {
        // Find the alternatif by ID
        $alternatif = Alternatif::findOrFail($id);

        // Update the status and eligible fields
        $alternatif->eligible = true;
        $alternatif->save();

        return response()->json(['success' => true, 'message' => 'Alternatif accepted successfully.']);
    }

    public function reject($id)
    {
        // Find the alternatif by ID
        $alternatif = Alternatif::findOrFail($id);

        // Update the status and eligible fields
        $alternatif->eligible = false;
        $alternatif->save();

        return response()->json(['success' => true, 'message' => 'Alternatif rejected successfully.']);
    }

    public function create()
    {
        $kriteria = Kriteria::with('subKriteria')->get();

        $fields = [
            ['name' => 'nik', 'type' => 'text','required' => true, 'label' => 'NIK'],
            ['name' => 'nama_lengkap', 'type' => 'text','required' => true, 'label' => 'Nama Lengkap'],
            ['name' => 'alamat', 'type' => 'textarea','required' => true, 'label' => 'Alamat'],
            ['name' => 'pendapatan', 'type' => 'number','required' => true, 'label' => 'Pendapatan (Rp)', 'min' => 0],
            ['name' => 'luas_tanah', 'type' => 'number','required' => true, 'label' => 'Luas Tanah (m²)', 'min' => 0],
        ];

        // Mapping kriteria ke fields
        foreach ($kriteria as $item) {
            if ($item->nama_kriteria == 'Pendapatan' || $item->nama_kriteria == 'Luas tanah') {
                continue;
            }
            $fields[] = [
                'name' => $item->nama_kriteria,
                'type' => 'multiselect',
                'label' => $item->nama_kriteria,
                'options' => $item->subKriteria ? $item->subKriteria->pluck('nama_sub_kriteria', 'id_sub_kriteria')->toArray() : [],
                'required' => true,
            ];
            $data = [
                'title' => 'Tambah Data Alternatif',
                'action' => route('alternatif.store'),
                'method' => 'POST',
                'buttonText' => 'Simpan',
            ];
        }
        return view('program.alternatif.form', compact('fields', 'data'));
    }
    public function edit($id)
    {
        $kriteria = Kriteria::with('subKriteria')->get();
        $alternatif = Alternatif::findOrFail($id); // Fetch the specific alternatif by ID

        $fields = [
            ['name' => 'nik', 'type' => 'text', 'label' => 'NIK', 'required' => true, 'value' => $alternatif->nik],
            ['name' => 'nama_lengkap', 'type' => 'text', 'label' => 'Nama Lengkap','required' => true, 'value' => $alternatif->nama_lengkap],
            ['name' => 'alamat', 'type' => 'textarea', 'label' => 'Alamat','required' => true, 'value' => $alternatif->alamat],
            ['name' => 'pendapatan', 'type' => 'number', 'label' => 'Pendapatan (Rp)','required' => true, 'min' => 0, 'value' => $alternatif->pendapatan],
            ['name' => 'luas_tanah', 'type' => 'number', 'label' => 'Luas Tanah (m²)','required' => true, 'min' => 0, 'value' => $alternatif->luas_tanah],
        ];

        // Mapping kriteria ke fields
        foreach ($kriteria as $item) {
            if ($item->nama_kriteria == 'Pendapatan' || $item->nama_kriteria == 'Luas tanah') {
                continue;
            }
            $fields[] = [
                'name' => $item->nama_kriteria,
                'type' => 'multiselect',
                'label' => $item->nama_kriteria,
                'options' => $item->subKriteria ? $item->subKriteria->pluck('nama_sub_kriteria', 'id_sub_kriteria')->toArray() : [],
                'value' => $alternatif->penilaians->where('id_kriteria', $item->id_kriteria)->pluck('id_sub_kriteria')->toArray(),
                'required' => true,
            ];
        }

        // Add dokumen_preview field at the bottom
        $fields[] = [
            'name' => 'dokumen_preview',
            'type' => 'file',
            'label' => 'Surat Pengantar RT',
            'value' => $alternatif->dokumen,
            // 'preview_url' => route('alternatif.viewDokumen', ['filename' => $alternatif->dokumen]),
        ];

        $data = [
            'title' => 'Edit Data Alternatif',
            'action' => route('alternatif.update', ['id' => $id]),
            'method' => 'PUT',
            'buttonText' => 'Ubah',
        ];
        return view('program.alternatif.form', compact('fields', 'data'));
    }

    public function update(Request $request, $id)
    {
        // Validation rules
        $request->validate([
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pendapatan' => 'required|numeric|min:0',
            'luas_tanah' => 'required|numeric|min:0',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Fetch the alternatif by ID
        $alternatif = Alternatif::findOrFail($id);

        // Separate Alternatif data
        $alternatifData = $request->only(['nik', 'nama_lengkap', 'alamat', 'pendapatan', 'luas_tanah']);

        // Handle file upload for 'dokumen'
        if ($request->hasFile('dokumen')) {
            // Delete the old file if it exists
            if ($alternatif->dokumen) {
                Storage::delete('public/' . $alternatif->dokumen);
            }

            $file = $request->file('dokumen');
            $filePath = $file->store('dokumen', 'public');
            $alternatifData['dokumen'] = str_replace('public/', '', $filePath);
        }

        // Update Alternatif data
        $alternatif->update($alternatifData);

        // Separate Penilaian data
        $penilaianData = $request->except(['nik', 'nama_lengkap', 'alamat', 'pendapatan', 'luas_tanah']);

        // Update or create Penilaian records
        foreach ($penilaianData as $key => $values) {
            // Convert key to match Kriteria's nama_kriteria (remove underscores)
            $namaKriteria = str_replace('_', ' ', $key);

            // Find the corresponding id_kriteria
            $kriteria = Kriteria::where('nama_kriteria', $namaKriteria)->first();

            if ($kriteria) {
                $id_kriteria = $kriteria->id_kriteria;

                // Update or create Penilaian records for each value
                foreach ($values as $id_sub_kriteria) {
                    Penilaian::updateOrCreate(
                        ['id_alternatif' => $id, 'id_kriteria' => $id_kriteria],
                        ['id_sub_kriteria' => $id_sub_kriteria]
                    );
                }
            }
        }

        return redirect()->route('alternatif.index')->with('success', 'Data alternatif berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pendapatan' => 'required|numeric|min:0',
            'luas_tanah' => 'required|numeric|min:0',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Separate Alternatif data
        $alternatifData = $request->only(['nik', 'nama_lengkap', 'alamat', 'pendapatan', 'luas_tanah']);

        // Handle file upload for 'dokumen'
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filePath = $file->store('dokumen', 'public');
            $alternatifData['dokumen'] = str_replace('public/', '', $filePath);
        }

        // Create a new Alternatif record
        $alternatif = Alternatif::create($alternatifData);

        // Separate Penilaian data
        $penilaianData = $request->except(['nik', 'nama_lengkap', 'alamat', 'pendapatan', 'luas_tanah']);

        // Create Penilaian records
        foreach ($penilaianData as $key => $values) {
            // Convert key to match Kriteria's nama_kriteria (remove underscores)
            $namaKriteria = str_replace('_', ' ', $key);

            // Find the corresponding id_kriteria
            $kriteria = Kriteria::where('nama_kriteria', $namaKriteria)->first();

            if ($kriteria) {
                $id_kriteria = $kriteria->id_kriteria;

                // Create Penilaian records for each value
                foreach ($values as $id_sub_kriteria) {
                    Penilaian::create([
                        'id_alternatif' => $alternatif->id_alternatif,
                        'id_kriteria' => $id_kriteria,
                        'id_sub_kriteria' => $id_sub_kriteria,
                    ]);
                }
            }
        }

        return redirect()->route('alternatif.index')->with('success', 'Data alternatif berhasil ditambahkan.');
    }

    public function viewDokumen($filename)
    {
        $filename = basename($filename);
        $path = storage_path('app/public/dokumen/' . $filename);
        dd($path);
        abort_unless(file_exists($path), 404, 'File not found.');

        return response()->file($path);
    }

}