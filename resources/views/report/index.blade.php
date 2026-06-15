@extends('layouts.app')
@section('content')

{{-- Stat Cards --}}
<div class="miba-stat-grid" style="margin-bottom:20px">
  <div class="miba-stat">
    <div class="miba-stat-icon blue"><i class="fa fa-arrow-down"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($totalBulan,0,',','.') }}</div>
      <div class="miba-stat-label">Pemasukan SPP/Tagihan</div>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon green"><i class="fa fa-plus-circle"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($totalDebit,0,',','.') }}</div>
      <div class="miba-stat-label">Pemasukan Lain</div>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon red"><i class="fa fa-arrow-up"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($totalKredit,0,',','.') }}</div>
      <div class="miba-stat-label">Total Pengeluaran</div>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon {{ $saldo >= 0 ? 'teal' : 'red' }}"><i class="fa fa-balance-scale"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px;color:{{ $saldo >= 0 ? 'var(--primary)' : 'var(--danger)' }}">
        Rp {{ number_format(abs($saldo),0,',','.') }}
      </div>
      <div class="miba-stat-label">{{ $saldo >= 0 ? 'Saldo Bersih' : 'Defisit' }}</div>
    </div>
  </div>
</div>

<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-bar-chart"></i> Laporan Total Keuangan</div>
    <a href="{{ route('report.exportKeuangan', request()->query()) }}" class="btn-miba btn-miba-sm btn-success-miba">
      <i class="fa fa-file-excel-o"></i> Export Excel
    </a>
  </div>
  <div class="miba-filter-bar">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
      <div class="miba-input-icon">
        <i class="fa fa-calendar icon"></i>
        <input type="text" name="ds" class="miba-input date-pick" value="{{ request('ds') }}" placeholder="Dari tanggal" style="width:160px">
      </div>
      <span style="color:var(--text-muted);font-size:13px">s/d</span>
      <div class="miba-input-icon">
        <i class="fa fa-calendar icon"></i>
        <input type="text" name="de" class="miba-input date-pick" value="{{ request('de') }}" placeholder="Sampai tanggal" style="width:160px">
      </div>
      <button class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-filter"></i> Filter</button>
      <a href="{{ route('report.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Reset</a>
    </form>
  </div>
  <div class="miba-card-body" style="border-bottom:1px solid var(--border)">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
      <div>
        <div style="font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:8px">RINGKASAN KEUANGAN</div>
        <table class="miba-table">
          <tr><td style="font-weight:500">Total Pemasukan SPP</td><td style="text-align:right;font-weight:600;color:var(--success)">Rp {{ number_format($totalBulan,0,',','.') }}</td></tr>
          <tr><td style="font-weight:500">Pemasukan Bebas/Lainnya</td><td style="text-align:right;font-weight:600;color:var(--success)">Rp {{ number_format($totalBebas,0,',','.') }}</td></tr>
          <tr><td style="font-weight:500">Pemasukan Lain-lain</td><td style="text-align:right;font-weight:600;color:var(--success)">Rp {{ number_format($totalDebit,0,',','.') }}</td></tr>
          <tr style="border-top:2px solid var(--border-dark)"><td style="font-weight:700">Total Pemasukan</td><td style="text-align:right;font-weight:700;color:var(--success)">Rp {{ number_format($pemasukan,0,',','.') }}</td></tr>
          <tr><td style="font-weight:500">Total Pengeluaran</td><td style="text-align:right;font-weight:600;color:var(--danger)">Rp {{ number_format($totalKredit,0,',','.') }}</td></tr>
          <tr style="border-top:2px solid var(--border-dark)">
            <td style="font-weight:700">Saldo Bersih</td>
            <td style="text-align:right;font-weight:800;font-size:15px;color:{{ $saldo>=0?'var(--primary)':'var(--danger)' }}">
              Rp {{ number_format(abs($saldo),0,',','.') }}
            </td>
          </tr>
        </table>
      </div>
      <div>
        <div style="font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:8px">PERSENTASE</div>
        @php $total = $pemasukan + $totalKredit; @endphp
        @if($total > 0)
        <div style="margin-bottom:12px">
          <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
            <span>Pemasukan</span>
            <span style="font-weight:600;color:var(--success)">{{ round($pemasukan/$total*100) }}%</span>
          </div>
          <div style="height:8px;background:var(--border);border-radius:99px;overflow:hidden">
            <div style="width:{{ round($pemasukan/$total*100) }}%;height:100%;background:var(--success);border-radius:99px"></div>
          </div>
        </div>
        <div>
          <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
            <span>Pengeluaran</span>
            <span style="font-weight:600;color:var(--danger)">{{ round($totalKredit/$total*100) }}%</span>
          </div>
          <div style="height:8px;background:var(--border);border-radius:99px;overflow:hidden">
            <div style="width:{{ round($totalKredit/$total*100) }}%;height:100%;background:var(--danger);border-radius:99px"></div>
          </div>
        </div>
        @else
        <div style="color:var(--text-muted);font-size:13px">Belum ada data transaksi</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
