@extends('layouts.app')
@section('content')

<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-exchange"></i> Transaksi Siswa</div>
    <a href="{{ route('student.index') }}" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-users"></i> Referensi Siswa</a>
  </div>
  <div class="miba-card-body">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
      <select name="n" class="miba-select" style="width:200px">
        <option value="">-- Semua Tahun Pelajaran --</option>
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ request('n')==$p->period_id?'selected':'' }}>{{ $p->period_start }}/{{ $p->period_end }}</option>
        @endforeach
      </select>
      <div class="miba-input-group" style="flex:1;min-width:200px">
        <div class="miba-input-icon" style="flex:1">
          <i class="fa fa-search icon"></i>
          <input type="text" name="r" class="miba-input" autofocus value="{{ request('r') }}" placeholder="Masukkan NIS Siswa..." required style="border-radius:8px 0 0 8px">
        </div>
        <button class="btn-miba btn-primary-miba" style="border-radius:0 8px 8px 0"><i class="fa fa-search"></i> Cari</button>
      </div>
    </form>
  </div>
</div>

@if($student)
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-user"></i> {{ $student->student_full_name }}</div>
    @if(request('n'))
    <a href="{{ route('payout.printBill',['n'=>request('n'),'r'=>request('r')]) }}" target="_blank" class="btn-miba btn-miba-sm btn-ghost-miba"><i class="fa fa-print"></i> Cetak Tagihan</a>
    @endif
  </div>
  <div class="miba-card-body">
    <div style="display:grid;grid-template-columns:1fr auto;gap:16px">
      <table class="miba-table">
        @foreach([['Tahun Pelajaran',collect($periods)->firstWhere('period_id',request('n'))?->period_start.'/'.collect($periods)->firstWhere('period_id',request('n'))?->period_end??'-'],['NIS',$student->student_nis],['Nama',$student->student_full_name],['Kelas',$student->class->class_name??'-']] as [$l,$v])
        <tr><td style="width:160px;font-weight:500;color:var(--text-muted)">{{ $l }}</td><td>{{ $v }}</td></tr>
        @endforeach
      </table>
      @if($student->student_img)
        <img src="{{ asset('uploads/student/'.$student->student_img) }}" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
      @else
        <div style="width:70px;height:70px;border-radius:50%;background:var(--primary-xlight);display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:24px"><i class="fa fa-user"></i></div>
      @endif
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
  <div class="miba-card" style="margin:0">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-history"></i> Transaksi Terakhir</div></div>
    <div class="miba-card-body p0">
      <table class="miba-table">
        @forelse($lastTrx as $trx)
        <tr>
          <td style="font-size:12px">
            @if($trx->bulan_bulan_id)
              {{ $trx->bulan->payment->pos->pos_name??'-' }} ({{ $trx->bulan->month->month_name??'' }})
            @else
              {{ $trx->bebasPay->bebas->payment->pos->pos_name??'-' }}
            @endif
          </td>
          <td style="font-size:11px;color:var(--text-muted);white-space:nowrap">
            {{ \Carbon\Carbon::parse($trx->log_trx_input_date)->format('d/m/Y') }}
          </td>
        </tr>
        @empty
        <tr><td style="text-align:center;padding:16px;color:var(--text-muted)">Belum ada transaksi</td></tr>
        @endforelse
      </table>
    </div>
  </div>

  <div class="miba-card" style="margin:0">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-calculator"></i> Kalkulator</div></div>
    <div class="miba-card-body">
      <div class="miba-form-group">
        <label class="miba-label">Total</label>
        <input type="text" class="miba-input" value="{{ number_format($cash+$cashb,0,',','.') }}" id="harga">
      </div>
      <div class="miba-form-group">
        <label class="miba-label">Dibayar</label>
        <input type="text" class="miba-input" value="{{ number_format($cash+$cashb,0,',','.') }}" id="bayar">
      </div>
      <div class="miba-form-group">
        <label class="miba-label">Kembalian</label>
        <input type="text" class="miba-input" readonly id="kembalian" style="font-weight:700;color:var(--primary)">
      </div>
    </div>
  </div>

  <div class="miba-card" style="margin:0">
    <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-print"></i> Cetak Bukti</div></div>
    <div class="miba-card-body">
      <form action="{{ route('payout.cetakBukti') }}" method="GET" target="_blank">
        <input type="hidden" name="n" value="{{ request('n') }}">
        <input type="hidden" name="r" value="{{ request('r') }}">
        <div class="miba-form-group">
          <label class="miba-label">Tanggal Transaksi</label>
          <input class="miba-input date-pick" required type="text" name="d" value="{{ date('Y-m-d') }}">
        </div>
        <button class="btn-miba btn-primary-miba" style="width:100%;justify-content:center"><i class="fa fa-print"></i> Cetak Kwitansi</button>
      </form>
    </div>
  </div>
