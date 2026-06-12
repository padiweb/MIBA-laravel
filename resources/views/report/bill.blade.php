@extends('layouts.app')
@section('content')
<div class="box box-success">
  <div class="box-header with-border"><h3 class="box-title">Laporan Per-Kelas (Rekapitulasi)</h3></div>
  <div class="box-body">
    <form method="GET" id="filter-form">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label>Tahun Pelajaran <span class="text-danger">*</span></label>
            <select class="form-control" name="p" required>
              <option value="">-- Pilih Tahun Pelajaran --</option>
              @foreach($periods as $p)
                <option value="{{ $p->period_id }}" {{ request('p')==$p->period_id?'selected':'' }}>
                  {{ $p->period_start }}/{{ $p->period_end }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label>Unit Pendidikan</label>
            <select class="form-control" name="k">
              <option value="">-- Semua Unit --</option>
              @foreach($majorsList as $m)
                <option value="{{ $m->majors_id }}" {{ request('k')==$m->majors_id?'selected':'' }}>{{ $m->majors_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label>Kelas</label>
            <select class="form-control" name="c">
              <option value="">-- Semua Kelas --</option>
              @foreach($classes as $c)
                <option value="{{ $c->class_id }}" {{ request('c')==$c->class_id?'selected':'' }}>{{ $c->class_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <label>&nbsp;</label><br>
          <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter Data</button>
          @if($result)
            <a class="btn btn-success" href="{{ route('report.billExport', request()->query()) }}">
              <i class="fa fa-file-excel-o"></i> Export CSV
            </a>
            <button type="button" class="btn btn-info" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

@if($result)
<div class="box box-success">
  <div class="box-body table-responsive">
    <table class="table table-bordered table-hover" style="white-space:nowrap;font-size:12px">
      <thead>
        <tr>
          <th>No</th><th>Kelas</th><th>Nama</th>
          @foreach($result['months'] as $m)
            <th>{{ $m->month_name }}</th>
          @endforeach
          <th>Bebas</th>
          <th>Total Dibayar</th>
          <th>Kekurangan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($result['rows'] as $i => $row)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $row['student']->class->class_name ?? '-' }}</td>
          <td>{{ $row['student']->student_full_name }}</td>
          @foreach($result['months'] as $m)
            @php $b = $row['months'][$m->month_id] ?? null; @endphp
            <td class="text-center {{ $b ? ($b->bulan_status ? 'bg-green' : 'bg-red') : '' }}">
              @if($b)
                @if($b->bulan_status)
                  <i class="fa fa-check"></i>
                @else
                  {{ number_format($b->bulan_bill,0,',','.') }}
                @endif
              @else
                -
              @endif
            </td>
          @endforeach
          <td class="text-right">{{ number_format($row['bebas'],0,',','.') }}</td>
          <td class="text-right"><strong>{{ number_format($row['total_dibayar'],0,',','.') }}</strong></td>
          <td class="text-right"><strong class="{{ $row['kekurangan']>0?'text-red':'text-green' }}">{{ number_format($row['kekurangan'],0,',','.') }}</strong></td>
        </tr>
        @empty
        <tr><td colspan="{{ count($result['months'])+6 }}" class="text-center">Tidak ada data</td></tr>
        @endforelse
      </tbody>
      <tfoot>
        <tr class="bg-gray">
          <td colspan="{{ count($result['months'])+4 }}" class="text-right"><strong>TOTAL</strong></td>
          <td class="text-right"><strong>{{ number_format($result['grand_dibayar'],0,',','.') }}</strong></td>
          <td class="text-right"><strong>{{ number_format($result['grand_kekurangan'],0,',','.') }}</strong></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
@endif
@endsection
