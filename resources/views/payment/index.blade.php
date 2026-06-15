@extends('layouts.app')
@section('content')
<div class="miba-card">
  <div class="miba-card-header">
    <div class="miba-card-title"><i class="fa fa-cog"></i> Jenis Pembayaran</div>
    <div style="display:flex;gap:8px">
      <select class="miba-select" style="width:180px" onchange="filterPeriod(this.value)" id="periodFilter">
        <option value="">-- Semua Tahun --</option>
        @foreach($periods as $p)
          <option value="{{ $p->period_id }}" {{ request('period_id')==$p->period_id?'selected':'' }}>{{ $p->period_start }}/{{ $p->period_end }}</option>
        @endforeach
      </select>
      <a href="{{ route('payment.create') }}" class="btn-miba btn-miba-sm btn-primary-miba"><i class="fa fa-plus"></i> Tambah</a>
    </div>
  </div>
  <div class="miba-table-wrap">
    <table class="miba-table">
      <thead><tr><th>No</th><th>Nama Pembayaran</th><th>Tahun</th><th>Tipe</th><th>Tarif</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($payments as $i => $p)
        <tr>
          <td style="color:var(--text-muted)">{{ $payments->firstItem()+$i }}</td>
          <td style="font-weight:600">{{ $p->pos->pos_name??'-' }} <small style="color:var(--text-muted)">T.P {{ $p->period->period_start??'' }}/{{ $p->period->period_end??'' }}</small></td>
          <td>{{ $p->period->period_start??'-' }}/{{ $p->period->period_end??'-' }}</td>
          <td><span class="badge-miba {{ $p->payment_type=='BULAN'?'badge-info':'badge-warning' }}">{{ $p->payment_type=='BULAN'?'Bulanan':'Bebas' }}</span></td>
          <td>
            @if($p->payment_type=='BULAN')
              <a href="{{ route('payment.viewBulan',$p->payment_id) }}" class="btn-miba btn-miba-xs btn-primary-miba"><i class="fa fa-sliders"></i> Atur Tarif</a>
            @else
              <a href="{{ route('payment.viewBebas',$p->payment_id) }}" class="btn-miba btn-miba-xs btn-primary-miba"><i class="fa fa-sliders"></i> Atur Tagihan</a>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('payment.edit',$p->payment_id) }}" class="btn-miba btn-miba-xs btn-accent-miba"><i class="fa fa-edit"></i></a>
              <form method="POST" action="{{ route('payment.destroy',$p->payment_id) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                <button class="btn-miba btn-miba-xs btn-danger-miba"><i class="fa fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada jenis pembayaran</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:12px 16px">{{ $payments->links() }}</div>
</div>
@endsection
@push('scripts')
<script>function filterPeriod(v){window.location='{{ route('payment.index') }}'+(v?'?period_id='+v:'');}</script>
@endpush