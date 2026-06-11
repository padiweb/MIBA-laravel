@extends('layouts.app')
@section('content')
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Informasi</h3>
    <div class="box-tools pull-right">
      <a href="{{ route('information.create') }}" class="btn btn-primary btn-sm">
        <i class="fa fa-plus"></i> Tambah
      </a>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered table-striped">
      <thead><tr><th>No</th><th>Judul</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($informations as $i => $info)
        <tr>
          <td>{{ $informations->firstItem() + $i }}</td>
          <td>{{ $info->information_title }}</td>
          <td>
            <span class="label label-{{ $info->information_publish ? 'success' : 'default' }}">
              {{ $info->information_publish ? 'Publish' : 'Draft' }}
            </span>
          </td>
          <td>{{ \Carbon\Carbon::parse($info->information_input_date)->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('information.edit', $info->information_id) }}" class="btn btn-warning btn-xs">
              <i class="fa fa-edit"></i>
            </a>
            <form action="{{ route('information.destroy', $info->information_id) }}" method="POST" style="display:inline"
                  onsubmit="return confirm('Hapus informasi ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
        @endforelse
      </tbody>
    </table>
    <div class="text-center">{{ $informations->links() }}</div>
  </div>
</div>
@endsection
