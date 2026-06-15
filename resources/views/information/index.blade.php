@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-newspaper-o"></i> Informasi</div>
    <a href="{{ route('information.create') }}" class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-plus"></i> Buat Informasi</a>
  </div>
  <div class="miba-table-wrap">
    <table class="miba-table">
      <thead><tr><th>No</th><th>Judul</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($informations as $i => $info)
        <tr>
          <td style="color:var(--text-muted)">{{ $informations->firstItem()+$i }}</td>
          <td>
            <div style="font-weight:600">{{ $info->information_title }}</div>
            <div style="font-size:12px;color:var(--text-muted)">{{ \Illuminate\Support\Str::limit(strip_tags($info->information_desc),80) }}</div>
          </td>
          <td><span class="badge-miba {{ $info->information_publish?'badge-success':'badge-muted' }}">{{ $info->information_publish?'Dipublikasi':'Draft' }}</span></td>
          <td style="font-size:12px;color:var(--text-muted)">{{ \Carbon\Carbon::parse($info->information_input_date)->locale('id')->isoFormat('D MMM Y') }}</td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('information.edit',$info->information_id) }}" class="btn-miba btn-miba-xs btn-accent-miba"><i class="fa fa-edit"></i></a>
              <form method="POST" action="{{ route('information.destroy',$info->information_id) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada informasi</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:12px 16px">{{ $informations->links() }}</div>
</div>
@endsection