@extends('layouts.app')

@section('title', 'Request Refund')

@section('content')
<div class="container section">
  <div class="mb-lg">
    <a href="{{ route('library.index') }}" class="btn-ghost text-mute"><i class="fa fa-arrow-left"></i> Kembali ke Library</a>
  </div>

  <div class="card" style="max-width:600px;margin:0 auto">
    <h1 class="t-heading-xl mb-sm">Request Refund</h1>
    <p class="text-mute mb-xl">Refund hanya berlaku untuk game yang dibeli dalam 14 hari terakhir.</p>

    <div class="flex gap-md mb-xl" style="padding:16px;background:var(--c-surface-soft);border-radius: 0">
      @if($detail->game->cover_image)
        <img src="{{ $detail->game->cover_image_url }}" style="width:100px;height:56px;object-fit:cover;border-radius: 0">
      @else
        <div class="img-placeholder" style="width:100px;height:56px;font-size:16px"><i class="fa fa-image"></i></div>
      @endif
      <div>
        <div class="t-body-strong">{{ $detail->game->title }}</div>
        <div class="text-mute t-caption-sm mt-xs">Dibeli: {{ $detail->transaction->paid_at->format('d M Y') }}</div>
        <div class="text-green t-body-strong mt-xs">Rp {{ number_format($detail->price, 0, ',', '.') }}</div>
      </div>
    </div>

    @if($detail->refund_status === 'requested')
      <div class="alert alert-warning">
        <i class="fa fa-clock"></i> Permintaan refund kamu sedang diproses oleh admin.
      </div>
      <p class="mt-md text-mute">Alasan: {{ $detail->refund_reason }}</p>
    @elseif($detail->refund_status === 'rejected')
      <div class="alert alert-error">
        <i class="fa fa-xmark"></i> Permintaan refund kamu ditolak oleh admin.
      </div>
    @elseif($detail->refund_status === 'approved')
      <div class="alert alert-success">
        <i class="fa fa-check"></i> Refund telah disetujui.
      </div>
    @else
      @php
        $daysPassed = $detail->transaction->paid_at->diffInDays(now());
        $daysLeft = 14 - $daysPassed;
      @endphp

      @if($daysLeft >= 0)
        <div class="alert alert-info">
          <i class="fa fa-info-circle"></i> Sisa waktu kelayakan refund: <strong>{{ $daysLeft }} hari</strong>
        </div>

        <form action="{{ route('refund.request', $detail->id) }}" method="POST">
          @csrf
          <div class="form-group mt-lg">
            <label for="refund_reason" class="form-label">Alasan Refund</label>
            <textarea name="refund_reason" id="refund_reason" class="form-control" placeholder="Jelaskan alasan kamu ingin refund game ini secara detail (minimal 20 karakter)..." required minlength="20" maxlength="500"></textarea>
          </div>
          <button type="submit" class="btn btn-danger w-full btn-lg">Kirim Request Refund</button>
        </form>
      @else
        <div class="alert alert-error">
          <i class="fa fa-ban"></i> Game ini sudah melewati batas waktu kelayakan refund (14 hari).
        </div>
      @endif
    @endif
  </div>
</div>
@endsection