</div>

@if(($user_role_id??0) != 3)
<div class="miba-card">
  <div class="miba-card-header"><div class="miba-card-title"><i class="fa fa-money"></i> Jenis Pembayaran</div></div>
  <div class="miba-card-body p0">
    <div class="miba-tabs" style="padding:0 16px;margin:0;border-bottom:2px solid var(--border)">
      <a class="miba-tab active" href="#" onclick="switchTab(this,'tab-bulanan')">Bulanan</a>
      <a class="miba-tab" href="#" onclick="switchTab(this,'tab-bebas')">Bebas / Lainnya</a>
    </div>

    <div id="tab-bulanan" class="miba-tab-content active" style="padding:16px">
      <div class="miba-table-wrap">
        <table class="miba-table">
          <thead><tr><th>No</th><th>Jenis Pembayaran</th><th>Tahun Pelajaran</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($paymentsList as $i => $p)
            <tr>
              <td style="color:var(--text-muted)">{{ $i+1 }}</td>
              <td style="font-weight:500">{{ $p->pos->pos_name??'-' }}</td>
              <td>T.P {{ $p->period->period_start??'' }}/{{ $p->period->period_end??'' }}</td>
              <td><a href="{{ route('payout.bayar',[$p->payment_id,$student->student_id]) }}" class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-dollar"></i> Bayar</a></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted)">Tidak ada data pembayaran bulanan</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div id="tab-bebas" class="miba-tab-content" style="padding:16px">
      <div class="miba-table-wrap">
        <table class="miba-table">
          <thead><tr><th>No</th><th>Jenis Pembayaran</th><th>Total Tagihan</th><th>Dibayar</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($bebasList as $i => $b)
            @php $sisa=$b->bebas_bill-$b->bebas_total_pay; $lunas=$b->bebas_bill==$b->bebas_total_pay; @endphp
            <tr>
              <td style="color:var(--text-muted)">{{ $i+1 }}</td>
              <td style="font-weight:500">{{ $b->payment->pos->pos_name??'-' }} <small style="color:var(--text-muted)">T.P {{ $b->payment->period->period_start??'' }}/{{ $b->payment->period->period_end??'' }}</small></td>
              <td>Rp {{ number_format($sisa,0,',','.') }}</td>
              <td>Rp {{ number_format($b->bebas_total_pay,0,',','.') }}</td>
              <td>
                <a href="#" data-toggle="modal" data-target="#riwayat{{ $b->bebas_id }}" class="badge-miba {{ $lunas?'badge-success':'badge-danger' }}" style="cursor:pointer">{{ $lunas?'Lunas':'Belum Lunas' }}</a>
              </td>
              <td>
                <a data-toggle="modal" class="btn-miba btn-miba-xs {{ $lunas?'btn-ghost-miba':'btn-primary-miba' }}" href="#addCicilan{{ $b->bebas_id }}">
                  <i class="fa fa-dollar"></i> Bayar
                </a>
              </td>
            </tr>

            {{-- Modal Rincian --}}
            <div class="modal fade" id="addRincian{{ $b->bebas_id }}">
              <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
                <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Rincian Tagihan</h4><button class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body"><textarea rows="5" class="miba-input" readonly>{{ $b->bebas_desc }}</textarea></div>
                <div class="modal-footer"><button class="btn-miba btn-ghost-miba" data-dismiss="modal">Tutup</button></div>
              </div></div>
            </div>

            {{-- Modal Bayar --}}
            <div class="modal fade" id="addCicilan{{ $b->bebas_id }}">
              <div class="modal-dialog"><div class="modal-content" style="border-radius:12px;overflow:hidden">
                <form method="POST" action="{{ route('payout.payoutBebas') }}">
                  @csrf
                  <input type="hidden" name="bebas_id" value="{{ $b->bebas_id }}">
                  <input type="hidden" name="student_nis" value="{{ $student->student_nis }}">
                  <input type="hidden" name="student_student_id" value="{{ $student->student_id }}">
                  <input type="hidden" name="payment_payment_id" value="{{ $b->payment_payment_id }}">
                  <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Bayar Tagihan / Angsuran</h4><button class="close" data-dismiss="modal">&times;</button></div>
                  <div class="modal-body">
                    <div class="miba-form-group">
                      <label class="miba-label">Nama Pembayaran</label>
                      <input class="miba-input" readonly value="{{ $b->payment->pos->pos_name??'' }}">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                      <div class="miba-form-group">
                        <label class="miba-label">Jumlah Bayar <span class="req">*</span></label>
                        <input type="number" required name="bebas_pay_bill" class="miba-input" max="{{ $sisa }}">
                      </div>
                      <div class="miba-form-group">
                        <label class="miba-label">Keterangan <span class="req">*</span></label>
                        <input type="text" required name="bebas_pay_desc" class="miba-input">
                      </div>
                    </div>
                    <div style="font-size:12px;color:var(--text-muted)">Sisa tagihan: <strong>Rp {{ number_format($sisa,0,',','.') }}</strong></div>
                  </div>
                  <div class="modal-footer"><button type="submit" class="btn-miba btn-primary-miba">Simpan</button><button type="button" class="btn-miba btn-ghost-miba" data-dismiss="modal">Batal</button></div>
                </form>
              </div></div>
            </div>

            {{-- Modal Riwayat --}}
            <div class="modal fade" id="riwayat{{ $b->bebas_id }}">
              <div class="modal-dialog modal-lg"><div class="modal-content" style="border-radius:12px;overflow:hidden">
                <div class="modal-header" style="border-bottom:1px solid var(--border)"><h4 class="modal-title">Riwayat Angsuran</h4><button class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body" style="padding:0"><iframe src="{{ route('payout.riwayatBebas',$b->bebas_id) }}" width="100%" height="350" style="border:none"></iframe></div>
                <div class="modal-footer"><button class="btn-miba btn-ghost-miba" data-dismiss="modal">Tutup</button></div>
              </div></div>
            </div>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Tidak ada data tagihan bebas</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endif

@elseif(request('r'))
<div class="miba-alert miba-alert-warning"><i class="fa fa-warning"></i> Siswa dengan NIS <strong>{{ request('r') }}</strong> tidak ditemukan.</div>
@endif

@endsection
@push('scripts')
<script>
function switchTab(el,id){$('.miba-tab').removeClass('active');$('.miba-tab-content').removeClass('active');$(el).addClass('active');$('#'+id).addClass('active');return false;}
function calc(){var h=($('#harga').val()||'0').replace(/\D/g,'');var b=($('#bayar').val()||'0').replace(/\D/g,'');$('#kembalian').val((parseInt(b||0)-parseInt(h||0)).toLocaleString('id-ID'));}
$('#harga,#bayar').on('input',calc);calc();
</script>
@endpush
