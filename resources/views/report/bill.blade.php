@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-file-text"></i> Laporan Per-Kelas (Rekapitulasi)</div>
    <div style="display:flex;gap:8px">
      @if(request('p'))
      <a href="{{ route('report.billExport', request()->query()) }}" class="btn-miba btn-miba-sm btn-success-miba">
        <i class="fa fa-file-excel-o"></i> Export Excel
      </a>
      <a href="{{ route('report.billDetailExport', request()->query()) }}" class="btn-miba btn-miba-sm btn-accent-miba">
        <i class="fa fa-file-excel-o"></i> Rekapitulasi Excel
      </a>
      @endif
    </div>
  </div>

  <div class="miba-filter-bar">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
      <select name="p" class="miba-select" style="width:180px" required>
        <option value="">-- Tahun Pelajaran --</option>
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ request('p')==$p->period_id?'selected':'' }}>
            {{ $p->period_start }}/{{ $p->period_end }}
          </option>
        @endforeach
      </select>
      <select name="c" class="miba-select" style="width:160px">
        <option value="">-- Semua Kelas --</option>
        @foreach($classes as $c)
          <option value="{{ $c->class_id }}" {{ request('c')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
        @endforeach
      </select>
      @if(($app_level??'')=='senior')
      <select name="k" class="miba-select" style="width:160px">
        <option value="">-- Semua Unit --</option>
        @foreach($majorsList as $m)
          <option value="{{ $m->majors_id }}" {{ request('k')==$m->majors_id?'selected':'' }}>{{ $m->majors_name }}</option>
        @endforeach
      </select>
      @endif
      <button class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-search"></i> Tampilkan</button>
      <a href="{{ route('report.bill') }}" class="btn-miba btn-miba-sm btn-ghost-miba">Reset</a>
    </form>
  </div>

  @if($result)
    <div style="overflow-x:auto">
      <table class="miba-table" style="min-width:900px">
        <thead>
          <tr>
            <th style="position:sticky;left:0;background:var(--bg);z-index:1">No</th>
            <th style="position:sticky;left:32px;background:var(--bg);z-index:1;min-width:180px">Nama Siswa</th>
            <th>Kelas</th>
            @foreach($result['months'] as $m)
              <th style="text-align:center;min-width:72px;font-size:10px">{{ $m->month_name }}</th>
            @endforeach
            <th style="text-align:right;min-width:100px">Dibayar</th>
            <th style="text-align:right;min-width:100px">Kekurangan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($result['rows'] as $i => $row)
          @php $student = $row['student']; @endphp
          <tr>
            <td style="color:var(--text-muted)">{{ $i+1 }}</td>
            <td>
              <div style="font-weight:600;font-size:13px">{{ $student->student_full_name }}</div>
              <div style="font-size:11px;color:var(--text-muted)">{{ $student->student_nis }}</div>
            </td>
            <td style="font-size:12px;color:var(--text-muted)">{{ $student->class->class_name??'' }}</td>
            @foreach($result['months'] as $m)
            @php $bulan = $row['months'][$m->month_id] ?? null; @endphp
            <td style="text-align:center">
              @if($bulan)
                @if($bulan->bulan_status)
                  <span style="color:var(--success);font-size:14px" title="Lunas">✓</span>
                @else
                  <span style="color:var(--danger);font-size:12px" title="Belum Lunas">–</span>
                @endif
              @else
                <span style="color:var(--border-dark);font-size:12px">·</span>
              @endif
            </td>
            @endforeach
            <td style="text-align:right;font-weight:600;color:var(--success)">
              Rp {{ number_format($row['total_dibayar'],0,',','.') }}
            </td>
            <td style="text-align:right;font-weight:600;color:{{ $row['kekurangan']>0?'var(--danger)':'var(--success)' }}">
              {{ $row['kekurangan']>0 ? 'Rp '.number_format($row['kekurangan'],0,',','.') : '✓ Lunas' }}
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background:var(--bg)">
            <td colspan="{{ 3 + count($result['months']) }}" style="text-align:right;font-weight:700">Grand Total</td>
            <td style="text-align:right;font-weight:700;color:var(--success)">Rp {{ number_format($result['grand_dibayar'],0,',','.') }}</td>
            <td style="text-align:right;font-weight:700;color:var(--danger)">Rp {{ number_format($result['grand_kekurangan'],0,',','.') }}</td>
          </tr>
        </tfoot>
      </table>
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
@endsection
