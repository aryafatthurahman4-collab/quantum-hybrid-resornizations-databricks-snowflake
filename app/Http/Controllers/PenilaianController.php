<?php
namespace App\Http\Controllers;

use App\Models\PenilaianKinerja;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $penilaian = PenilaianKinerja::with(['karyawan', 'penilai']);
        if ($user->isKaryawan()) {
            $penilaian->where('karyawan_id', $user->karyawan_id);
        } elseif ($user->isAtasan()) {
            $penilaian->where('penilai_id', $user->id);
        }
        $penilaian = $penilaian->latest()->paginate(10);
        return view('penilaian.index', compact('penilaian'));
    }

    public function create()
    {
        $karyawan = Karyawan::where('aktif', true)->get();
        return view('penilaian.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'periode' => 'required|max:20',
            'tanggal_penilaian' => 'required|date',
            'nilai_disiplin' => 'required|numeric|min:0|max:100',
            'nilai_kualitas' => 'required|numeric|min:0|max:100',
            'nilai_kuantitas' => 'required|numeric|min:0|max:100',
            'nilai_tanggung_jawab' => 'required|numeric|min:0|max:100',
            'nilai_kerjasama' => 'required|numeric|min:0|max:100',
            'nilai_inisiatif' => 'required|numeric|min:0|max:100',
            'nilai_ketepatan_waktu' => 'required|numeric|min:0|max:100',
            'nilai_target' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable',
        ]);

        $validated['penilai_id'] = Auth::id();
        $nilai = collect($validated)->only([
            'nilai_disiplin', 'nilai_kualitas', 'nilai_kuantitas',
            'nilai_tanggung_jawab', 'nilai_kerjasama', 'nilai_inisiatif',
            'nilai_ketepatan_waktu', 'nilai_target'
        ]);
        $validated['nilai_akhir'] = round($nilai->sum() / $nilai->count(), 2);

        PenilaianKinerja::create($validated);
        return redirect()->route('penilaian.index')->with('success', 'Penilaian kinerja berhasil disimpan.');
    }

    public function show(PenilaianKinerja $penilaian)
    {
        $penilaian->load(['karyawan', 'penilai']);
        return view('penilaian.show', compact('penilaian'));
    }
}
