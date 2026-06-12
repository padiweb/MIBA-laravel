@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Pembayaran Siswa</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('payout.create') }}" class="btn btn-primary btn-sm">
        <i class="fa fa-plus"></i> Bayar
      </a>
    </div>
  </div>
  <div class="box-body">
    <form method="GET" class="form-inline" style="margin-bottom:15px">
      <input type="text" name="n" class="form-control" placeholder="Cari nama / NIS..."
             value="{{ request('n') }}" style="width:180px">
      <select name="period_id" class="form-control">
        <option value="">Semua Tahun</option>
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ request('period_id')==$p->period_id?'selected':'' }}>
            {{ $p->period_start }}/{{ $p->period_end }}
          </option>
        @endforeach
      </select>
      <input type="text" name="date_start" class="form-control input-group date" placeholder="Dari tanggal" value="{{ request('date_start') }}" style="width:130px">
      <input type="text" name="date_end" class="form-control input-group date" placeholder="Sampai tanggal" value="{{ request('date_end') }}" style="width:130px">
      <button class="btn btn-default"><i class="fa fa-search"></i></button>
      <a href="{{ route('payout.index') }}" class="btn btn-default">Reset</a>
    </form>

    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr><th>No</th><th>No. Bayar</th><th>Siswa</th><th>Jenis</th><th>Bulan</th><th>Nominal</th><th>Tgl Bayar</th><th>Petugas</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($payouts as $i => $b)
        <tr>
          <td>{{ $payouts->firstItem() + $i }}</td>
          <td>{{ $b->bulan_number_pay ?? '-' }}</td>
          <td>
            <strong>{{ $b->student->student_full_name ?? '-' }}</strong><br>
            <small class="text-muted">{{ $b->student->student_nis ?? '' }}</small>
          </td>
          <td>{{ $b->payment->pos->pos_name ?? '-' }}</td>
          <td>{{ $b->month->month_name ?? '-' }}</td>
          <td>Rp {{ number_format($b->bulan_bill, 0, ',', '.') }}</td>
          <td>{{ $b->bulan_date_pay ? \Carbon\Carbon::parse($b->bulan_date_pay)->format('d/m/Y') : '-' }}</td>
          <td>{{ $b->user->user_full_name ?? '-' }}</td>
          <td>
            <a href="{{ route('payout.cetak', $b->bulan_id) }}" class="btn btn-info btn-xs" target="_blank">
              <i class="fa fa-print"></i>
            </a>
            <form action="{{ route('payout.destroy', $b->bulan_id) }}" method="POST" style="display:inline"
                  onsubmit="return confirm('Hapus data pembayaran ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="text-center">{{ $payouts->links() }}</div>
  </div>
</div>
@endsection
