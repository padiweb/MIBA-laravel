@extends('portal.layout')
@section('content')
<div style="display:grid;grid-template-columns:260px 1fr;gap:16px;align-items:start">
  <div class="miba-card">
    <div class="miba-card-body" style="text-align:center">
      @if($student->student_img)
        <img src="{{ asset('uploads/student/'.$student->student_img) }}" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--border)">
      @else
        <div style="width:90px;height:90px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;margin:0 auto;color:var(--primary);font-size:32px"><i class="fa fa-user"></i></div>
      @endif
      <h3 style="font-size:15px;font-weight:700;margin:12px 0 4px">{{ $student->student_full_name }}</h3>
      <p style="font-size:12px;color:var(--text-muted)">{{ $student->student_nis }}</p>
      <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
        <a href="{{ route('portal.profile.edit') }}" class="btn-miba btn-primary-miba" style="justify-content:center"><i class="fa fa-edit"></i> Edit Profil</a>
        <a href="{{ route('portal.profile.cpw') }}" class="btn-miba btn-outline-miba" style="justify-content:center"><i class="fa fa-key"></i> Ganti Password</a>
      </div>
    </div>
  </div>
  <div class="miba-card">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-user"></i> Data Diri</div></div>
    <div class="miba-card-body">
      <table class="miba-table">
        <tr><td style="width:180px;font-weight:500;color:var(--text-muted)">NIS</td><td style="font-weight:700">{{ $student->student_nis }}</td></tr>
        <tr><td>NISN</td><td>{{ $student->student_nisn ?: '-' }}</td></tr>
        <tr><td>Nama Lengkap</td><td>{{ $student->student_full_name }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>{{ $student->student_gender=='L'?'Laki-laki':'Perempuan' }}</td></tr>
        <tr><td>Tempat, Tgl Lahir</td><td>{{ $student->student_born_place }}, {{ $student->student_born_date?\Carbon\Carbon::parse($student->student_born_date)->locale('id')->isoFormat('D MMMM Y'):'-' }}</td></tr>
        <tr><td>No. HP</td><td>{{ $student->student_phone ?: '-' }}</td></tr>
        <tr><td>Kelas</td><td>{{ $student->class->class_name??'-' }}</td></tr>
        @if(($app_level??'')=='senior')<tr><td>Unit Sekolah</td><td>{{ $student->majors->majors_name??'-' }}</td></tr>@endif
        <tr><td>Nama Ibu</td><td>{{ $student->student_name_of_mother ?: '-' }}</td></tr>
        <tr><td>Nama Ayah</td><td>{{ $student->student_name_of_father ?: '-' }}</td></tr>
        <tr><td>No. HP Orang Tua</td><td>{{ $student->student_parent_phone ?: '-' }}</td></tr>
        <tr><td>Alamat</td><td>{{ $student->student_address ?: '-' }}</td></tr>
      </table>
    </div>
  </div>
</div>
@endsection