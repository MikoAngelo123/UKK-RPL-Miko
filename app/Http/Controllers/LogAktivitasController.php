<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    /**
     * Tampilkan riwayat log aktivitas sistem.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $logs = LogAktivitas::with('user')
            ->when($search, function ($query, $search) {
                $query->where('aktivitas', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('log.index', compact('logs', 'search'));
    }
}
