@extends('portal.layout')
@section('content')
<div class="miba-stat-grid" style="grid-template-columns:1fr 1fr">
  <div class="miba-stat">
    <div class="miba-stat-icon blue"><i class="fa fa-dollar"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($totalBulan,0,',','.') }}</div>
      <div class="miba-stat-label">Sisa Tagihan Bulanan</div>
    </div>
  </div>
  <div class="miba-stat">
    <div class="miba-stat-icon red"><i class="fa fa-money"></i></div>
    <div class="miba-stat-body">
      <div class="miba-stat-value" style="font-size:16px">Rp {{ number_format($totalBebas-$totalBebasPay,0,',','.') }}</div>
      <div class="miba-stat-label">Sisa Tagihan Lainnya</div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
  <div class="miba-card" style="margin:0">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-user"></i> Identitas Santri</div></div>
    <div class="miba-card-body">
      <table class="miba-table">
        <tr><td style="width:150px;font-weight:500;color:var(--text-muted)">Tahun Pelajaran</td><td>{{ $period?$period->period_start.'/'.$period->period_end:'-' }}</td></tr>
        <tr><td>NIM</td><td style="font-weight:700">{{ $student->student_nis }}</td></tr>
        <tr><td>Nama</td><td>{{ $student->student_full_name }}</td></tr>
        <tr><td>Nama Ibu</td><td>{{ $student->student_name_of_mother ?: '-' }}</td></tr>
        <tr><td>Kelas</td><td>{{ $student->class->class_name??'-' }}</td></tr>
        @if(($app_level??'')=='senior')<tr><td>Unit Sekolah</td><td>{{ $student->majors->majors_name??'-' }}</td></tr>@endif
      </table>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:16px">
    <div class="miba-card" style="margin:0">
      <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-calendar"></i> Tagihan Bulanan Aktif</div><a href="{{ route('portal.payout') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Lihat Semua</a></div>
      <div class="miba-card-body p0">
        <table class="miba-table">
          @forelse($bulanUnpaid->take(5) as $b)
          <tr>
            <td style="font-weight:500">{{ $b->payment->pos->pos_name??'' }}</td>
            <td>{{ $b->month->month_name??'' }}</td>
            <td><span class="badge-miba badge-danger">Belum Lunas</span></td>
          </tr>
          @empty
          <tr><td colspan="3" style="text-align:center;padding:16px;color:var(--success)"><i class="fa fa-check-circle"></i> Semua tagihan lunas!</td></tr>
          @endforelse
        </table>
      </div>
    </div>

    <div class="miba-card" style="margin:0">
      <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-newspaper-o"></i> Informasi</div></div>
      <div class="miba-card-body p0">
        @forelse($infos->take(3) as $info)
        <div style="padding:10px 16px;border-bottom:1px solid var(--border)">
          <div style="font-weight:600;font-size:13px">{{ $info->information_title }}</div>
          <div style="font-size:11px;color:var(--text-muted)">{{ \Illuminate\Support\Str::limit(strip_tags($info->information_desc),60) }}</div>
        </div>
        @empty
        <div style="padding:16px;text-align:center;color:var(--text-muted)">Belum ada informasi</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>$(window).on('resize load',function(){if($(window).width()<640)$('div[style*="grid-template-columns:1fr 1fr"]').css({display:'flex','flex-direction':'column'});});</script>
@endpush