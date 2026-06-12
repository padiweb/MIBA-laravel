@extends('layouts.app')
@section('content')
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">
      Tambah Tarif Pembayaran
      @if($mode=='class') (Berdasarkan Kelas)
      @elseif($mode=='majors') (Berdasarkan Unit Pendidikan)
      @else (Berdasarkan Siswa)
      @endif
    </h3>
  </div>
  <div class="box-body">
    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('payment.storeBulan', [$payment->payment_id, $mode]) }}">
      @csrf
      <div class="row">
        <div class="col-md-5">
          <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">Pilih Target</h3></div>
            <div class="box-body">
              <div class="form-group">
                <label>Jenis Pembayaran</label>
                <input type="text" class="form-control" readonly
                       value="{{ $payment->pos->pos_name ?? '' }} - T.A {{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}">
              </div>
              <div class="form-group">
                <label>Tahun Pelajaran</label>
                <input type="text" class="form-control" readonly value="{{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}">
              </div>
              <div class="form-group">
                <label>Tipe Pembayaran</label>
                <input type="text" class="form-control" readonly value="Bulanan">
              </div>

              @if($mode == 'student')
                <div class="form-group">
                  <label>Siswa <span class="text-danger">*</span></label>
                  <select name="student_id" class="form-control" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $s)
                      <option value="{{ $s->student_id }}">{{ $s->student_nis }} - {{ $s->student_full_name }}</option>
                    @endforeach
                  </select>
                </div>
              @else
                <div class="form-group">
                  <label>Kelas <span class="text-danger">*</span></label>
                  <select name="class_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $c)
                      <option value="{{ $c->class_id }}">{{ $c->class_name }}</option>
                    @endforeach
                  </select>
                </div>
                @if($mode == 'majors')
                <div class="form-group">
                  <label>Unit Pendidikan <span class="text-danger">*</span></label>
                  <select name="majors_id" class="form-control" required>
                    <option value="">-- Pilih Unit Pendidikan --</option>
                    @foreach($majorsList as $m)
                      <option value="{{ $m->majors_id }}">{{ $m->majors_name }}</option>
                    @endforeach
                  </select>
                </div>
                @endif
              @endif
            </div>
          </div>

          <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">Tarif Setiap Bulan Sama</h3></div>
            <div class="box-body">
              <div class="form-group">
                <label>Tarif Bulanan (Rp.)</label>
                <input type="number" placeholder="Masukkan nilai lalu Enter" id="allTarif" class="form-control">
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-7">
          <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">Tarif Setiap Bulan (boleh berbeda)</h3></div>
            <div class="box-body">
              <table class="table">
                @foreach($months as $m)
                  <input type="hidden" name="month_id[]" value="{{ $m->month_id }}">
                  <tr>
                    <td><strong>{{ $m->month_name }}</strong></td>
                    <td><input type="number" id="n{{ $m->month_id }}" name="bulan_bill[]" class="form-control" required></td>
                  </tr>
                @endforeach
              </table>
            </div>
            <div class="box-footer">
              <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
              <a href="{{ route('payment.viewBulan', $payment->payment_id) }}" class="btn btn-default"><i class="fa fa-repeat"></i> Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@push('scripts')
<script>
document.getElementById('allTarif').addEventListener('keypress', function(e){
  if (e.key === 'Enter') {
    e.preventDefault();
    var val = this.value;
    @foreach($months as $m)
      document.getElementById('n{{ $m->month_id }}').value = val;
    @endforeach
  }
});
</script>
@endpush
@endsection
