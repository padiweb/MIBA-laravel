@extends('layouts.app')
@section('content')
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">
      Tambah Tarif Tagihan
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

    <form method="POST" action="{{ route('payment.storeBebas', [$payment->payment_id, $mode]) }}">
      @csrf
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Jenis Pembayaran</label>
            <input type="text" class="form-control" readonly
                   value="{{ $payment->pos->pos_name ?? '' }} - T.A {{ $payment->period->period_start ?? '' }}/{{ $payment->period->period_end ?? '' }}">
          </div>
          <div class="form-group">
            <label>Tipe Pembayaran</label>
            <input type="text" class="form-control" readonly value="Bebas">
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

        <div class="col-md-6">
          <div class="form-group">
            <label>Total Tagihan (Rp.) <span class="text-danger">*</span></label>
            <input type="number" name="bebas_bill" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="bebas_desc" class="form-control" rows="4" placeholder="Contoh: Rincian biaya / cicilan ke-1, dst"></textarea>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
      <a href="{{ route('payment.viewBebas', $payment->payment_id) }}" class="btn btn-default"><i class="fa fa-repeat"></i> Cancel</a>
    </form>
  </div>
</div>
@endsection
