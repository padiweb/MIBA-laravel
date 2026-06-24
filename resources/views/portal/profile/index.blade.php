@extends('portal.layout')
@section('content')

{{-- ── Profile Hero ── --}}
<div class="p-profile-hero">
  <div class="p-profile-avatar-wrap">
    @if($student->student_img)
      <img class="p-profile-avatar" src="{{ asset('uploads/student/'.$student->student_img) }}" alt="Foto">
    @else
      <div class="p-profile-avatar-placeholder">
        {{ strtoupper(substr($student->student_full_name, 0, 1)) }}
      </div>
    @endif
  </div>
  <div class="p-profile-name">{{ $student->student_full_name }}</div>
  <div class="p-profile-nis">NIS {{ $student->student_nis }}</div>
  <div class="p-profile-actions">
    <a href="{{ route('portal.profile.edit') }}" class="p-btn p-btn-outline" style="border-color:rgba(255,255,255,.5);color:white">
      <i class="fa fa-edit"></i> Edit Profil
    </a>
    <a href="{{ route('portal.profile.cpw') }}" class="p-btn p-btn-secondary" style="background:rgba(255,255,255,.15);color:white;border-color:rgba(255,255,255,.2)">
      <i class="fa fa-lock"></i> Password
    </a>
  </div>
</div>

{{-- ── Data Akademik ── --}}
<div class="p-card">
  <div class="p-card-header">
    <div class="p-card-title"><i class="fa fa-graduation-cap"></i> Data Akademik</div>
  </div>
  <div class="p-card-body p0">
    @php
      $akademik = [
        ['fa-id-card',     'NIS',             $student->student_nis],
        ['fa-hashtag',     'NISN',            $student->student_nisn ?: '-'],
        ['fa-th-list',     'Kelas',           $student->class->class_name ?? '-'],
        ['fa-university',  'Unit Pendidikan', $student->majors->majors_name ?? '-'],
      ];
    @endphp
    @foreach($akademik as [$icon, $label, $val])
    <div class="p-info-row">
      <div class="p-info-row-icon"><i class="fa {{ $icon }}"></i></div>
      <div class="p-info-row-content">
        <div class="p-info-row-label">{{ $label }}</div>
        <div class="p-info-row-value">{{ $val }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>

{{-- ── Data Pribadi ── --}}
<div class="p-card">
  <div class="p-card-header">
    <div class="p-card-title"><i class="fa fa-user"></i> Data Pribadi</div>
  </div>
  <div class="p-card-body p0">
    @php
      $born = $student->student_born_date
        ? \Carbon\Carbon::parse($student->student_born_date)->locale('id')->isoFormat('D MMMM Y')
        : '-';
      $pribadi = [
        ['fa-venus-mars',  'Jenis Kelamin',    $student->student_gender == 'L' ? 'Laki-laki' : 'Perempuan'],
        ['fa-map-marker',  'Tempat Lahir',     $student->student_born_place ?: '-'],
        ['fa-calendar',    'Tanggal Lahir',    $born],
        ['fa-id-badge',    'NIK',              $student->student_hobby ?: '-'],
        ['fa-phone',       'No. HP',           $student->student_phone ?: '-'],
        ['fa-home',        'Alamat',           $student->student_address ?: '-'],
      ];
    @endphp
    @foreach($pribadi as [$icon, $label, $val])
    <div class="p-info-row">
      <div class="p-info-row-icon"><i class="fa {{ $icon }}"></i></div>
      <div class="p-info-row-content">
        <div class="p-info-row-label">{{ $label }}</div>
        <div class="p-info-row-value">{{ $val }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>

{{-- ── Data Keluarga ── --}}
<div class="p-card">
  <div class="p-card-header">
    <div class="p-card-title"><i class="fa fa-users"></i> Data Keluarga</div>
  </div>
  <div class="p-card-body p0">
    @php
      $keluarga = [
        ['fa-female', 'Nama Ibu',          $student->student_name_of_mother ?: '-'],
        ['fa-male',   'Nama Ayah',         $student->student_name_of_father ?: '-'],
        ['fa-phone',  'No. HP Orang Tua',  $student->student_parent_phone ?: '-'],
      ];
    @endphp
    @foreach($keluarga as [$icon, $label, $val])
    <div class="p-info-row">
      <div class="p-info-row-icon"><i class="fa {{ $icon }}"></i></div>
      <div class="p-info-row-content">
        <div class="p-info-row-label">{{ $label }}</div>
        <div class="p-info-row-value">{{ $val }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>

{{-- ── Menu Pengaturan ── --}}
<div class="p-card">
  <div class="p-nav-section">
    <a href="{{ route('portal.profile.edit') }}" class="p-nav-row">
      <div class="p-nav-row-icon" style="background:#dbeafe;color:#2563eb"><i class="fa fa-edit"></i></div>
      <div class="p-nav-row-text">
        <div class="p-nav-row-title">Edit Profil</div>
        <div class="p-nav-row-desc">Ubah data pribadi dan keluarga</div>
      </div>
      <i class="fa fa-chevron-right p-nav-row-arrow"></i>
    </a>
    <a href="{{ route('portal.profile.cpw') }}" class="p-nav-row">
      <div class="p-nav-row-icon" style="background:#f3e8ff;color:#9333ea"><i class="fa fa-lock"></i></div>
      <div class="p-nav-row-text">
        <div class="p-nav-row-title">Ganti Password</div>
        <div class="p-nav-row-desc">Ubah kata sandi akun Anda</div>
      </div>
      <i class="fa fa-chevron-right p-nav-row-arrow"></i>
    </a>
    <a href="{{ route('portal.logout') }}" class="p-nav-row" onclick="return confirm('Yakin ingin keluar?')">
      <div class="p-nav-row-icon" style="background:#fee2e2;color:#dc2626"><i class="fa fa-sign-out"></i></div>
      <div class="p-nav-row-text">
        <div class="p-nav-row-title" style="color:#dc2626">Keluar</div>
        <div class="p-nav-row-desc">Logout dari akun ini</div>
      </div>
      <i class="fa fa-chevron-right p-nav-row-arrow"></i>
    </a>
  </div>
</div>

@endsection
