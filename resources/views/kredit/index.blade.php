@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-arrow-up" style="color:var(--danger)"></i> Pengeluaran (Kredit)</div>
    <button class="btn-miba btn-miba-sm btn-danger-miba" data-toggle="modal" data-target="#addKredit"><i class="fa fa-plus"></i> Tambah Pengeluaran</button>
  </div>
  <div class="miba-table-wrap">
    <table class="miba-table">
      <thead><tr><th>No</th><th>Tanggal</th><th>Keterangan</th><th>Nominal</th><th>Petugas</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($kredits as $i=>$k)
        <tr>
          <td style="color:var(--text-muted)">{{ $kredits->firstItem()+$i }}</td>
          <td style="white-space:nowrap">{{ \Carbon\Carbon::parse($k->kredit_date)->locale('id')->isoFormat('D MMM Y') }}</td>
          <td>{{ $k->kredit_desc }}</td>
          <td style="font-weight:600;color:var(--danger)">Rp {{ number_format($k->kredit_value,0,',','.') }}</td>
          <td style="font-size:12px;color:var(--text-muted)">{{ $k->user->user_full_name??'-' }}</td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('kredit.edit',$k->kredit_id) }}" class="btn-miba btn-miba-xs btn-accent-miba"><i class="fa fa-edit"></i></a>
              <form method="POST" action="{{ route('kredit.destroy',$k->kredit_id) }}" style="display:inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada data pengeluaran</td></tr>
        @endforelse
      </tbody>
      <tfoot><tr><td colspan="3" style="text-align:right;font-weight:600">Total</td><td style="font-weight:700;color:var(--danger)">Rp {{ number_format($kredits->sum('kredit_value'),0,',','.') }}</td><td colspan="2"></td></tr></tfoot>
    </table>
  </div>
  <div style="padding:12px 16px">{{ $kredits->links() }}</div>
</div>

<div class="modal fade" id="addKredit">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
    <form method="POST" action="{{ route('kredit.storeGlob') }}">@csrf
      <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Tambah Pengeluaran</h4><button class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <div class="miba-form-group"><label class="miba-label">Tanggal</label><input class="miba-input date-pick" required type="text" name="kredit_date" value="{{ date('Y-m-d') }}"></div>
        <div id="rows_kredit">
          <div class="row-item" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px">
            <div><label class="miba-label">Keterangan <span class="req">*</span></label><input type="text" required name="kredit_desc[]" class="miba-input"></div>
            <div><label class="miba-label">Jumlah (Rp) <span class="req">*</span></label><input type="number" required name="kredit_value[]" class="miba-input"></div>
          </div>
        </div>
        <button type="button" class="btn-miba btn-miba-sm btn-ghost-miba" id="addRowKredit"><i class="fa fa-plus"></i> Tambah Baris</button>
      </div>
      <div class="modal-footer"><button type="submit" class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> Simpan</button><button type="button" class="btn-miba btn-ghost-miba" data-dismiss="modal">Batal</button></div>
    </form>
  </div></div>
</div>
@endsection
@push('scripts')
<script>
$('#addRowKredit').click(function(){
  $('#rows_kredit').append('<div class="row-item" style="display:grid;grid-template-columns:1fr 1fr auto;gap:8px;margin-bottom:8px"><div><input type="text" required name="kredit_desc[]" class="miba-input" placeholder="Keterangan"></div><div><input type="number" required name="kredit_value[]" class="miba-input" placeholder="Jumlah"></div><button type="button" onclick="$(this).closest(\'.row-item\').remove()" class="btn-miba btn-miba-xs btn-danger-miba" style="margin-top:auto"><i class="fa fa-times"></i></button></div>');
});
</script>
@endpush