<?php

namespace App\Http\Controllers\Rekap;

use App\Helpers\AuthDivisi;
use App\Http\Controllers\Controller;
use App\Http\Services\LogAbsensiService;
use App\Models\Karyawan;
use App\Models\LogAbsensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class LogAbsensiController extends Controller
{
    public function __construct(
        protected LogAbsensiService $log_absensi_service
    ) {}

    public function index()
    {
        $data["log_absensi"] = $this->log_absensi_service->list();

        return view("pages.modules.rekap.absensi.index", $data);
    }

    public function create()
    {
        return view("pages.modules.rekap.absensi.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        $canLibre = $this->canUseLibreOffice();

        if (!$canLibre && $ext !== 'csv') {
            return back()->with(
                'error',
                'Server ini hanya mendukung file CSV. Silakan convert XLS ke CSV terlebih dahulu.'
            );
        }

        $tempDir = null;

        if ($ext === 'csv') {
            $csvPath = $file->getPathname();
        } else {
            $soffice = $this->getLibreOfficePath();

            if (!$soffice) {
                return back()->with('error', 'LibreOffice tidak tersedia di server');
            }

            $tempDir = sys_get_temp_dir() . '/fp_' . uniqid();
            mkdir($tempDir, 0777, true);

            $uploadedPath = $file->getPathname();

            $cmd = "\"{$soffice}\" --headless --nologo --nolockcheck --convert-to csv \"{$uploadedPath}\" --outdir \"{$tempDir}\"";
            exec($cmd, $out, $exitCode);

            if ($exitCode !== 0) {
                return back()->with('error', 'Gagal convert file XLS');
            }

            $csvFiles = glob($tempDir . '/*.csv');
            if (!$csvFiles) {
                return back()->with('error', 'CSV hasil convert tidak ditemukan');
            }

            $csvPath = $csvFiles[0];
        }

        $lines = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (count($lines) < 2) {
            return back()->with('error', 'CSV kosong');
        }

        $delimiter = str_contains($lines[0], ';') ? ';' : ',';
        $rows = array_map(fn($l) => str_getcsv($l, $delimiter), $lines);

        $header = $rows[0];

        $departemenIndex = null;
        foreach ($header as $i => $col) {
            if (str_contains(strtolower($col), 'departemen')) {
                $departemenIndex = $i;
                break;
            }
        }

        if ($departemenIndex === null) {
            return back()->with('error', 'Kolom Departemen tidak ditemukan di file');
        }

        $divisiUser = strtoupper(
            trim(Auth::user()->one_divisi_roles->divisi->nama_divisi ?? '')
        );

        if (!$divisiUser) {
            return back()->with('error', 'Divisi akun tidak valid');
        }

        $departemenFile = collect($rows)
            ->slice(1)
            ->map(fn($r) => strtoupper(trim($r[$departemenIndex] ?? '')))
            ->filter()
            ->unique();

        if ($departemenFile->count() !== 1) {
            return back()->with(
                'error',
                'File mengandung lebih dari satu Departemen'
            );
        }

        if ($departemenFile->first() !== $divisiUser) {
            return back()->with(
                'error',
                "Departemen file ({$departemenFile->first()}) tidak sesuai dengan divisi akun ({$divisiUser})"
            );
        }

        $data = array_slice($rows, 1);

        $karyawanMap = Karyawan::pluck('id_fp')
            ->map(fn($id) => $this->extractIdFp($id))
            ->filter()
            ->flip();

        $existingAbsensi = LogAbsensi::where('divisi_id', AuthDivisi::id())
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->id_fp . '|' . $item->tanggal_waktu => true
                ];
            });

        DB::beginTransaction();

        try {
            $inserted = 0;
            $skipped  = 0;

            foreach ($data as $row) {

                $idFp = null;
                foreach ($row as $cell) {
                    $idFp = $this->extractIdFp($cell);
                    if ($idFp !== null) break;
                }

                if (!$idFp || !isset($karyawanMap[$idFp])) {
                    $skipped++;
                    continue;
                }

                $rawTanggal = null;
                for ($i = 0; $i < count($row); $i++) {
                    if (preg_match('/^\d{2}\/\d{2}\/\d{2}$/', trim($row[$i] ?? ''))) {
                        $rawTanggal = trim($row[$i]) . ' ' . trim($row[$i + 1] ?? '');
                        break;
                    }

                    if (preg_match('/\d{2}-[A-Za-z]{3}-\d{2}/', trim($row[$i] ?? ''))) {
                        $rawTanggal = trim($row[$i]);
                        break;
                    }
                }

                if (!$rawTanggal) {
                    $skipped++;
                    continue;
                }

                $tanggal = $this->parseTanggalFingerprint($rawTanggal);
                if (!$tanggal) {
                    $skipped++;
                    continue;
                }

                $key = $idFp . '|' . $tanggal;

                if (isset($existingAbsensi[$key])) {
                    $skipped++;
                    continue;
                }

                $kodeLokasi = isset($row[4])
                    ? (int) preg_replace('/[^0-9]/', '', $row[4])
                    : null;

                LogAbsensi::create([
                    'id'            => Str::uuid(),
                    'divisi_id'     => AuthDivisi::id(),
                    'id_fp'         => $idFp,
                    'tanggal_waktu' => $tanggal,
                    'kode_lokasi'   => $kodeLokasi ?: null,
                    'created_by'    => Auth::id(),
                    'updated_by'    => Auth::id(),
                ]);

                $existingAbsensi[$key] = true;
                $inserted++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        if ($tempDir) {
            @unlink($csvPath);
            @rmdir($tempDir);
        }

        return back()->with(
            'success',
            "Import selesai. Masuk: {$inserted}, Skip: {$skipped}"
        );
    }

    private function canUseLibreOffice(): bool
    {
        if (!function_exists('exec')) {
            return false;
        }

        return (bool) $this->getLibreOfficePath();
    }

    private function getLibreOfficePath(): ?string
    {
        $paths = [
            '/usr/bin/libreoffice',
            '/usr/bin/soffice',
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                exec("\"{$path}\" --version", $out, $code);
                if ($code === 0) {
                    return $path;
                }
            }
        }

        return null;
    }

    private function extractIdFp($value)
    {
        if (preg_match('/\d+/', (string) $value, $m)) {
            return ltrim($m[0], '0'); // 02 → 2
        }
        return null;
    }

    private function parseTanggalFingerprint($value)
    {
        $value = trim($value);

        if (preg_match('/\d{1,2}\.\d{1}$/', $value)) {
            $value = preg_replace_callback(
                '/(\d{1,2})\.(\d{1})$/',
                fn($m) =>
                str_pad($m[1], 2, '0', STR_PAD_LEFT) . '.' .
                    str_pad($m[2], 2, '0', STR_PAD_RIGHT),
                $value
            );
        }

        $formats = [
            'd-M-y h:i:s A',
            'd/m/y H.i',
            'd/m/y H:i',
        ];

        foreach ($formats as $f) {
            try {
                return Carbon::createFromFormat($f, $value);
            } catch (\Exception $e) {
            }
        }

        return null;
    }

    public function edit($id)
    {
        try {
            $data["edit"] = $this->log_absensi_service->edit($id);

            return view("pages.modules.rekap.absensi.edit", $data);
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->log_absensi_service->update($id, $request->all());

            return back()->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $e) {

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->log_absensi_service->delete($id);

            return back()
                ->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
