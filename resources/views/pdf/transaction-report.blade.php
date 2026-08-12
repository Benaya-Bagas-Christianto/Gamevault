<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, sans-serif; font-size: 12px; color: #1a1a1a; }
  .header { background: #000; color: #fff; padding: 20px 28px; display: flex; justify-content: space-between; align-items: center; }
  .brand { font-size: 20px; font-weight: 700; }
  .brand span { color: #76b900; }
  .meta { padding: 16px 28px; background: #f7f7f7; border-bottom: 2px solid #76b900; display: flex; justify-content: space-between; font-size: 12px; }
  .content { padding: 20px 28px; }
  table { width: 100%; border-collapse: collapse; }
  thead th { background: #000; color: #fff; font-size: 10px; text-transform: uppercase; font-weight: 700; padding: 8px 10px; text-align: left; }
  tbody td { padding: 8px 10px; border-bottom: 1px solid #cccccc; font-size: 11px; }
  tbody tr:nth-child(even) td { background: #f7f7f7; }
  .total-row td { font-weight: 700; background: rgba(118,185,0,0.1); border-top: 2px solid #76b900; font-size: 13px; }
  .status-paid { color: #3f8500; font-weight: 700; }
  .footer { margin-top: 20px; padding: 12px 28px; background: #f7f7f7; font-size: 10px; color: #757575; text-align: center; }
</style>
</head>
<body>

<div class="header">
  <div>
    <div class="brand">Game<span>Vault</span></div>
    <div style="font-size:11px;color:rgba(255,255,255,0.5);margin-top:2px">Laporan Transaksi Penjualan</div>
  </div>
  <div style="text-align:right;color:rgba(255,255,255,0.8)">
    <div>Digenerate: {{ now()->format('d M Y, H:i') }}</div>
    <div style="color:#76b900;font-weight:700;font-size:16px">
      TOTAL: Rp {{ number_format($totalRevenue, 0, ',', '.') }}
    </div>
  </div>
</div>

<div class="meta">
  <span>Total Transaksi Lunas: <strong>{{ $transactions->count() }}</strong></span>
  <span>Total Pendapatan: <strong style="color:#3f8500">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></span>
</div>

<div class="content">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Order ID</th>
        <th>Pembeli</th>
        <th>Email</th>
        <th>Game ({{ number_format(1) }})</th>
        <th>Total</th>
        <th>Metode</th>
        <th>Tanggal Bayar</th>
      </tr>
    </thead>
    <tbody>
      @foreach($transactions as $i => $transaction)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td><strong>{{ $transaction->order_id }}</strong></td>
          <td>{{ $transaction->user->name }}</td>
          <td>{{ $transaction->user->email }}</td>
          <td>{{ $transaction->details->count() }} game</td>
          <td><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
          <td>{{ strtoupper($transaction->payment_type ?? '-') }}</td>
          <td>{{ $transaction->paid_at?->format('d M Y') }}</td>
        </tr>
      @endforeach
      <tr class="total-row">
        <td colspan="5" style="text-align:right"><strong>TOTAL PENDAPATAN</strong></td>
        <td colspan="3"><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
      </tr>
    </tbody>
  </table>
</div>

<div class="footer">
  Dokumen ini bersifat rahasia dan hanya untuk keperluan internal GameVault.<br>
  &copy; {{ date('Y') }} GameVault — Platform Game Digital Terpercaya
</div>

</body>
</html>
