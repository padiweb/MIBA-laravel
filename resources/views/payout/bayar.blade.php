@extends('layouts.app')
@section('content')

<div class="row">
  {{-- Kiri: Daftar Bulan --}}
  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Transaksi Bulanan — {{ $payment->pos->pos_name ?? '' }}</h3>
        <a href="{{ route('payout.index', ['n'=>$payment->period_period_id,'r'=>$student->student_nis]) }}"
           class="btn btn-danger btn-xs pull-right">
          <i class="fa fa-random"></i> KEMBALI
        </a>
      </div>
      <div class="box-body">
        <table class="table table-hover table-striped table-bordered">
          <tbody>
            @foreach($bulans as $b)
            <tr>
              <td><strong>{{ $b->month->month_name }}</strong></td>
              <td class="{{ $b->bulan_status ? 'danger' : 'success' }} text-center">
                <a href="{{ $b->bulan_status
                    ? route('payout.unpay', [$payment->payment_id, $student->student_id, $b->bulan_id])
                    : route('payout.pay',   [$payment->payment_id, $student->student_id, $b->bulan_id]) }}"
                   onclick="return confirm('{{ $b->bulan_status ? 'Batalkan pembayaran bulan '.$b->month->month_name.'?' : 'Bayar bulan '.$b->month->month_name.'?' }}')"
                   class="btn btn-xs btn-{{ $b->bulan_status ? 'danger' : 'success' }}">
                  <strong>
                    {{ $b->bulan_status
                        ? '✓ ' . \Carbon\Carbon::parse($b->bulan_date_pay)->format('d/m/y')
                        : 'Rp '.number_format($b->bulan_bill, 0, ',', '.') }}
                  </strong>
                </a>
              </td>
              <td class="text-center">
                @if($b->bulan_status)
                  <a href="{{ route('payout.cetak', $b->bulan_id) }}" target="_blank"
                     class="btn btn-xs btn-warning">
                    <i class="fa fa-print"></i> Cetak
                  </a>
                @endif
                <button class="btn btn-xs btn-info"
                        onclick="showDescModal({{ $b->bulan_id }}, {{ $payment->payment_id }}, {{ $student->student_id }}, '{{ $b->bulan_pay_desc }}')">
                  <i class="fa fa-file-text-o"></i> Keterangan
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="text-right">
                <strong>
                  Sudah Bayar: Rp {{ number_format($bulans->where('bulan_status',1)->sum('bulan_bill'), 0, ',', '.') }} /
                  Total: Rp {{ number_format($bulans->sum('bulan_bill'), 0, ',', '.') }}
                </strong>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  {{-- Kanan: Identitas Siswa --}}
  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Detail Identitas</h3>
        @if(($user_role_id ?? 0) == 1)
          <a href="{{ route('payment.editBulan', [$payment->payment_id, $student->student_id]) }}" class="btn btn-primary btn-xs pull-right">
            <i class="fa fa-edit"></i> Edit Tarif Pembayaran
          </a>
        @endif
      </div>
      <div class="box-body">
        @if($student->student_img)
          <div class="text-center" style="margin-bottom:10px">
            <img src="{{ asset('uploads/student/'.$student->student_img) }}"
                 class="img-circle" style="width:80px;height:80px;object-fit:cover">
          </div>
        @endif
        <table class="table table-striped">
          <tr><td width="150">NIS</td><td>{{ $student->student_nis }}</td></tr>
          <tr><td>Nama</td><td><strong>{{ $student->student_full_name }}</strong></td></tr>
          <tr><td>Kelas</td><td>{{ $student->class->class_name ?? '-' }}</td></tr>
          <tr><td>Unit Pendidikan</td><td>{{ $student->majors->majors_name ?? '-' }}</td></tr>
          <tr><td>Tahun Pelajaran</td>
            <td>{{ $payment->period->period_start ?? '-' }}/{{ $payment->period->period_end ?? '-' }}</td>
          </tr>
          <tr><td>Jenis Pembayaran</td><td>{{ $payment->pos->pos_name ?? '-' }}</td></tr>
          <tr><td>Tipe</td>
            <td><span class="label label-info">{{ $payment->payment_type }}</span></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Keterangan --}}
<div class="modal fade" id="descModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('payout.updateDesc') }}">
        @csrf
        <input type="hidden" name="bulan_id" id="desc_bulan_id">
        <input type="hidden" name="payment_id" id="desc_payment_id">
        <input type="hidden" name="student_id" id="desc_student_id">
        <div class="modal-header">
          <h4 class="modal-title">Tambah/Edit Keterangan</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="bulan_pay_desc" id="desc_text" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function showDescModal(bulanId, paymentId, studentId, desc) {
  $('#desc_bulan_id').val(bulanId);
  $('#desc_payment_id').val(paymentId);
  $('#desc_student_id').val(studentId);
  $('#desc_text').val(desc);
  $('#descModal').modal('show');
}
</script>
@endpush
@endsection
