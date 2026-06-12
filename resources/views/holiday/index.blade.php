@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Tambah Hari Libur</h3></div>
      <div class="box-body">
        <form method="POST" action="{{ route('holiday.store') }}">
          @csrf
          <div class="form-group">
            <label>Tanggal</label>
            <div class="input-group date">
              <input type="text" name="date" class="form-control" required>
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="info" class="form-control" placeholder="Contoh: Hari Raya Idul Fitri" required>
          </div>
          <button class="btn btn-primary btn-block"><i class="fa fa-save"></i> Simpan</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">Daftar Hari Libur</h3></div>
      <div class="box-body">
        <table class="table table-bordered table-striped">
          <thead><tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($holidays as $i => $h)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ \Carbon\Carbon::parse($h->date)->format('d/m/Y') }}</td>
              <td>{{ $h->info }}</td>
              <td>
                <form action="{{ route('holiday.destroy', $h->id) }}" method="POST" style="display:inline"
                      onsubmit="return confirm('Hapus hari libur ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
