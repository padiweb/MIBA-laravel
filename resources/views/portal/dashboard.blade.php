@extends('portal.layout')
@section('content')

{{-- Greeting card -- mobile hanya tampil di mobile, desktop tersembunyi --}}
<div style="background:linear-gradient(135deg,#2563eb,#1e40af);border-radius:20px;padding:20px;margin-bottom:16px;color:#fff;position:relative;overflow:hidden">
  <div style="position:absolute;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.07);top:-60px;right:-40px"></div>
  <div style="position:absolute;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.05);bottom:-30px;left:20px"></div>
  <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between">
    <div>
      <div style="font-size:13px;opacity:.8;margin-bottom:4px">Selamat datang 👋</div>
      <div style="font-size:18px;font-weight:700;margin-bottom:2px">{{ explode(' ', $student->student_full_name)[0] }}</div>
      <div style="font-size:11px;opacity:.7">{{ $student->class->class_name ?? '' }} &nbsp;·&nbsp; NIS {{ $student->student_nis }}</div>
    </div>
    @if($student->student_img)
      <img src="{{ asset('uploads/student/'.$student->student_img) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4)">
    @else
      <div style="width:56px;height:56px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;border:3px solid rgba(255,255,255,.3)">
        {{ strtoupper(substr($student->student_full_name, 0, 1)) }}
      </div>
    @endif
  </div>
  <div style="position:relative;z-index:1;margin-top:14px;display:flex;gap:10px">
    <div style="flex:1;background:rgba(255,255,255,.15);border-radius:12px;padding:10px 14px">
      <div style="font-size:11px;opacity:.75;margin-bottom:2px">Sisa Tagihan Bulanan</div>
      <div style="font-size:15px;font-weight:700">Rp {{ number_format($totalBulan,0,',','.') }}</div>
    </div>
    <div style="flex:1;background:rgba(255,255,255,.15);border-radius:12px;padding:10px 14px">
      <div style="font-size:11px;opacity:.75;margin-bottom:2px">Sisa Tagihan Lain</div>
      <div style="font-size:15px;font-weight:700">Rp {{ number_format($totalBebas-$totalBebasPay,0,',','.') }}</div>
    </div>
  </div>
</div>

