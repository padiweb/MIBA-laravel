@extends('layouts.app')
@section('content')
<div class="box box-danger">
  <div class="box-header with-border">
    <h3 class="box-title">Daftar Pengeluaran (Kredit)</h3>
    <div class="box-tools pull-right">
      <a href="#addKredit" data-toggle="modal" class="btn btn-danger btn-sm">
        <i class="fa fa-plus"></i> Tambah Pengeluaran
      </a>
    </div>
  </div>
  <div class="box-body table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Nominal</th><th>Petugas</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($kredits as $i => $k)
        <tr>
          <td>{{ $kredits->firstItem() + $i }}</td>
          <td>{{ \Carbon\Carbon::parse($k->kredit_date)->locale('id')->isoFormat('D MMMM Y') }}</td>
          <td>{{ $k->kredit_desc }}</td>
          <td>Rp. {{ number_format($k->kredit_value, 0, ',', '.') }}</td>
          <td>{{ $k->user->user_full_name ?? '-' }}</td>
          <td>
            <a href="{{ route('kredit.edit', $k->kredit_id) }}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
            <form action="{{ route('kredit.destroy', $k->kredit_id) }}" method="POST" style="display:inline"
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
          <td><strong>Rp. {{ number_format($kredits->sum('kredit_value'), 0, ',', '.') }}</strong></td>
          <td colspan="2"></td>
        </tr>
      </tfoot>
    </table>
    <div class="text-center">{{ $kredits->links() }}</div>
  </div>
</div>

{{-- Modal Tambah Pengeluaran (multi-baris) --}}
<div class="modal fade" id="addKredit" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tambah Pengeluaran</h4>
      </div>
      <form method="POST" action="{{ route('kredit.storeGlob') }}">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Tanggal Pengeluaran</label>
            <div class="input-group date">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input class="form-control" required type="text" name="kredit_date" value="{{ date('Y-m-d') }}" placeholder="Tanggal Pengeluaran">
            </div>
          </div>
          <div id="p_scents_kredit">
            <div class="row">
              <div class="col-md-6">
                <label>Keterangan *</label>
                <input type="text" required name="kredit_desc[]" class="form-control" placeholder="Keterangan Pengeluaran">
              </div>
              <div class="col-md-6">
                <label>Jumlah Rupiah *</label>
                <input type="number" required name="kredit_value[]" class="form-control" placeholder="Jumlah">
              </div>
            </div>
          </div>
          <br>
          <a href="#" class="btn btn-xs btn-success" id="addScnt_kredit"><i class="fa fa-plus"></i> <b>Tambah Baris</b></a>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
$(function() {
  var scntDiv = $('#p_scents_kredit');
  $('#addScnt_kredit').click(function() {
    scntDiv.append('<div class="row" style="margin-top:10px"><div class="col-md-6"><label>Keterangan *</label><input type="text" required name="kredit_desc[]" class="form-control" placeholder="Keterangan Pengeluaran"><br><a href="#" class="btn btn-xs btn-danger remRow"><i class="fa fa-close"></i> Hapus Baris</a></div><div class="col-md-6"><label>Jumlah Rupiah *</label><input type="number" required name="kredit_value[]" class="form-control" placeholder="Jumlah"></div></div>');
    return false;
  });
  $(document).on('click', '.remRow', function() { $(this).closest('.row').remove(); return false; });
});
</script>
@endpush
