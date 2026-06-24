@extends('portal.layout')
@section('content')

{{-- Period Selector --}}
<div class="p-card">
  <div class="p-card-body">
    <div class="p-period-label">Tahun Pelajaran</div>
    <form method="GET" id="periodForm">
      <select name="n" class="p-select" onchange="document.getElementById('periodForm').submit()">
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ $periodId==$p->period_id ? 'selected' : '' }}>
            {{ $p->period_start }}/{{ $p->period_end }}
          </option>
        @endforeach
      </select>
    </form>
  </div>
</div>

{{-- Tabs --}}
<div class="p-tabs">
  <button class="p-tab-btn active" onclick="switchTab(this,'tab-bulanan')">
    <i class="fa fa-calendar"></i> Bulanan
  </button>
  <button class="p-tab-btn" onclick="switchTab(this,'tab-bebas')">
    <i class="fa fa-list"></i> Lain-lain
  </button>
</div>

{{-- Tab: Bulanan --}}
<div id="tab-bulanan" class="p-tab-pane active">
  @php
    $months = $bulans->groupBy(fn($b) => $b->payment->pos->pos_name ?? 'SPP');
  @endphp
  @if($months->count())
    @foreach($months as $payName => $rows)
    <div class="p-card">
      <div class="p-card-header">
        <div class="p-card-title"><i class="fa fa-dollar"></i> {{ $payName }}</div>
        @php
          $paid = $rows->where('bulan_status', 1)->count();
          $total = $rows->count();
        @endphp
        <span class="p-badge {{ $paid==$total ? 'p-badge-success' : 'p-badge-warning' }}">
          {{ $paid }}/{{ $total }} Lunas
        </span>
      </div>
      <div class="p-card-body p0">
        <div class="p-month-grid" style="padding:var(--p-space-3)">
          @foreach($rows->sortBy('month_month_id') as $b)
          <div class="p-month-cell {{ $b->bulan_status ? 'paid' : 'unpaid' }}">
            <div class="p-month-cell-name">{{ $b->month->month_name ?? '' }}</div>
            <div class="p-month-cell-status">
              @if($b->bulan_status)
                ✓ Lunas
              @else
                {{ number_format($b->bulan_bill, 0, ',', '.') }}
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endforeach
  @else
    <div class="p-card">
      <div class="p-card-body" style="text-align:center;padding:var(--p-space-8)">
        <div style="font-size:36px;margin-bottom:8px">📅</div>
        <div style="font-weight:600;color:var(--p-text-muted)">Tidak ada data tagihan bulanan</div>
      </div>
    </div>
  @endif
</div>

{{-- Tab: Bebas/Lainnya --}}
<div id="tab-bebas" class="p-tab-pane">
  @if($bebasList->count())
  <div class="p-card">
    <div class="p-card-body p0">
      @foreach($bebasList as $row)
      @php
        $sisa  = $row->bebas_bill - $row->bebas_total_pay;
        $lunas = $sisa <= 0;
        $pct   = $row->bebas_bill > 0 ? min(100, round($row->bebas_total_pay / $row->bebas_bill * 100)) : 0;
      @endphp
      <div class="p-bebas-item">
        <div class="p-bebas-row">
          <div class="p-bebas-name">{{ $row->payment->pos->pos_name ?? '-' }}</div>
          <span class="p-badge {{ $lunas ? 'p-badge-success' : 'p-badge-warning' }}">
            {{ $lunas ? 'Lunas' : number_format($sisa,0,',','.') }}
          </span>
        </div>
        <div class="p-progress">
          <div class="p-progress-bar {{ $lunas ? 'full' : '' }}" style="width:{{ $pct }}%"></div>
        </div>
        <div class="p-bebas-nums">
          <span>Dibayar: Rp {{ number_format($row->bebas_total_pay,0,',','.') }}</span>
          <span>Total: Rp {{ number_format($row->bebas_bill,0,',','.') }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @else
    <div class="p-card">
      <div class="p-card-body" style="text-align:center;padding:var(--p-space-8)">
        <div style="font-size:36px;margin-bottom:8px">✅</div>
        <div style="font-weight:600;color:var(--p-text-muted)">Tidak ada tagihan lainnya</div>
      </div>
    </div>
  @endif
</div>

@endsection
@push('scripts')
<script>
function switchTab(btn, id) {
  document.querySelectorAll('.p-tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.p-tab-pane').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById(id).classList.add('active');
}
</script>
@endpush
