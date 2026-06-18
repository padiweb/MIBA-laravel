@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title">
      <i class="fa fa-plus"></i> Tambah Tarif Tagihan
      @if($mode=='class') <span style="color:var(--text-muted);font-weight:400">(Berdasarkan Kelas)</span>
      @elseif($mode=='majors') <span style="color:var(--text-muted);font-weight:400">(Berdasarkan Unit Pendidikan)</span>
      @else <span style="color:var(--text-muted);font-weight:400">(Berdasarkan Siswa)</span>
      @endif
    </div>
    <a href="{{ route('payment.viewBebas',$payment->payment_id) }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>

  <div class="miba-card-body">
    @if($errors->any())
      <div class="miba-alert miba-alert-danger">
        <i class="fa fa-exclamation-circle"></i>
        <ul style="margin:0;padding-left:16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('payment.storeBebas', [$payment->payment_id, $mode]) }}">
      @csrf
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

        <div>
          <div class="miba-form-group">
            <label class="miba-label">Jenis Pembayaran</label>
            <input type="text" class="miba-input" readonly style="background:var(--bg)"
                   value="{{ $payment->pos->pos_name ?? '' }} - T.A {{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}">
          </div>
          <div class="miba-form-group">
            <label class="miba-label">Tipe Pembayaran</label>
            <input type="text" class="miba-input" readonly style="background:var(--bg)" value="Bebas">
          </div>

          @if($mode == 'student')
            <div class="miba-form-group">
              <label class="miba-label">Filter Kelas</label>
              <select id="filterClass" class="miba-select">
                <option value="">-- Semua Kelas --</option>
                @foreach($classes as $c)
                  <option value="{{ $c->class_id }}">{{ $c->class_name }}</option>
                @endforeach
              </select>
            </div>
            @if(($app_level ?? '') == 'senior')
            <div class="miba-form-group">
              <label class="miba-label">Filter Unit Pendidikan</label>
              <select id="filterMajors" class="miba-select">
                <option value="">-- Semua Unit --</option>
                @foreach($majorsList as $m)
                  <option value="{{ $m->majors_id }}">{{ $m->majors_name }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="miba-form-group">
              <label class="miba-label">Siswa <span class="req">*</span></label>
              <select name="student_id" id="studentSelect" class="miba-select" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach($students as $s)
                  <option value="{{ $s->student_id }}"
                          data-class="{{ $s->class_class_id }}"
                          data-majors="{{ $s->majors_majors_id }}">
                    {{ $s->student_nis }} — {{ $s->student_full_name }}
                    ({{ $s->class->class_name ?? '-' }}@if(($app_level??'')=='senior' && $s->majors) · {{ $s->majors->majors_name }}@endif)
                  </option>
                @endforeach
              </select>
              <div id="studentCount" style="font-size:11px;color:var(--text-muted);margin-top:4px">{{ $students->count() }} siswa tersedia</div>
            </div>
          @else
            <div class="miba-form-group">
              <label class="miba-label">Kelas <span class="req">*</span></label>
              <select name="class_id" class="miba-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classes as $c)
                  <option value="{{ $c->class_id }}">{{ $c->class_name }}</option>
                @endforeach
              </select>
            </div>
            @if($mode == 'majors')
            <div class="miba-form-group">
              <label class="miba-label">Unit Pendidikan <span class="req">*</span></label>
              <select name="majors_id" class="miba-select" required>
                <option value="">-- Pilih Unit Pendidikan --</option>
                @foreach($majorsList as $m)
                  <option value="{{ $m->majors_id }}">{{ $m->majors_name }}</option>
                @endforeach
              </select>
            </div>
            @endif
          @endif
        </div>

        <div>
          <div class="miba-form-group">
            <label class="miba-label">Total Tagihan (Rp.) <span class="req">*</span></label>
            <input type="number" name="bebas_bill" class="miba-input" required>
          </div>
          <div class="miba-form-group">
            <label class="miba-label">Keterangan</label>
            <textarea name="bebas_desc" class="miba-textarea" rows="4" placeholder="Contoh: Rincian biaya / cicilan ke-1, dst"></textarea>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:8px;margin-top:8px">
        <button type="submit" class="btn-miba btn-primary-miba"><i class="fa fa-save"></i> Simpan</button>
        <a href="{{ route('payment.viewBebas', $payment->payment_id) }}" class="btn-miba btn-ghost-miba">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
@if($mode == 'student')
(function() {
  var filterClass  = document.getElementById('filterClass');
  var filterMajors = document.getElementById('filterMajors');
  var studentSel   = document.getElementById('studentSelect');
  var countLabel   = document.getElementById('studentCount');
  var allOptions   = Array.from(studentSel.options).slice(1);

  function applyFilter() {
    var classVal  = filterClass ? filterClass.value : '';
    var majorsVal = filterMajors ? filterMajors.value : '';
    var visibleCount = 0;

    studentSel.innerHTML = '<option value="">-- Pilih Siswa --</option>';
    allOptions.forEach(function(opt) {
      var matchClass  = !classVal  || opt.dataset.class  === classVal;
      var matchMajors = !majorsVal || opt.dataset.majors === majorsVal;
      if (matchClass && matchMajors) {
        studentSel.appendChild(opt.cloneNode(true));
        visibleCount++;
      }
    });
    countLabel.textContent = visibleCount + ' siswa tersedia' + (classVal || majorsVal ? ' (terfilter)' : '');
  }

  if (filterClass)  filterClass.addEventListener('change', applyFilter);
  if (filterMajors) filterMajors.addEventListener('change', applyFilter);
})();
@endif

$(window).on('resize load', function(){
  if ($(window).width() < 768) {
    $('form > div[style*="grid-template-columns:1fr 1fr"]').css({display:'flex','flex-direction':'column'});
  }
});
</script>
@endpush
