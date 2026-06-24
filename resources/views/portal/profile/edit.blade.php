@extends('portal.layout')
@section('content')

<form method="POST" action="{{ route('portal.profile.update') }}">
  @csrf @method('PUT')

  {{-- Data Pribadi --}}
  <div class="p-card">
    <div class="p-card-body">
      <div class="p-form-section">Data Pribadi</div>

      <div class="p-form-group">
        <label class="p-label">Nama Lengkap</label>
        <input class="p-input p-input-readonly" type="text" value="{{ $student->student_full_name }}" readonly>
      </div>

      <div class="p-form-group">
        <label class="p-label">Jenis Kelamin</label>
        <select name="student_gender" class="p-select">
          <option value="L" {{ $student->student_gender=='L' ? 'selected' : '' }}>Laki-laki</option>
          <option value="P" {{ $student->student_gender=='P' ? 'selected' : '' }}>Perempuan</option>
        </select>
      </div>

      <div class="p-form-group">
        <label class="p-label">Tempat Lahir</label>
        <input name="student_born_place" type="text" class="p-input"
               value="{{ $student->student_born_place }}" placeholder="Tempat lahir">
      </div>

      <div class="p-form-group">
        <label class="p-label">Tanggal Lahir</label>
        <input name="student_born_date" type="text" class="p-input date-pick"
               value="{{ $student->student_born_date }}" placeholder="YYYY-MM-DD">
      </div>

      <div class="p-form-group">
        <label class="p-label">NIK</label>
        <input name="student_hobby" type="text" class="p-input"
               value="{{ $student->student_hobby }}" placeholder="Nomor Induk Kependudukan">
      </div>

      <div class="p-form-group">
        <label class="p-label">No. HP</label>
        <div class="p-input-icon">
          <i class="fa fa-phone"></i>
          <input name="student_phone" type="tel" class="p-input"
                 value="{{ $student->student_phone }}" placeholder="08xx-xxxx-xxxx">
        </div>
      </div>

      <div class="p-form-group" style="margin-bottom:0">
        <label class="p-label">Alamat</label>
        <textarea name="student_address" class="p-textarea" rows="3"
                  placeholder="Alamat tempat tinggal">{{ $student->student_address }}</textarea>
      </div>
    </div>
  </div>

  {{-- Data Keluarga --}}
  <div class="p-card">
    <div class="p-card-body">
      <div class="p-form-section">Data Keluarga</div>

      <div class="p-form-group">
        <label class="p-label">Nama Ibu Kandung</label>
        <input name="student_name_of_mother" type="text" class="p-input"
               value="{{ $student->student_name_of_mother }}" placeholder="Nama ibu">
      </div>

      <div class="p-form-group">
        <label class="p-label">Nama Ayah Kandung</label>
        <input name="student_name_of_father" type="text" class="p-input"
               value="{{ $student->student_name_of_father }}" placeholder="Nama ayah">
      </div>

      <div class="p-form-group" style="margin-bottom:0">
        <label class="p-label">No. HP Orang Tua</label>
        <div class="p-input-icon">
          <i class="fa fa-phone"></i>
          <input name="student_parent_phone" type="tel" class="p-input"
                 value="{{ $student->student_parent_phone }}" placeholder="08xx-xxxx-xxxx">
        </div>
      </div>
    </div>
  </div>

  {{-- Tombol --}}
  <div class="p-form-footer">
    <button type="submit" class="p-btn p-btn-primary p-btn-block">
      <i class="fa fa-save"></i> Simpan Perubahan
    </button>
  </div>
  <div style="margin-top:var(--p-space-2)">
    <a href="{{ route('portal.profile') }}" class="p-btn p-btn-secondary p-btn-block">
      Batal
    </a>
  </div>
</form>

@endsection
