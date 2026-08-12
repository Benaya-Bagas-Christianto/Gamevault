@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container section text-center">
  <div style="max-width:500px; margin:0 auto">
    <div style="font-size:64px;color:var(--c-primary);margin-bottom:24px"><i class="fa fa-credit-card"></i></div>
    <h1 class="t-display-lg mb-sm">Selesaikan Pembayaran</h1>
    <p class="text-mute mb-xl">Silakan selesaikan pembayaran untuk Order #{{ $transaction->order_id }}. Pop-up pembayaran akan muncul otomatis.</p>
    
    <div class="card-dark mb-lg text-left">
      <div class="flex-between mb-sm">
        <span>Total Tagihan:</span>
        <span class="t-heading-md text-green">Rp {{ number_format($total, 0, ',', '.') }}</span>
      </div>
      <div class="flex-between">
        <span>Status:</span>
        <span class="badge badge-green">MENUNGGU PEMBAYARAN</span>
      </div>
    </div>

    <button id="pay-button" class="btn btn-primary btn-lg w-full mb-md">
      Buka Kembali Jendela Pembayaran
    </button>
    <a href="{{ route('library.index') }}" class="btn btn-outline-dark w-full">Cek Library Nanti</a>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script type="text/javascript">
  function triggerSnap() {
    window.snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        fetch('{{ route("checkout.finalize") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: '{{ $transaction->order_id }}', payment_type: result.payment_type || 'unknown' })
        })
        .then(() => {
            window.location.href = "{{ route('library.index') }}";
        })
        .catch(() => {
            window.location.href = "{{ route('library.index') }}";
        });
      },
      onPending: function(result){
        if (typeof showCenterPopup === 'function') showCenterPopup('Menunggu pembayaran diselesaikan.', true);
      },
      onError: function(result){
        if (typeof showCenterPopup === 'function') showCenterPopup('Pembayaran gagal, silakan coba lagi.', false);
      },
      onClose: function(){
        if (typeof showCenterPopup === 'function') showCenterPopup('Jendela pembayaran ditutup sebelum selesai.', false);
      }
    });
  }

  // Auto trigger on load
  document.addEventListener('DOMContentLoaded', function() {
    triggerSnap();
  });

  // Manual trigger
  document.getElementById('pay-button').addEventListener('click', function () {
    triggerSnap();
  });
</script>
@endpush
