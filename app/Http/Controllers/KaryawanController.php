<?php
namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\SatuanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::with(['jabatan', 'satuanKerja'])->latest()->paginate(10);
        return view('karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $jabatan = Jabatan::all();
        $units = SatuanKerja::all();
        $satuanKerja = $units;
        return view('karyawan.create', compact('jabatan', 'units', 'satuanKerja'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:karyawan,nip|max:30',
            'nama_lengkap' => 'required|max:150',
            'tempat_lahir' => 'nullable|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'agama' => 'nullable|max:20',
            'pendidikan_terakhir' => 'nullable|max:50',
            'status_perkawinan' => 'nullable|max:20',
            'tanggal_masuk' => 'required|date',
            'status_kepegawaian' => 'required|in:tetap,kontrak,magang,honorer',
            'jabatan_id' => 'required|exists:jabatan,id',
            'satuan_kerja_id' => 'required|exists:satuan_kerja,id',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        $validated['aktif'] = true;
        Karyawan::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['jabatan', 'satuanKerja', 'absensi' => fn($q) => $q->latest()->take(30)]);
        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $jabatan = Jabatan::all();
        $units = SatuanKerja::all();
        $satuanKerja = $units;
        return view('karyawan.edit', compact('karyawan', 'jabatan', 'units', 'satuanKerja'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'nip' => 'required|max:30|unique:karyawan,nip,' . $karyawan->id,
            'nama_lengkap' => 'required|max:150',
            'tempat_lahir' => 'nullable|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'agama' => 'nullable|max:20',
            'pendidikan_terakhir' => 'nullable|max:50',
            'status_perkawinan' => 'nullable|max:20',
            'tanggal_masuk' => 'required|date',
            'status_kepegawaian' => 'required|in:tetap,kontrak,magang,honorer',
            'jabatan_id' => 'required|exists:jabatan,id',
            'satuan_kerja_id' => 'required|exists:satuan_kerja,id',
            'foto' => 'nullable|image|max:2048',
            'aktif' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($karyawan->foto) Storage::disk('public')->delete($karyawan->foto);
            $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        $karyawan->update($validated);
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto) Storage::disk('public')->delete($karyawan->foto);
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
