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
        <div class="col-md-5">
          <div class="form-group">
            <label class="control-label">Pilih Tahun Pelajaran</label>
            <select class="form-control" name="n" required>
              <option value="">-- Pilih Tahun --</option>
              @foreach($periods as $p)
                <option value="{{ $p->period_id }}" {{ request('n')==$p->period_id?'selected':'' }}>
                  {{ $p->period_start }}/{{ $p->period_end }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label class="control-label">NIS Siswa</label>
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
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">&nbsp;</label>
            <a href="{{ route('payout.index') }}" class="btn btn-default btn-block">Reset</a>
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
      @if(request('n') && request('r'))
        <a href="{{ route('payout.cetakTagihan', ['n'=>request('n'),'r'=>request('r')]) }}"
           target="_blank" class="btn btn-warning btn-xs pull-right">
          <i class="fa fa-print"></i> Cetak Semua Tagihan
        </a>
      @endif
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-striped">
            <tr><td width="150">NIS</td><td>: <strong>{{ $student->student_nis }}</strong></td></tr>
            <tr><td>Nama Siswa</td><td>: <strong>{{ $student->student_full_name }}</strong></td></tr>
            <tr><td>Kelas</td><td>: {{ $student->class->class_name ?? '-' }}</td></tr>
            <tr><td>Unit Pendidikan</td><td>: {{ $student->majors->majors_name ?? '-' }}</td></tr>
          </table>
        </div>
        <div class="col-md-6">
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Tagihan</span>
              <span class="info-box-number">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
              <div class="progress"><div class="progress-bar" style="width: {{ $totalTagihan > 0 ? round(($totalBayar/$totalTagihan)*100) : 0 }}%"></div></div>
              <span class="progress-description">Sudah Bayar: Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Daftar Pembayaran Bulanan --}}
  @if(count($bulanData) > 0)
    <div class="row">
      @foreach($bulanData as $item)
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">
              <i class="fa fa-calendar"></i>
              {{ $item['payment']->pos->pos_name ?? '-' }}
            </h3>
            <a href="{{ route('payout.bayar', [$item['payment']->payment_id, $student->student_id]) }}"
               class="btn btn-primary btn-xs pull-right">
              <i class="fa fa-arrow-right"></i> Kelola Pembayaran
            </a>
          </div>
          <div class="box-body">
            <table class="table table-bordered table-condensed">
              @foreach($item['bulans'] as $b)
              <tr>
                <td><strong>{{ $b->month->month_name }}</strong></td>
                <td class="text-center {{ $b->bulan_status ? 'bg-green' : 'bg-red' }}" style="color:white">
                  @if($b->bulan_status)
                    <i class="fa fa-check"></i> {{ \Carbon\Carbon::parse($b->bulan_date_pay)->format('d/m/Y') }}
                  @else
                    Rp {{ number_format($b->bulan_bill, 0, ',', '.') }}
                  @endif
                </td>
                <td class="text-center">
                  @if($b->bulan_status)
                    <a href="{{ route('payout.cetak', $b->bulan_id) }}" target="_blank"
                       class="btn btn-xs btn-default"><i class="fa fa-print"></i></a>
                  @endif
                </td>
              </tr>
              @endforeach
            </table>
            <div class="text-right">
              <small class="text-muted">
                Bayar: Rp {{ number_format($item['sudah_bayar'], 0, ',', '.') }} /
                Total: Rp {{ number_format($item['total'], 0, ',', '.') }}
              </small>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  @else
    <div class="alert alert-info">
      <i class="fa fa-info-circle"></i>
      Belum ada data pembayaran untuk siswa ini di tahun pelajaran yang dipilih.
    </div>
  @endif

@elseif(request('r'))
  <div class="alert alert-warning">
    <i class="fa fa-warning"></i> Siswa dengan NIS <strong>{{ request('r') }}</strong> tidak ditemukan.
  </div>
@endif

@endsection
