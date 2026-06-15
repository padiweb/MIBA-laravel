@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:260px 1fr;gap:16px;align-items:start">
  <div class="miba-card">
    <div class="miba-card-body" style="text-align:center">
      @if($user->user_image)
        <img src="{{ asset('uploads/users/'.$user->user_image) }}" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--border)">
      @else
        <div style="width:90px;height:90px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;margin:0 auto;color:var(--primary);font-size:32px;font-weight:700">{{ strtoupper(substr($user->user_full_name,0,1)) }}</div>
      @endif
      <h3 style="font-size:15px;font-weight:700;margin:12px 0 4px">{{ $user->user_full_name }}</h3>
      <p style="font-size:12px;color:var(--text-muted)">{{ $user->role->role_name??'-' }}</p>
      @if(session('user_id')!=$user->user_id)
      <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
        <a href="{{ route('users.resetPasswordForm',$user->user_id) }}" class="btn-miba btn-outline-miba" style="justify-content:center"><i class="fa fa-key"></i> Reset Password</a>
        <form method="POST" action="{{ route('users.destroy',$user->user_id) }}" onsubmit="return confirm('Hapus pengguna ini?')">@csrf @method('DELETE')
          <button class="btn-miba btn-danger-miba" style="width:100%;justify-content:center"><i class="fa fa-trash"></i> Hapus</button>
        </form>
      </div>
      @endif
      <a href="{{ route('users.index') }}" class="btn-miba btn-ghost-miba" style="width:100%;justify-content:center;margin-top:8px"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
  </div>
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-user"></i> Detail Pengguna</div></div>
    <div class="miba-card-body">
      <table class="miba-table">
        <tr><td style="width:180px;font-weight:500;color:var(--text-muted)">Nama Lengkap</td><td>{{ $user->user_full_name }}</td></tr>
        <tr><td>Email</td><td>{{ $user->user_email }}</td></tr>
        <tr><td>Role</td><td><span class="badge-miba badge-info">{{ $user->role->role_name??'-' }}</span></td></tr>
        <tr><td>Deskripsi</td><td>{{ $user->user_description ?: '-' }}</td></tr>
        <tr><td>Status</td><td><span class="badge-miba badge-success">Aktif</span></td></tr>
      </table>
    </div>
  </div>
</div>
@endsection