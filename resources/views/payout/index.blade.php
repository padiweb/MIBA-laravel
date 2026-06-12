@extends('layouts.app')
@section('content')

{{-- Form Pencarian --}}
<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title">Cari Transaksi Pembayaran</h3>
    <a href="{{ route('student.index') }}" class="btn btn-danger btn-xs pull-right">
      <i class="fa fa-navicon"></i> Referensi Data Siswa
    </a>
  </div>
  <div class="box-body">
    <form method="GET" class="form-horizontal">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Pilih Tahun Pelajaran</label>
            <select class="form-control" name="n">
              <option value="">-- Semua Tahun --</option>
              @foreach($periods as $p)
                <option value="{{ $p->period_id }}" {{ request('n')==$p->period_id?'selected':'' }}>
                  {{ $p->period_start }}/{{ $p->period_end }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Input Berdasarkan NIS Siswa</label>
            <div class="input-group">
              <input type="text" class="form-control" name="r" autofocus
                     value="{{ request('r') }}" placeholder="Masukkan NIS Siswa" required>
              <span class="input-group-btn">
                <button class="btn btn-success" type="submit">
                  <i class="fa fa-search"></i> Cari Data
                </button>
              </span>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

@if($student)
  {{-- Info Siswa --}}
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Informasi Siswa</h3>
      @if(request('n'))
        <a href="{{ route('payout.printBill', ['n'=>request('n'),'r'=>request('r')]) }}"
           target="_blank" class="btn btn-warning btn-xs pull-right">
          <i class="fa fa-print"></i> Cetak Semua Tagihan
        </a>
      @endif
    </div>
    <div class="box-body">
      <div class="col-md-9">
        <table class="table table-striped">
          <tbody>
            <tr>
              <td width="200">Tahun Pelajaran</td><td width="4">:</td>
              <td>
                @foreach($periods as $p)
                  @if(request('n')==$p->period_id)<strong>{{ $p->period_start }}/{{ $p->period_end }}</strong>@endif
                @endforeach
              </td>
            </tr>
            <tr><td>NIS</td><td>:</td><td>{{ $student->student_nis }}</td></tr>
            <tr><td>Nama Siswa</td><td>:</td><td><strong>{{ $student->student_full_name }}</strong></td></tr>
            <tr>
              <td>Kelas</td><td>:</td>
              <td>{{ $student->class->class_name ?? '-' }}@if(($app_level ?? '')=='senior') {{ $student->majors->majors_name ?? '' }}@endif</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-3">
        @if($student->student_img)
          <img src="{{ asset('uploads/student/'.$student->student_img) }}" class="img-thumbnail img-responsive">
        @else
          <img src="{{ asset('media/img/user.png') }}" class="img-thumbnail img-responsive">
        @endif
      </div>
    </div>
  </div>

  <div class="row">
    {{-- Transaksi Terakhir --}}
    <div class="col-md-6">
      <div class="box box-success">
        <div class="box-header with-border"><h3 class="box-title">Transaksi Terakhir</h3></div>
        <div class="box-body">
          <table class="table table-responsive table-bordered" style="white-space:nowrap">
            <tr class="success"><th>Pembayaran</th><th>Tagihan</th><th>Tanggal</th></tr>
            @forelse($lastTrx as $trx)
            <tr>
              <td>
                @if($trx->bulan_bulan_id)
                  {{ $trx->bulan->payment->pos->pos_name ?? '-' }} - T.P {{ $trx->bulan->payment->period->period_start ?? '' }}/{{ $trx->bulan->payment->period->period_end ?? '' }} ({{ $trx->bulan->month->month_name ?? '' }})
                @else
                  {{ $trx->bebasPay->bebas->payment->pos->pos_name ?? '-' }} - T.A {{ $trx->bebasPay->bebas->payment->period->period_start ?? '' }}/{{ $trx->bebasPay->bebas->payment->period->period_end ?? '' }}
                @endif
              </td>
              <td>
                @if($trx->bulan_bulan_id)
                  Rp. {{ number_format($trx->bulan->bulan_bill ?? 0,0,',','.') }}
                @else
                  Rp. {{ number_format($trx->bebasPay->bebas_pay_bill ?? 0,0,',','.') }}
                @endif
              </td>
              <td>{{ \Carbon\Carbon::parse($trx->log_trx_input_date)->locale('id')->isoFormat('D MMMM Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Belum ada transaksi</td></tr>
            @endforelse
          </table>
        </div>
      </div>
    </div>

    {{-- Kalkulator Pembayaran --}}
    <div class="col-md-3">
      <div class="box box-success">
        <div class="box-header with-border"><h3 class="box-title">Pembayaran</h3></div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Total</label>
                <input type="text" class="form-control" value="{{ number_format($cash+$cashb,0,',','.') }}" id="harga">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Dibayar</label>
                <input type="text" class="form-control" value="{{ number_format($cash+$cashb,0,',','.') }}" id="bayar">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Kembalian</label>
            <input type="text" class="form-control" readonly id="kembalian">
          </div>
        </div>
      </div>
    </div>

    {{-- Cetak Bukti --}}
    <div class="col-md-3">
      <div class="box box-success">
        <div class="box-header with-border"><h3 class="box-title">Cetak Bukti Pembayaran</h3></div>
        <div class="box-body">
          <form action="{{ route('payout.cetakBukti') }}" method="GET" target="_blank">
            <input type="hidden" name="n" value="{{ request('n') }}">
            <input type="hidden" name="r" value="{{ request('r') }}">
            <div class="form-group">
              <label>Tanggal Transaksi</label>
              <div class="input-group date">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input class="form-control" required type="text" name="d" value="{{ date('Y-m-d') }}">
              </div>
            </div>
            <button class="btn btn-success btn-block" type="submit">
              <i class="fa fa-print"></i> Cetak Bukti Pembayaran
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Jenis Pembayaran - hanya untuk non-BENDAHARA --}}
  @if(($user_role_id ?? 0) != 3)
  <div class="box box-success">
    <div class="box-header with-border"><h3 class="box-title">Jenis Pembayaran</h3></div>
    <div class="box-body">
      <div class="nav-tabs-custom tab-success">
        <ul class="nav nav-tabs">
          <li class="active bg-success"><a href="#tab_1" data-toggle="tab"><b>Bulanan</b> <i class="fa fa-shopping-cart"></i></a></li>
          <li class="bg-success"><a href="#tab_2" data-toggle="tab"><b>Bebas</b> <i class="fa fa-shopping-cart"></i></a></li>
        </ul>
      </div>
      <div class="tab-content">

        {{-- TAB BULANAN --}}
        <div class="tab-pane active" id="tab_1">
          <div class="box-body table-responsive">
            <table class="table table-bordered" style="white-space:nowrap">
              <thead>
                <tr class="success">
                  <th class="text-center">No.</th><th>Jenis Pembayaran</th><th>Tahun Pelajaran</th><th class="text-center">Bayar</th>
                </tr>
              </thead>
              <tbody>
                @forelse($paymentsList as $i => $p)
                <tr>
                  <td class="text-center">{{ $i+1 }}</td>
                  <td>{{ $p->pos->pos_name ?? '-' }} - T.P {{ $p->period->period_start ?? '' }}/{{ $p->period->period_end ?? '' }}</td>
                  <td class="danger">{{ $p->period->period_start ?? '' }}/{{ $p->period->period_end ?? '' }}</td>
                  <td class="text-center danger">
                    <a href="{{ route('payout.bayar', [$p->payment_id, $student->student_id]) }}" class="btn btn-xs btn-success">
                      <i class="fa fa-dollar"></i> <b>Bayar Bulanan</b>
                    </a>
                  </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Tidak ada data pembayaran bulanan</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- TAB BEBAS --}}
        <div class="tab-pane" id="tab_2">
          <div class="box-body">
            <table class="table table-hover table-responsive table-bordered" style="white-space:nowrap">
              <thead>
                <tr class="success">
                  <th class="text-center">No.</th><th>Jenis Pembayaran</th><th>Total Tagihan</th>
                  <th>Dibayar</th><th class="text-center">Status</th>
                  <th class="text-center">Bayar</th><th class="text-center">Detail Tagihan</th>
                </tr>
              </thead>
              <tbody>
                @forelse($bebasList as $i => $b)
                @php $sisa = $b->bebas_bill - $b->bebas_total_pay; $lunas = $b->bebas_bill == $b->bebas_total_pay; @endphp
                <tr class="{{ $lunas ? 'success' : 'danger' }}">
                  <td class="text-center" style="background-color:#fff">{{ $i+1 }}</td>
                  <td style="background-color:#fff">{{ $b->payment->pos->pos_name ?? '-' }} - T.P {{ $b->payment->period->period_start ?? '' }}/{{ $b->payment->period->period_end ?? '' }}</td>
                  <td>Rp. {{ number_format($sisa,0,',','.') }}</td>
                  <td>Rp. {{ number_format($b->bebas_total_pay,0,',','.') }}</td>
                  <td class="text-center">
                    <a href="#" class="label {{ $lunas?'label-success':'label-danger' }}">{{ $lunas?'Lunas':'Belum Lunas' }}</a>
                  </td>
                  <td class="text-center">
                    <a data-toggle="modal" class="btn btn-success btn-xs {{ $lunas?'disabled':'' }}" href="#addCicilan{{ $b->bebas_id }}">
                      <i class="fa fa-dollar"></i> <b>Bayar Tagihan</b>
                    </a>
                  </td>
                  <td class="text-center">
                    <a data-toggle="modal" class="btn btn-info btn-xs" href="#addRincian{{ $b->bebas_id }}">
                      <i class="fa fa-dollar"></i> <b>Rincian</b>
                    </a>
                  </td>
                </tr>

                {{-- Modal Rincian --}}
                <div class="modal fade" id="addRincian{{ $b->bebas_id }}">
                  <div class="modal-dialog modal-md"><div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">{{ $student->student_nis }} - Detail Tagihan</h4>
                    </div>
                    <div class="modal-body">
                      <label>Keterangan</label>
                      <textarea rows="5" class="form-control" readonly>{{ $b->bebas_desc }}</textarea>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                  </div></div>
                </div>

                {{-- Modal Bayar Tagihan (Cicilan) --}}
                <div class="modal fade" id="addCicilan{{ $b->bebas_id }}">
                  <div class="modal-dialog modal-md"><div class="modal-content">
                    <form method="POST" action="{{ route('payout.payoutBebas') }}">
                      @csrf
                      <input type="hidden" name="bebas_id" value="{{ $b->bebas_id }}">
                      <input type="hidden" name="student_nis" value="{{ $student->student_nis }}">
                      <input type="hidden" name="student_student_id" value="{{ $student->student_id }}">
                      <input type="hidden" name="payment_payment_id" value="{{ $b->payment_payment_id }}">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tambah Pembayaran / Angsuran</h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Nama Pembayaran</label>
                          <input class="form-control" readonly value="{{ $b->payment->pos->pos_name ?? '' }} - T.A {{ $b->payment->period->period_start ?? '' }}/{{ $b->payment->period->period_end ?? '' }}">
                        </div>
                        <div class="form-group">
                          <label>Tanggal</label>
                          <input class="form-control" readonly value="{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}">
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <label>Jumlah Bayar *</label>
                            <input type="number" required name="bebas_pay_bill" class="form-control" placeholder="Jumlah Bayar" max="{{ $sisa }}">
                          </div>
                          <div class="col-md-6">
                            <label>Keterangan *</label>
                            <input type="text" required name="bebas_pay_desc" class="form-control" placeholder="Keterangan">
                          </div>
                        </div>
                        <small class="text-muted">Sisa tagihan: Rp {{ number_format($sisa,0,',','.') }}</small>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                      </div>
                    </form>
                  </div></div>
                </div>
                @empty
                <tr><td colspan="7" class="text-center">Tidak ada data pembayaran bebas</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

@elseif(request('r'))
  <div class="alert alert-warning">
    <i class="fa fa-warning"></i> Siswa dengan NIS <strong>{{ request('r') }}</strong> tidak ditemukan.
  </div>
@endif

@endsection
@push('scripts')
<script>
function calc() {
  var h = ($('#harga').val()||'0').replace(/\D/g,'');
  var b = ($('#bayar').val()||'0').replace(/\D/g,'');
  var total = parseInt(b||0,10) - parseInt(h||0,10);
  $('#kembalian').val(total.toLocaleString('id-ID'));
}
$('#harga, #bayar').on('input', calc);
calc();
</script>
@endpush
