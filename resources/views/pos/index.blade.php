@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:16px;align-items:start">
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-plus"></i> Tambah Nama Pembayaran</div></div>
    <div class="miba-card-body">
      <form method="POST" action="{{ route('pos.store') }}">@csrf
        <div class="miba-form-group"><label class="miba-label">Nama Pembayaran <span class="req">*</span></label><input type="text" name="pos_name" class="miba-input" required placeholder="Contoh: SPP Bulanan"></div>
        <div class="miba-form-group"><label class="miba-label">Keterangan</label><textarea name="pos_desc" class="miba-textarea" rows="2" placeholder="Deskripsi singkat..."></textarea></div>
        <button class="btn-miba btn-primary-miba" style="width:100%;justify-content:center"><i class="fa fa-save"></i> Simpan</button>
      </form>
    </div>
  </div>
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-tags"></i> Daftar Nama Pembayaran</div></div>
    <div class="miba-table-wrap">
      <table class="miba-table">
        <thead><tr><th>No</th><th>Nama Pembayaran</th><th>Keterangan</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($poses as $i => $p)
          <tr>
            <td style="color:var(--text-muted)">{{ $i+1 }}</td>
            <td style="font-weight:600">{{ $p->pos_name }}</td>
            <td style="font-size:12px;color:var(--text-muted)">{{ $p->pos_desc ?: '-' }}</td>
            <td>
              <div style="display:flex;gap:4px">
                <button class="btn-miba btn-miba-xs btn-accent-miba" onclick="editPos({{ $p->pos_id }},'{{ addslashes($p->pos_name) }}','{{ addslashes($p->pos_desc??'') }}')"><i class="fa fa-edit"></i></button>
                <form method="POST" action="{{ route('pos.destroy',$p->pos_id) }}" onsubmit="return confirm('Hapus nama pembayaran ini?')">@csrf @method('DELETE')
                  <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted)">Belum ada nama pembayaran</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="modal fade" id="editPos">
  <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
    <form method="POST" id="editPosForm" action="">@csrf @method('PUT')
      <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Edit Nama Pembayaran</h4><button class="close" data-dismiss="modal">&times;</button></div>
      <div class="modal-body">
        <div class="miba-form-group"><label class="miba-label">Nama Pembayaran</label><input type="text" name="pos_name" id="editPosName" class="miba-input" required></div>
        <div class="miba-form-group"><label class="miba-label">Keterangan</label><textarea name="pos_desc" id="editPosDesc" class="miba-textarea" rows="2"></textarea></div>
      </div>
      <div class="modal-footer"><button type="submit" class="btn-miba btn-primary-miba">Update</button><button type="button" class="btn-miba btn-ghost-miba" data-dismiss="modal">Batal</button></div>
    </form>
  </div></div>
</div>
@endsection
@push('scripts')
<script>
function editPos(id,name,desc){$('#editPosForm').attr('action','/manage/pos/'+id);$('#editPosName').val(name);$('#editPosDesc').val(desc);$('#editPos').modal('show');}
</script>
@endpush