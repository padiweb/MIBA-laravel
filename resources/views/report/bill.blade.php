@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-file-text"></i> Laporan Per-Kelas (Rekapitulasi)</div>
    <div class="report-actions" style="display:flex;gap:8px;flex-wrap:wrap">
      @if(request('p'))
      <a href="{{ route('report.billExport', request()->query()) }}" class="btn-miba btn-miba-sm btn-success-miba">
        <i class="fa fa-file-excel-o"></i> Export Excel
      </a>
      <a href="{{ route('report.billDetailExport', request()->query()) }}" class="btn-miba btn-miba-sm btn-accent-miba">
        <i class="fa fa-file-excel-o"></i> Rekapitulasi Detail
      </a>
      @endif
    </div>
  </div>

  <div class="miba-filter-bar">
    <form method="GET" id="filterForm" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;width:100%">
      <select name="p" class="miba-select" style="width:180px" required>
        <option value="">-- Tahun Pelajaran --</option>
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ request('p')==$p->period_id?'selected':'' }}>
            {{ $p->period_start }}/{{ $p->period_end }}
          </option>
        @endforeach
      </select>
      @if(($app_level??'')=='senior')
      <select name="k" id="filterUnit" class="miba-select" style="width:170px" onchange="document.getElementById('filterForm').submit()">
        <option value="">-- Semua Unit --</option>
        @foreach($majorsList as $m)
          <option value="{{ $m->majors_id }}" {{ request('k')==$m->majors_id?'selected':'' }}>{{ $m->majors_name }}</option>
        @endforeach
      </select>
      @endif
      <select name="c" class="miba-select" style="width:170px">
        <option value="">-- Semua Kelas ({{ $classes->count() }}) --</option>
        @foreach($classes as $c)
          <option value="{{ $c->class_id }}" {{ request('c')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
        @endforeach
      </select>
      <button class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-search"></i> Tampilkan</button>
      <a href="{{ route('report.bill') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Reset</a>
    </form>
  </div>

  @if($result)
    {{-- Legend --}}
    <div class="report-legend">
      <span><i class="legend-dot lunas"></i> Lunas</span>
      <span><i class="legend-dot kurang"></i> Ada Kekurangan</span>
      <span><i class="legend-dot kosong"></i> Tidak Ada Tagihan</span>
    </div>

    {{-- ════════ DESKTOP/TABLET: TABEL ════════ --}}
    <div class="bill-table-view">
      <div class="table-scroll-hint">
        <i class="fa fa-arrows-h"></i> Geser ke kanan untuk melihat semua kolom
      </div>
      <div class="miba-table-wrap">
        <table class="miba-table" style="min-width:1000px">
          <thead>
            <tr>
              <th rowspan="2" class="sticky-col sticky-col-1" style="vertical-align:middle">No</th>
              <th rowspan="2" class="sticky-col sticky-col-2" style="min-width:190px;vertical-align:middle">Nama Siswa</th>
              <th rowspan="2" style="vertical-align:middle">Kelas</th>
              <th colspan="{{ count($result['months']) }}" style="text-align:center">Pembayaran Bulanan</th>
              @if(count($result['bebasPayments']))
              <th colspan="{{ count($result['bebasPayments']) }}" style="text-align:center">Pembayaran Bebas / Lainnya</th>
              @endif
              <th rowspan="2" class="sticky-col-r sticky-col-r2" style="text-align:right;vertical-align:middle">Total Dibayar</th>
              <th rowspan="2" class="sticky-col-r sticky-col-r1" style="text-align:right;vertical-align:middle">Kekurangan</th>
            </tr>
            <tr>
              @foreach($result['months'] as $m)
                <th style="text-align:center;min-width:90px;font-size:10px">{{ $m->month_name }}</th>
              @endforeach
              @foreach($result['bebasPayments'] as $bp)
                <th style="text-align:center;min-width:110px;font-size:10px">{{ $bp->pos->pos_name ?? 'Bebas' }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($result['rows'] as $i => $row)
            @php $student = $row['student']; @endphp
            <tr>
              <td class="sticky-col sticky-col-1" style="color:var(--text-muted)">{{ $i+1 }}</td>
              <td class="sticky-col sticky-col-2">
                <div style="font-weight:600;font-size:13px">{{ $student->student_full_name }}</div>
                <div style="font-size:11px;color:var(--text-muted)">{{ $student->student_nis }}</div>
              </td>
              <td style="font-size:12px;color:var(--text-muted)">{{ $student->class->class_name??'' }}</td>

              @foreach($result['months'] as $m)
              @php $bulan = $row['months'][$m->month_id] ?? null; @endphp
              <td style="text-align:center;{{ $bulan ? ($bulan->bulan_status ? 'background:#f0fdf4' : 'background:#fef2f2') : '' }}">
                @if($bulan)
                  @if($bulan->bulan_status)
                    <span style="color:#16a34a;font-weight:600;font-size:11px">Lunas</span>
                  @else
                    <span style="color:#dc2626;font-weight:600;font-size:11px">{{ number_format($bulan->bulan_bill,0,',','.') }}</span>
                  @endif
                @else
                  <span style="color:var(--border-dark)">–</span>
                @endif
              </td>
              @endforeach

              @foreach($result['bebasPayments'] as $bp)
              @php
                $bb = $row['bebasCells'][$bp->payment_id] ?? null;
                $sisa = $bb ? ($bb->bebas_bill - $bb->bebas_total_pay) : null;
              @endphp
              <td style="text-align:center;{{ $bb ? ($sisa<=0 ? 'background:#f0fdf4' : 'background:#fef2f2') : '' }}">
                @if($bb)
                  @if($sisa <= 0)
                    <span style="color:#16a34a;font-weight:600;font-size:11px">Lunas</span>
                  @else
                    <span style="color:#dc2626;font-weight:600;font-size:11px">{{ number_format($sisa,0,',','.') }}</span>
                  @endif
                @else
                  <span style="color:var(--border-dark)">–</span>
                @endif
              </td>
              @endforeach

              <td class="sticky-col-r sticky-col-r2" style="text-align:right;font-weight:600;color:var(--success)">
                Rp {{ number_format($row['total_dibayar'],0,',','.') }}
              </td>
              <td class="sticky-col-r sticky-col-r1" style="text-align:right;font-weight:600;color:{{ $row['kekurangan']>0?'var(--danger)':'var(--success)' }}">
                {{ $row['kekurangan']>0 ? 'Rp '.number_format($row['kekurangan'],0,',','.') : 'Lunas' }}
              </td>
            </tr>
            @endforeach
          </tbody>
          @if(count($result['rows']))
          <tfoot>
            <tr style="background:var(--bg)">
              <td colspan="{{ 3 + count($result['months']) + count($result['bebasPayments']) }}" style="text-align:right;font-weight:700">Grand Total</td>
              <td class="sticky-col-r sticky-col-r2" style="text-align:right;font-weight:700;color:var(--success);background:var(--bg)">Rp {{ number_format($result['grand_dibayar'],0,',','.') }}</td>
              <td class="sticky-col-r sticky-col-r1" style="text-align:right;font-weight:700;color:var(--danger);background:var(--bg)">Rp {{ number_format($result['grand_kekurangan'],0,',','.') }}</td>
            </tr>
          </tfoot>
          @endif
        </table>
      </div>
    </div>

    {{-- ════════ MOBILE: CARD PER SISWA ════════ --}}
    <div class="bill-card-view">
      @foreach($result['rows'] as $i => $row)
      @php $student = $row['student']; $lunas = $row['kekurangan'] <= 0; @endphp
      <div class="bill-student-card">
        <div class="bsc-head" onclick="toggleBsc(this)">
          <div class="bsc-num">{{ $i+1 }}</div>
          <div class="bsc-info">
            <div class="bsc-name">{{ $student->student_full_name }}</div>
            <div class="bsc-sub">{{ $student->student_nis }} · {{ $student->class->class_name??'' }}</div>
          </div>
          <div class="bsc-right">
            <div class="bsc-amount {{ $lunas ? 'ok' : 'bad' }}">
              {{ $lunas ? 'Lunas' : 'Rp '.number_format($row['kekurangan'],0,',','.') }}
            </div>
            <i class="fa fa-chevron-down bsc-chevron"></i>
          </div>
        </div>

        <div class="bsc-body">
          <div class="bsc-section-title">Pembayaran Bulanan</div>
          <div class="bsc-grid">
            @foreach($result['months'] as $m)
            @php $bulan = $row['months'][$m->month_id] ?? null; @endphp
            <div class="bsc-cell {{ $bulan ? ($bulan->bulan_status ? 'ok' : 'bad') : 'empty' }}">
              <div class="bsc-cell-label">{{ $m->month_name }}</div>
              <div class="bsc-cell-value">
                @if($bulan)
                  {{ $bulan->bulan_status ? 'Lunas' : number_format($bulan->bulan_bill,0,',','.') }}
                @else
                  –
                @endif
              </div>
            </div>
            @endforeach
          </div>

          @if(count($result['bebasPayments']))
          <div class="bsc-section-title" style="margin-top:12px">Pembayaran Bebas / Lainnya</div>
          <div class="bsc-grid">
            @foreach($result['bebasPayments'] as $bp)
            @php
              $bb = $row['bebasCells'][$bp->payment_id] ?? null;
              $sisa = $bb ? ($bb->bebas_bill - $bb->bebas_total_pay) : null;
            @endphp
            <div class="bsc-cell {{ $bb ? ($sisa<=0 ? 'ok' : 'bad') : 'empty' }}">
              <div class="bsc-cell-label">{{ $bp->pos->pos_name ?? 'Bebas' }}</div>
              <div class="bsc-cell-value">
                @if($bb)
                  {{ $sisa<=0 ? 'Lunas' : number_format($sisa,0,',','.') }}
                @else
                  –
                @endif
              </div>
            </div>
            @endforeach
          </div>
          @endif

          <div class="bsc-totals">
            <div class="bsc-total-item">
              <span>Total Dibayar</span>
              <strong style="color:var(--success)">Rp {{ number_format($row['total_dibayar'],0,',','.') }}</strong>
            </div>
            <div class="bsc-total-item">
              <span>Kekurangan</span>
              <strong style="color:{{ $lunas?'var(--success)':'var(--danger)' }}">
                {{ $lunas ? 'Lunas' : 'Rp '.number_format($row['kekurangan'],0,',','.') }}
              </strong>
            </div>
          </div>
        </div>
      </div>
      @endforeach

      @if(count($result['rows']))
      <div class="bill-grand-total">
        <div class="bgt-row">
          <span>Grand Total Dibayar</span>
          <strong style="color:var(--success)">Rp {{ number_format($result['grand_dibayar'],0,',','.') }}</strong>
        </div>
        <div class="bgt-row">
          <span>Grand Total Kekurangan</span>
          <strong style="color:var(--danger)">Rp {{ number_format($result['grand_kekurangan'],0,',','.') }}</strong>
        </div>
      </div>
      @else
      <div style="text-align:center;padding:32px;color:var(--text-muted)">Tidak ada data siswa untuk filter ini</div>
      @endif
    </div>

  @elseif(request('p'))
    <div style="text-align:center;padding:40px;color:var(--text-muted)">
      <i class="fa fa-search" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px"></i>
      Tidak ada data untuk filter ini
    </div>
  @else
    <div style="text-align:center;padding:48px;color:var(--text-muted)">
      <i class="fa fa-filter" style="font-size:40px;opacity:.2;display:block;margin-bottom:12px"></i>
      <div style="font-weight:500">Pilih Tahun Pelajaran</div>
      <div style="font-size:12px;margin-top:4px">untuk menampilkan laporan rekapitulasi pembayaran</div>
    </div>
  @endif
</div>

<style>
/* ── Legend ── */
.report-legend { padding:12px 16px; border-bottom:1px solid var(--border); display:flex; gap:16px; flex-wrap:wrap; font-size:11px; color:var(--text-muted); }
.legend-dot { display:inline-block; width:10px; height:10px; border-radius:3px; margin-right:4px; vertical-align:middle; }
.legend-dot.lunas  { background:#d1fae5; border:1px solid #6ee7b7; }
.legend-dot.kurang { background:#fee2e2; border:1px solid #fca5a5; }
.legend-dot.kosong { background:var(--bg); border:1px solid var(--border); }

/* ── Scroll hint (desktop/tablet only, auto-hide setelah discroll) ── */
.table-scroll-hint {
  display: none;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  color: var(--primary);
  background: var(--primary-xlight);
  padding: 7px 16px;
  font-weight: 500;
}

/* ── Sticky kolom kiri tabel ── */
.sticky-col { position: sticky; background: #fff; z-index: 1; }
.sticky-col-1 { left: 0; min-width: 32px; }
.sticky-col-2 { left: 32px; }
thead .sticky-col { background: var(--bg); z-index: 2; }
tbody tr:hover .sticky-col { background: var(--bg); }

/* ── Sticky kolom kanan (Total Dibayar / Kekurangan) ── */
.sticky-col-r { position: sticky; background: #fff; z-index: 1; }
.sticky-col-r1 { right: 0; min-width: 110px; box-shadow: -2px 0 4px rgba(0,0,0,.05); }
.sticky-col-r2 { right: 110px; min-width: 120px; }
thead .sticky-col-r { background: var(--bg); z-index: 2; }
tbody tr:hover .sticky-col-r { background: var(--bg); }

/* ── Default: tabel tampil, card view sembunyi ── */
.bill-card-view { display: none; }
.bill-table-view { display: block; }

/* ════════════════════════════════════
   TABLET (769px–1024px): tabel tetap,
   tapi tampilkan scroll-hint
   ════════════════════════════════════ */
@media (min-width: 769px) and (max-width: 1100px) {
  .table-scroll-hint { display: flex; }
}

/* ════════════════════════════════════
   MOBILE & TABLET KECIL (≤900px):
   ganti tabel → card per siswa
   ════════════════════════════════════ */
@media (max-width: 900px) {
  .report-legend { font-size: 10px; gap: 10px; padding: 10px 12px; }

  .bill-table-view { display: none; }
  .bill-card-view { display: block; padding: 12px; }

  .bill-student-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    margin-bottom: 10px;
    overflow: hidden;
  }
  .bsc-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    cursor: pointer;
  }
  .bsc-num {
    width: 24px; height: 24px;
    border-radius: 50%;
    background: var(--bg);
    color: var(--text-muted);
    font-size: 11px; font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .bsc-info { flex: 1; min-width: 0; }
  .bsc-name { font-size: 13px; font-weight: 700; color: var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .bsc-sub { font-size: 11px; color: var(--text-muted); margin-top: 1px; }
  .bsc-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
  .bsc-amount { font-size: 12px; font-weight: 700; white-space: nowrap; }
  .bsc-amount.ok  { color: #16a34a; }
  .bsc-amount.bad { color: #dc2626; }
  .bsc-chevron { font-size: 11px; color: var(--text-muted); transition: transform .25s; flex-shrink:0; }
  .bill-student-card.open .bsc-chevron { transform: rotate(180deg); }

  .bsc-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .3s ease;
    border-top: 1px solid transparent;
  }
  .bill-student-card.open .bsc-body {
    max-height: 1200px;
    border-top: 1px solid var(--border);
  }
  .bsc-section-title {
    font-size: 10px; font-weight: 700; letter-spacing:.04em; text-transform:uppercase;
    color: var(--text-muted); padding: 10px 14px 6px;
  }
  .bsc-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
    padding: 0 14px;
  }
  .bsc-cell {
    border-radius: 8px;
    padding: 6px 4px;
    text-align: center;
    border: 1px solid var(--border);
  }
  .bsc-cell.ok    { background:#f0fdf4; border-color:#bbf7d0; }
  .bsc-cell.bad   { background:#fef2f2; border-color:#fecaca; }
  .bsc-cell.empty { background:var(--bg); }
  .bsc-cell-label { font-size: 9px; color: var(--text-muted); margin-bottom: 2px; }
  .bsc-cell-value { font-size: 10px; font-weight: 700; color: var(--text-primary); }
  .bsc-cell.ok  .bsc-cell-value { color: #16a34a; }
  .bsc-cell.bad .bsc-cell-value { color: #dc2626; }

  .bsc-totals {
    display: flex; flex-direction: column; gap: 6px;
    padding: 12px 14px; margin-top: 8px;
    border-top: 1px dashed var(--border);
  }
  .bsc-total-item { display: flex; justify-content: space-between; font-size: 12px; }
  .bsc-total-item span { color: var(--text-muted); }

  .bill-grand-total {
    background: var(--bg);
    border-radius: 14px;
    padding: 14px;
    margin-top: 4px;
  }
  .bgt-row { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; }
}

/* Card lebih compact lagi di layar sangat kecil */
@media (max-width: 360px) {
  .bsc-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endsection
@push('scripts')
<script>
function toggleBsc(headEl) {
  headEl.closest('.bill-student-card').classList.toggle('open');
}

// Auto-expand card pertama di mobile biar user tahu cara pakainya
document.addEventListener('DOMContentLoaded', function() {
  if (window.innerWidth <= 900) {
    var first = document.querySelector('.bill-student-card');
    if (first) first.classList.add('open');
  }
});
</script>
@endpush
