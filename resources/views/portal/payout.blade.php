@extends('portal.layout')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-money"></i> Cek Pembayaran</div>
    <form method="GET" style="display:flex;gap:8px">
      <select name="n" class="miba-select" style="width:170px" onchange="this.form.submit()">
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ $periodId==$p->period_id?'selected':'' }}>{{ $p->period_start }}/{{ $p->period_end }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="miba-card-body p0">
    <div class="miba-tabs" style="padding:0 16px;border-bottom:2px solid var(--border)">
      <a class="miba-tab active" href="#" onclick="switchTab(this,'tab-bulanan')">Bulanan</a>
      <a class="miba-tab" href="#" onclick="switchTab(this,'tab-bebas')">Bebas / Lainnya</a>
    </div>
    <div id="tab-bulanan" class="miba-tab-content active" style="padding:0">
      <table class="miba-table">
        <thead><tr><th>Pembayaran</th><th>Bulan</th><th>Tagihan</th><th>Status</th><th>Tgl Bayar</th></tr></thead>
        <tbody>
          @forelse($bulans as $row)
          <tr>
            <td style="font-weight:500">{{ $row->payment->pos->pos_name??'-' }}</td>
            <td>{{ $row->month->month_name??'' }}</td>
            <td>Rp {{ number_format($row->bulan_bill,0,',','.') }}</td>
            <td><span class="badge-miba {{ $row->bulan_status?'badge-success':'badge-danger' }}">{{ $row->bulan_status?'Lunas':'Belum' }}</span></td>
            <td style="font-size:12px;color:var(--text-muted)">{{ $row->bulan_status&&$row->bulan_date_pay?\Carbon\Carbon::parse($row->bulan_date_pay)->format('d/m/Y'):'-' }}</td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-muted)">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div id="tab-bebas" class="miba-tab-content" style="padding:0">
      <table class="miba-table">
        <thead><tr><th>Jenis Pembayaran</th><th>Total</th><th>Dibayar</th><th>Sisa</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($bebasList as $row)
          @php $sisa=$row->bebas_bill-$row->bebas_total_pay; $lunas=$sisa<=0; @endphp
          <tr>
            <td style="font-weight:500">{{ $row->payment->pos->pos_name??'-' }} <small style="color:var(--text-muted)">T.A {{ $row->payment->period->period_start??'' }}/{{ $row->payment->period->period_end??'' }}</small></td>
            <td>Rp {{ number_format($row->bebas_bill,0,',','.') }}</td>
            <td style="color:var(--success)">Rp {{ number_format($row->bebas_total_pay,0,',','.') }}</td>
            <td style="color:{{ $lunas?'var(--success)':'var(--danger)' }};font-weight:600">Rp {{ number_format($sisa,0,',','.') }}</td>
            <td><span class="badge-miba {{ $lunas?'badge-success':'badge-danger' }}">{{ $lunas?'Lunas':'Belum' }}</span></td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-muted)">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>function switchTab(el,id){$('.miba-tab').removeClass('active');$('.miba-tab-content').removeClass('active');$(el).addClass('active');$('#'+id).addClass('active');return false;}</script>
@endpush