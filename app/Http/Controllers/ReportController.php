<?php

namespace App\Http\Controllers;

use App\Report;
use App\Sparepart;
use App\Notification;
use App\User;
use Auth;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->hasRole(['manager', 'cashier']);

        $reports = Report::where('workshop_id', Auth::user()->workshop_id)->get();

        return view('pages.report.index_report', compact('reports'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_report(Request $request)
    {
        Auth::user()->hasRole(['cashier']);

        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
        ]);

        $check = Report::firstWhere([
            'sparepart_id' => $request->sparepart_id,
            'workshop_id'  => Auth::user()->workshop_id,
            'is_done'      => '0'
        ]);

        if($check) {
            session()->flash('toast', ['info', 'Suku cadang sudah pernah dilaporkan namun belum diproses']);

            return redirect('/sparepart');
        } else {
            $report = new Report();
            $report->reported_at = \Carbon\Carbon::now()->format('Y-m-d');
            $report->sparepart_id = $request->sparepart_id;
            $report->workshop_id = Auth::user()->workshop_id;

            if($report->save()) {
                session()->flash('toast', ['success', 'Suku cadang berhasil dilaporkan']);

                $sparepart = Sparepart::firstWhere('id', $request->sparepart_id);
                $notification = new Notification();
                $notification->message = 'Laporan Stok Rendah: '. $sparepart->name .'.';
                $notification->target_user_id = User::where([
                    'workshop_id' => Auth::user()->workshop_id,
                    'role' => 'manager'
                ])->first()->id;
                $notification->redirect = '/report';
                $notification->save();
            }
            else session()->flash('toast', ['error', 'Suku cadang gagal dilaporkan']);

            return redirect('/report');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update_report(Request $request, Report $report)
    {
        Auth::user()->hasRole(['manager']);

        $report->is_done = '1';

        if($report->save()) session()->flash('toast', ['success', 'Laporan berhasil diproses']);
        else session()->flash('toast', ['error', 'Laporan gagal diproses']);

        return redirect('/report');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function delete_report(Report $report)
    {
        Auth::user()->hasRole(['cashier']);

        if($report->delete()) session()->flash('toast', ['success', 'Laporan berhasil dihapus']);
        else session()->flash('toast', ['error', 'Laporan gagal dihapus']);

        return redirect('/report');
    }
}
