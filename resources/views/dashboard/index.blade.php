@extends('layouts.app')
@section('content')

{{-- Stat Grid --}}
<div class="miba-stat-grid">
  <div class="miba-stat">
    <div class="miba-stat-icon teal"><i class="fa fa-graduation-cap"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value">{{ $totalSiswa }}</div>
      <div class="miba-stat-label">Total Siswa Aktif</div>
      <a href="{{ route('student.index') }}" class="miba-stat-link">Lihat semua <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon green"><i class="fa fa-check-circle"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value">{{ $totalSiswaBayar }}</div>
      <div class="miba-stat-label">Siswa Sudah Bayar</div>
      <a href="{{ route('payout.index') }}" class="miba-stat-link">Lihat semua <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon amber"><i class="fa fa-money"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($pemasukanHariIni,0,',','.') }}</div>
      <div class="miba-stat-label">Pemasukan Hari Ini</div>
      <a href="{{ route('payout.index') }}" class="miba-stat-link">Lihat detail <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon blue"><i class="fa fa-arrow-down"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($totalDebit,0,',','.') }}</div>
      <div class="miba-stat-label">Pemasukan Bulan Ini</div>
      <a href="{{ route('debit.index') }}" class="miba-stat-link">Lihat detail <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon red"><i class="fa fa-arrow-up"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($totalKredit,0,',','.') }}</div>
      <div class="miba-stat-label">Pengeluaran Bulan Ini</div>
      <a href="{{ route('kredit.index') }}" class="miba-stat-link">Lihat detail <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon navy"><i class="fa fa-users"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value">{{ $totalUser }}</div>
      <div class="miba-stat-label">Total Pengguna</div>
      <a href="{{ route('users.index') }}" class="miba-stat-link">Kelola <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
</div>

<div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

  {{-- Transaksi Terakhir --}}
  <div class="miba-card" style="grid-column:1/2">
    <div class="miba-card-header">
      <div class="miba-card-title"><i class="fa fa-history"></i> Aktivitas Terbaru</div>
      <a href="{{ route('logs.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Lihat semua</a>
    </div>
    <div class="miba-card-body p0">
      <table class="miba-table">
        <thead>
          <tr><th>Waktu</th><th>Pengguna</th><th>Modul</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          @forelse($recentLogs as $log)
          <tr>
            <td style="white-space:nowrap;color:var(--text-muted)">{{ \Carbon\Carbon::parse($log->log_date)->format('d/m H:i') }}</td>
            <td>{{ $log->user->user_full_name ?? '-' }}</td>
            <td>{{ $log->log_module }}</td>
            <td>
              @php $ac = $log->log_action; @endphp
              <span class="badge-miba {{ $ac=='ADD'?'badge-success':($ac=='DELETE'?'badge-danger':($ac=='UPDATE'?'badge-info':'badge-muted')) }}">
                {{ $ac }}
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:24px">Belum ada aktivitas</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Informasi + Hari Libur --}}
  <div style="display:flex;flex-direction:column;gap:16px">
    <div class="miba-card">
      <div class="miba-card-header">
        <div class="miba-card-title"><i class="fa fa-newspaper-o"></i> Informasi</div>
        <a href="{{ route('information.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Kelola</a>
      </div>
      <div class="miba-card-body" style="padding:0">
        @forelse($infos as $info)
        <div style="padding:12px 16px;border-bottom:1px solid var(--border)">
          <div style="font-weight:600;font-size:13px;margin-bottom:2px">{{ $info->information_title }}</div>
          <div style="font-size:12px;color:var(--text-muted)">{{ \Illuminate\Support\Str::limit($info->information_desc,80) }}</div>
        </div>
        @empty
        <div style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px">Belum ada informasi</div>
        @endforelse
      </div>
    </div>
    <div class="miba-card">
      <div class="miba-card-header">
        <div class="miba-card-title"><i class="fa fa-calendar"></i> Agenda & Libur</div>
        <a href="{{ route('holiday.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Kelola</a>
      </div>
      <div class="miba-card-body" style="padding:0">
        @forelse($holidays as $h)
        <div style="padding:10px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px">
          <span class="badge-miba badge-danger">{{ \Carbon\Carbon::parse($h->date)->locale('id')->isoFormat('D MMM') }}</span>
          <span style="font-size:13px">{{ $h->info }}</span>
        </div>
        @empty
        <div style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px">Belum ada agenda</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
// Responsive dashboard grid mobile
$(window).on('resize load', function(){
  if($(window).width() < 768){
    $('.row[style*="grid"]').css({'display':'flex','flex-direction':'column'});
  } else {
    $('.row[style*="grid"]').css({'display':'grid'});
  }
});
</script>
@endpush
