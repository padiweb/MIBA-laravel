@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Debit</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('debit.store') }}">
          @csrf
          <div class="form-group">
            <label>Tanggal</label>
            <div class="input-group date">
              <input type="text" name="debit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="debit_desc" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Nominal</label>
            <div class="input-group">
              <span class="input-group-addon">Rp</span>
              <input type="number" name="debit_value" class="form-control" required min="0">
            </div>
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Daftar Debit</h3></div>
      <div class="box-body">
        <table class="table table-bordered table-striped">
          <thead><tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Nominal</th><th>Petugas</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($debits as $i => $d)
            <tr>
              <td>{{ $debits->firstItem() + $i }}</td>
              <td>{{ \Carbon\Carbon::parse($d->debit_date)->format('d/m/Y') }}</td>
              <td>{{ $d->debit_desc }}</td>
              <td>Rp {{ number_format($d->debit_value, 0, ',', '.') }}</td>
              <td>{{ $d->user->user_full_name ?? '-' }}</td>
              <td>
                <form action="{{ route('debit.destroy', $d->debit_id) }}" method="POST" style="display:inline"
                      onsubmit="return confirm('Hapus data ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
            @endforelse
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="text-right"><strong>Total</strong></td>
              <td><strong>Rp {{ number_format($debits->sum('debit_value'), 0, ',', '.') }}</strong></td>
              <td colspan="2"></td>
            </tr>
          </tfoot>
        </table>
        <div class="text-center">{{ $debits->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
