@extends('portal.layout')
@section('content')

{{-- ── Hero Card ── --}}
<div class="p-hero">
  <div class="p-hero-top">
    <div>
      <div class="p-hero-greeting">Assalamu'alaikum 👋</div>
      <div class="p-hero-name">{{ explode(' ', $student->student_full_name)[0] }}</div>
      <div class="p-hero-sub">{{ $student->class->class_name ?? '' }} · NIS {{ $student->student_nis }}</div>
    </div>
    <div class="p-hero-avatar">
      @if($student->student_img)
        <img src="{{ asset('uploads/student/'.$student->student_img) }}" alt="Foto">
      @else
        {{ strtoupper(substr($student->student_full_name, 0, 1)) }}
      @endif
    </div>
  </div>
  <div class="p-hero-stats">
    <div class="p-hero-stat">
      <div class="p-hero-stat-label">Sisa Tagihan Bulanan</div>
      <div class="p-hero-stat-value">Rp {{ number_format($totalBulan, 0, ',', '.') }}</div>
    </div>
    <div class="p-hero-stat">
      <div class="p-hero-stat-label">Tagihan Lainnya</div>
      <div class="p-hero-stat-value">Rp {{ number_format($totalBebas - $totalBebasPay, 0, ',', '.') }}</div>
    </div>
  </div>
</div>

{{-- ── Quick Actions ── --}}
<div class="p-actions">
  @php
    $acts = [
      ['route'=>'portal.payout',       'icon'=>'fa-money',      'color'=>'#dbeafe','ic'=>'#2563eb','label'=>'Tagihan'],
      ['route'=>'portal.profile',      'icon'=>'fa-user',       'color'=>'#dcfce7','ic'=>'#16a34a','label'=>'Profil'],
      ['route'=>'portal.profile.edit', 'icon'=>'fa-edit',       'color'=>'#fef3c7','ic'=>'#d97706','label'=>'Edit Data'],
      ['route'=>'portal.profile.cpw',  'icon'=>'fa-lock',       'color'=>'#f3e8ff','ic'=>'#9333ea','label'=>'Password'],
    ];
  @endphp
  @foreach($acts as $a)
  <a href="{{ route($a['route']) }}" class="p-action-btn">
    <div class="p-action-icon" style="background:{{ $a['color'] }}">
      <i class="fa {{ $a['icon'] }}" style="color:{{ $a['ic'] }}"></i>
    </div>
    <span class="p-action-label">{{ $a['label'] }}</span>
  </a>
  @endforeach
</div>

{{-- ── Tagihan Belum Lunas ── --}}
<div class="p-card">
  <div class="p-card-header">
    <div class="p-card-title" style="color:var(--p-danger)">
      <i class="fa fa-exclamation-circle" style="color:var(--p-danger)"></i>
      Tagihan Belum Lunas
    </div>
    <a href="{{ route('portal.payout') }}" style="font-size:12px;color:var(--p-brand);font-weight:600;text-decoration:none">Lihat Semua</a>
  </div>
  @forelse($bulanUnpaid->take(5) as $b)
  <div class="p-bill-item">
    <div class="p-bill-icon danger"><i class="fa fa-calendar"></i></div>
    <div class="p-bill-info">
      <div class="p-bill-name">{{ $b->payment->pos->pos_name ?? '' }}</div>
      <div class="p-bill-sub">{{ $b->month->month_name ?? '' }}</div>
    </div>
    <div>
      <div class="p-bill-amount" style="color:var(--p-danger)">Rp {{ number_format($b->bulan_bill, 0, ',', '.') }}</div>
      <div style="text-align:right;margin-top:2px"><span class="p-badge p-badge-danger">Belum</span></div>
    </div>
  </div>
  @empty
  <div style="padding:var(--p-space-5);text-align:center">
    <div style="font-size:32px;margin-bottom:8px">🎉</div>
    <div style="font-weight:700;color:var(--p-success)">Semua Tagihan Lunas!</div>
    <div style="font-size:12px;color:var(--p-text-muted);margin-top:4px">Tidak ada tagihan yang perlu dibayar</div>
  </div>
  @endforelse
  @if($bulanUnpaid->count() > 5)
  <div style="padding:10px 16px;text-align:center;font-size:12px;color:var(--p-text-muted);border-top:1px solid var(--p-border)">
    +{{ $bulanUnpaid->count()-5 }} tagihan lainnya
  </div>
  @endif
</div>

{{-- ── Info & Agenda ── --}}
@if($infos->count() || $holidays->count())
<div class="p-card">
  <div class="p-card-header">
    <div class="p-card-title"><i class="fa fa-newspaper-o"></i> Informasi</div>
  </div>
  @forelse($infos->take(3) as $info)
  <div class="p-info-item">
    <div class="p-info-title">{{ $info->information_title }}</div>
    <div class="p-info-meta">{{ \Carbon\Carbon::parse($info->information_input_date)->diffForHumans() }}</div>
  </div>
  @empty
  <div style="padding:16px;text-align:center;color:var(--p-text-muted);font-size:13px">Belum ada informasi</div>
  @endforelse
</div>
@endif

@endsection
