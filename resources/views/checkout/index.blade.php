@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container section">
  <div class="mb-lg">
    <a href="{{ route('cart.index') }}" class="btn-ghost text-mute"><i class="fa fa-arrow-left"></i> Kembali ke Keranjang</a>
  </div>
  
  <h1 class="t-display-lg mb-xl">Ringkasan Checkout</h1>

  <div class="layout-sidebar">
    <!-- Left: Items -->
    <div class="card">
      <h2 class="t-heading-md mb-md">Item Pembelian</h2>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Game</th>
              <th style="text-align:right">Harga</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cartItems as $game)
              <tr>
                <td>
                  <div class="flex gap-md" style="align-items:center">
                    @if($game->cover_image)
                      <img src="{{ $game->cover_image_url }}" style="width:60px;height:34px;object-fit:cover;border-radius: 0">
                    @else
                      <div class="img-placeholder" style="width:60px;height:34px;font-size:14px"><i class="fa fa-image"></i></div>
                    @endif
                    <span class="t-body-strong">{{ $game->title }}</span>
                  </div>
                </td>
                <td style="text-align:right; font-weight:700" class="{{ $game->price == 0 ? 'text-green' : '' }}">
                  {{ $game->price == 0 ? 'GRATIS' : 'Rp ' . number_format($game->price, 0, ',', '.') }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Right: Summary -->
    <div class="card-dark" style="position:sticky;top:80px">
      <h2 class="t-heading-sm mb-lg">Ringkasan Pembayaran</h2>
      
      <div class="flex-between mb-sm">
        <span class="text-mute">Total Game</span>
        <span>{{ count($cartItems) }}</span>
      </div>
      <div class="flex-between mb-lg">
        <span class="text-mute">Subtotal</span>
        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
      </div>
      
      <div class="flex-between mt-md pt-md" style="border-top:1px solid var(--c-hairline-strong); margin-bottom:24px">
        <span class="t-heading-sm">TOTAL</span>
        <span class="t-heading-md text-green">Rp {{ number_format($total, 0, ',', '.') }}</span>
      </div>

      <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary w-full btn-lg">
          <i class="fa fa-lock"></i> Bayar Sekarang
        </button>
      </form>
      
      <p class="text-center mt-md text-mute" style="font-size:12px">
        Dengan mengklik bayar, kamu menyetujui Syarat & Ketentuan GameVault.
      </p>
    </div>
  </div>
</div>
@endsection
