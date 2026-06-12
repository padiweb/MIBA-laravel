@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Jenis Pembayaran</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('payment.create') }}" class="btn btn-primary btn-sm">
        <i class="fa fa-plus"></i> Tambah
      </a>
    </div>
  </div>
  <div class="box-body">
    <form method="GET" class="form-inline" style="margin-bottom:15px">
      <input type="text" name="n" class="form-control" placeholder="Cari nama pos..."
             value="{{ request('n') }}" style="width:200px">
      <select name="period_id" class="form-control">
        <option value="">Semua Tahun Pelajaran</option>
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ request('period_id')==$p->period_id?'selected':'' }}>
            {{ $p->period_start }}/{{ $p->period_end }}
          </option>
        @endforeach
      </select>
      <button class="btn btn-default"><i class="fa fa-search"></i> Cari</button>
      <a href="{{ route('payment.index') }}" class="btn btn-default">Reset</a>
    </form>

    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr><th>No</th><th>Nama Pembayaran</th><th>Jenis Pembayaran</th><th>Tipe</th><th>Tahun</th><th>Tarif Pembayaran</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($payments as $i => $p)
        <tr>
          <td>{{ $payments->firstItem() + $i }}</td>
          <td>{{ $p->pos->pos_name ?? '-' }}</td>
          <td>{{ $p->pos->pos_name ?? '-' }} - T.P {{ $p->period->period_start ?? '-' }}/{{ $p->period->period_end ?? '-' }}</td>
          <td><span class="label label-{{ $p->payment_type=='BULAN'?'info':'warning' }}">{{ $p->payment_type=='BULAN'?'Bulanan':'Bebas' }}</span></td>
          <td>{{ $p->period->period_start ?? '-' }}/{{ $p->period->period_end ?? '-' }}</td>
          <td>
            @if($p->payment_type=='BULAN')
              <a href="{{ route('payment.viewBulan', $p->payment_id) }}" class="btn btn-primary btn-xs">
                Atur Tarif Pembayaran
              </a>
            @else
              <a href="{{ route('payment.viewBebas', $p->payment_id) }}" class="btn btn-primary btn-xs">
                Atur Tarif Pembayaran
              </a>
            @endif
          </td>
          <td>
            <a href="{{ route('payment.edit', $p->payment_id) }}" class="btn btn-success btn-xs" title="Edit">
              <i class="fa fa-edit"></i>
            </a>
            <form action="{{ route('payment.destroy', $p->payment_id) }}" method="POST" style="display:inline"
                  onsubmit="return confirm('Hapus data ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="text-center">{{ $payments->links() }}</div>
  </div>
</div>
@endsection
