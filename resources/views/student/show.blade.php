@extends('layouts.app')
@section('content')
<div style="display:grid;grid-template-columns:280px 1fr;gap:16px;align-items:start">

  {{-- Panel Kiri --}}
  <div class="miba-card">
    <div class="miba-card-body" style="text-align:center">
      @if($student->student_img)
        <img src="{{ asset('uploads/student/'.$student->student_img) }}"
             style="width:100px;height:100px;object-fit:cover;border-radius:50%;border:3px solid var(--border)">
      @else
        <div style="width:100px;height:100px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;margin:0 auto;color:var(--primary);font-size:36px">
          <i class="fa fa-user"></i>
        </div>
      @endif
      <h3 style="margin:12px 0 4px;font-size:16px;font-weight:700">{{ $student->student_full_name }}</h3>
      <div style="font-size:12px;color:var(--text-muted);margin-bottom:8px">{{ $student->student_nis }}</div>
      <span class="badge-miba {{ $student->student_status ? 'badge-success' : 'badge-muted' }}">
        {{ $student->student_status ? 'Aktif' : 'Nonaktif' }}
      </span>
      <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
        <a href="{{ route('student.edit',$student->student_id) }}" class="btn-miba btn-accent-miba" style="justify-content:center">
          <i class="fa fa-edit"></i> Edit
        </a>
        <a href="{{ route('student.resetPasswordForm',$student->student_id) }}" class="btn-miba btn-outline-miba" style="justify-content:center">
          <i class="fa fa-key"></i> Reset Password
        </a>
        <a href="{{ route('student.printPdf',$student->student_id) }}" target="_blank" class="btn-miba btn-ghost-miba" style="justify-content:center">
          <i class="fa fa-id-card"></i> Cetak Kartu
        </a>
        <a href="{{ route('student.index') }}" class="btn-miba btn-ghost-miba" style="justify-content:center">
          <i class="fa fa-arrow-left"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  {{-- Panel Kanan --}}
  <div class="miba-card">
    <div class="miba-card-header">
      <div class="miba-card-title"><i class="fa fa-user"></i> Detail Siswa</div>
    </div>
    <div class="miba-card-body">
      <div class="miba-tabs">
        <a class="miba-tab active" href="#" onclick="switchTab(this,'tab-pribadi')">Data Pribadi</a>
        <a class="miba-tab" href="#" onclick="switchTab(this,'tab-sekolah')">Data Sekolah</a>
        <a class="miba-tab" href="#" onclick="switchTab(this,'tab-keluarga')">Data Keluarga</a>
      </div>

      <div id="tab-pribadi" class="miba-tab-content active">
        <table class="miba-table">
          <tr>
            <td style="width:180px;font-weight:500;color:var(--text-muted)">Nama Lengkap</td>
            <td>{{ $student->student_full_name }}</td>
          </tr>
          <tr>
            <td>Jenis Kelamin</td>
            <td>{{ $student->student_gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
          </tr>
          <tr>
            <td>Tempat Lahir</td>
            <td>{{ $student->student_born_place ?: '-' }}</td>
          </tr>
          <tr>
            <td>Tanggal Lahir</td>
            <td>
              @if($student->student_born_date)
                {{ \Carbon\Carbon::parse($student->student_born_date)->locale('id')->isoFormat('D MMMM Y') }}
              @else
                -
              @endif
            </td>
          </tr>
          <tr><td>NIK</td><td>{{ $student->student_hobby ?: '-' }}</td></tr>
          <tr><td>No. HP</td><td>{{ $student->student_phone ?: '-' }}</td></tr>
          <tr><td>Alamat</td><td>{{ $student->student_address ?: '-' }}</td></tr>
        </table>
      </div>

      <div id="tab-sekolah" class="miba-tab-content">
        <table class="miba-table">
          <tr><td style="width:180px;font-weight:500;color:var(--text-muted)">NIS</td><td style="font-weight:700">{{ $student->student_nis }}</td></tr>
          <tr><td>NISN</td><td>{{ $student->student_nisn ?: '-' }}</td></tr>
          <tr><td>Kelas</td><td>{{ $student->class->class_name ?? '-' }}</td></tr>
          @if(($app_level ?? '') == 'senior')
          <tr><td>Unit Pendidikan</td><td>{{ $student->majors->majors_name ?? '-' }}</td></tr>
          @endif
        </table>
      </div>

      <div id="tab-keluarga" class="miba-tab-content">
        <table class="miba-table">
          <tr><td style="width:180px;font-weight:500;color:var(--text-muted)">Nama Ayah</td><td>{{ $student->student_name_of_father ?: '-' }}</td></tr>
          <tr><td>Nama Ibu</td><td>{{ $student->student_name_of_mother ?: '-' }}</td></tr>
          <tr><td>No. HP Orang Tua</td><td>{{ $student->student_parent_phone ?: '-' }}</td></tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
function switchTab(el, id) {
  $('.miba-tab').removeClass('active');
  $('.miba-tab-content').removeClass('active');
  $(el).addClass('active');
  $('#' + id).addClass('active');
  return false;
}
</script>
@endpush