{{-- Quick actions --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px">
  @php
    $actions = [
      ['route'=>'portal.payout','icon'=>'fa-money','label'=>'Tagihan','color'=>'#dbeafe','ic'=>'#2563eb'],
      ['route'=>'portal.profile','icon'=>'fa-user','label'=>'Profil','color'=>'#dcfce7','ic'=>'#16a34a'],
      ['route'=>'portal.profile.edit','icon'=>'fa-edit','label'=>'Edit Profil','color'=>'#fef3c7','ic'=>'#d97706'],
      ['route'=>'portal.profile.cpw','icon'=>'fa-key','label'=>'Password','color'=>'#fce7f3','ic'=>'#db2777'],
    ];
  @endphp
  @foreach($actions as $act)
  <a href="{{ route($act['route']) }}" style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;background:#fff;border-radius:16px;border:1px solid #e2e8f0;text-decoration:none;transition:transform .2s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
    <div style="width:42px;height:42px;border-radius:12px;background:{{ $act['color'] }};display:flex;align-items:center;justify-content:center">
      <i class="fa {{ $act['icon'] }}" style="font-size:18px;color:{{ $act['ic'] }}"></i>
    </div>
    <span style="font-size:10px;font-weight:600;color:#475569;text-align:center;line-height:1.2">{{ $act['label'] }}</span>
  </a>
  @endforeach
</div>

{{-- Tagihan belum lunas --}}
@if($bulanUnpaid->count() > 0)
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title" style="color:#ef4444"><i class="fa fa-exclamation-circle" style="color:#ef4444"></i> Tagihan Belum Lunas</div>
    <a href="{{ route('portal.payout') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Lihat Semua</a>
  </div>
  <div class="miba-card-body p0">
    @foreach($bulanUnpaid->take(4) as $b)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border)">
      <div>
        <div style="font-weight:600;font-size:13px">{{ $b->payment->pos->pos_name ?? '' }}</div>
        <div style="font-size:11px;color:var(--text-muted)">{{ $b->month->month_name ?? '' }}</div>
      </div>
      <div style="text-align:right">
        <div style="font-weight:700;font-size:13px;color:#ef4444">Rp {{ number_format($b->bulan_bill,0,',','.') }}</div>
        <span style="font-size:10px;background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:99px;font-weight:600">Belum Lunas</span>
      </div>
    </div>
    @endforeach
    @if($bulanUnpaid->count() > 4)
    <div style="padding:10px 16px;text-align:center;font-size:12px;color:var(--text-muted)">
      +{{ $bulanUnpaid->count()-4 }} tagihan lainnya
    </div>
    @endif
  </div>
</div>
@else
<div class="miba-card">
  <div class="miba-card-body" style="text-align:center;padding:24px">
    <div style="font-size:32px;margin-bottom:8px">🎉</div>
    <div style="font-weight:600;color:#065f46">Semua Tagihan Lunas!</div>
    <div style="font-size:12px;color:var(--text-muted);margin-top:4px">Tidak ada tagihan yang perlu dibayar</div>
  </div>
</div>
@endif

{{-- Grid 2 kolom: Tagihan Bebas + Info --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
  {{-- Tagihan Bebas/Lainnya --}}
  <div class="miba-card" style="margin:0">
    <div class="miba-card-header" style="padding:12px 14px">
      <div class="miba-card-title" style="font-size:13px"><i class="fa fa-list-alt"></i> Tagihan Lain</div>
    </div>
    <div class="miba-card-body p0">
      @forelse($bebasList->take(3) as $row)
      @php $sisa=$row->bebas_bill-$row->bebas_total_pay; $lunas=$sisa<=0; @endphp
      <div style="padding:10px 14px;border-bottom:1px solid var(--border)">
        <div style="font-size:12px;font-weight:600;margin-bottom:2px">{{ $row->payment->pos->pos_name ?? '' }}</div>
        <div style="display:flex;align-items:center;justify-content:space-between">
          <span style="font-size:11px;color:var(--text-muted)">Rp {{ number_format($sisa,0,',','.') }}</span>
          <span style="font-size:9px;background:{{ $lunas?'#d1fae5':'#fee2e2' }};color:{{ $lunas?'#065f46':'#991b1b' }};padding:1px 7px;border-radius:99px;font-weight:600">
            {{ $lunas?'Lunas':'Belum' }}
          </span>
        </div>
      </div>
      @empty
      <div style="padding:16px;text-align:center;font-size:12px;color:var(--text-muted)">Tidak ada</div>
      @endforelse
    </div>
  </div>

  {{-- Informasi --}}
  <div class="miba-card" style="margin:0">
    <div class="miba-card-header" style="padding:12px 14px">
      <div class="miba-card-title" style="font-size:13px"><i class="fa fa-newspaper-o"></i> Informasi</div>
    </div>
    <div class="miba-card-body p0">
      @forelse($infos->take(3) as $info)
      <div style="padding:10px 14px;border-bottom:1px solid var(--border)">
        <div style="font-size:12px;font-weight:600;line-height:1.3;margin-bottom:2px">{{ \Illuminate\Support\Str::limit($info->information_title,30) }}</div>
        <div style="font-size:10px;color:var(--text-muted)">{{ \Carbon\Carbon::parse($info->information_input_date)->diffForHumans() }}</div>
      </div>
      @empty
      <div style="padding:16px;text-align:center;font-size:12px;color:var(--text-muted)">Tidak ada</div>
      @endforelse
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
// Responsive grid 2-col → 1-col di layar kecil
$(window).on('resize load', function(){
  if($(window).width() < 400) {
    $('div[style*="grid-template-columns:1fr 1fr"]').css({display:'flex','flex-direction':'column'});
    $('div[style*="grid-template-columns:repeat(4,1fr)"]').css({'grid-template-columns':'repeat(2,1fr)'});
  }
});
</script>
@endpush
