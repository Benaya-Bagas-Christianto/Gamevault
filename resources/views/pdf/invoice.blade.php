<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    @page {
      margin: 0px;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
      color: #1a1a1a;
      padding: 0px 0px 80px 0px;
    }

    .header {
      background: #000;
      color: #fff;
      padding: 24px 32px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .brand {
      font-size: 22px;
      font-weight: 700;
    }

    .brand span {
      color: #76b900;
    }

    .invoice-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255, 255, 255, 0.6);
      margin-bottom: 4px;
    }

    .invoice-no {
      font-size: 16px;
      font-weight: 700;
      color: #76b900;
    }

    .meta {
      padding: 24px 32px;
      border-bottom: 1px solid #cccccc;
      display: flex;
      justify-content: space-between;
    }

    .meta-block h4 {
      font-size: 11px;
      text-transform: uppercase;
      color: #757575;
      margin-bottom: 8px;
    }

    .meta-block p {
      font-size: 13px;
      line-height: 1.6;
    }

    .content {
      padding: 24px 32px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }

    thead th {
      background: #f7f7f7;
      font-size: 11px;
      text-transform: uppercase;
      font-weight: 700;
      color: #757575;
      padding: 10px 12px;
      text-align: left;
      border-bottom: 2px solid #cccccc;
    }

    tbody td {
      padding: 12px;
      border-bottom: 1px solid #cccccc;
    }

    .summary {
      float: right;
      width: 240px;
    }

    .summary table {
      margin: 0;
    }

    .summary td {
      padding: 8px 12px;
      border: none;
      font-size: 13px;
    }

    .summary tr.total td {
      font-weight: 700;
      font-size: 15px;
      border-top: 2px solid #000;
      padding-top: 12px;
    }

    .summary tr.total td:last-child {
      color: #76b900;
    }

    .status-badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 0;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      background: rgba(63, 133, 0, 0.12);
      color: #3f8500;
      margin-left: 4px;
      vertical-align: middle;
      position: relative;
      top: -1px;
    }

    .footer {
      position: fixed;
      bottom: 0px;
      left: 0;
      right: 0;
      height: 48px;
      padding: 16px 32px;
      background: #f7f7f7;
      font-size: 11px;
      color: #757575;
      text-align: center;
    }
  </style>
</head>

<body>

  <div class="footer">
    Dokumen ini digenerate secara otomatis oleh GameVault pada {{ now()->format('d M Y, H:i') }} WIB.<br>
    &copy; {{ date('Y') }} GameVault - Platform Game Digital Terpercaya
  </div>

  <div class="header">
    <table style="width: 100%; border: none; margin: 0; padding: 0;">
      <tr>
        <td style="width: 50px; vertical-align: middle; border: none; padding: 0;">
          <img src="{{ public_path('img/logo.png') }}" alt="Logo" style="height: 36px;">
        </td>
        <td style="vertical-align: middle; border: none; padding: 0;">
          <div class="brand" style="line-height: 1;">Game<span>Vault</span></div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:4px;">Platform Game Digital Terpercaya</div>
        </td>
        <td style="text-align:right; vertical-align: middle; border: none; padding: 0;">
          <div class="invoice-label">Struk Pembelian</div>
          <div class="invoice-no">#{{ $transaction->order_id }}</div>
        </td>
      </tr>
    </table>
  </div>

  <div class="meta">
    <div class="meta-block">
      <h4>Ditagihkan Kepada</h4>
      <p>
        <strong>{{ $transaction->user->name }}</strong><br>
        {{ $transaction->user->email }}
      </p>
    </div>
    <div class="meta-block">
      <h4>Detail Transaksi</h4>
      <table style="width:auto; border:none; margin:0; padding:0; line-height: 1.6;">
        <tr>
          <td style="padding:0 4px 0 0; border:none; font-size:13px;">Tanggal:</td>
          <td style="padding:0; border:none; font-size:13px;"><strong>{{ $transaction->paid_at?->format('d M Y, H:i') }}</strong></td>
        </tr>
        <tr>
          <td style="padding:0 4px 0 0; border:none; font-size:13px;">Metode:</td>
          <td style="padding:0; border:none; font-size:13px;"><strong>{{ strtoupper($transaction->payment_type ?? '-') }}</strong></td>
        </tr>
        <tr>
          <td style="padding:0 4px 0 0; border:none; font-size:13px;">Status:</td>
          <td style="padding:0; border:none;">
            <span class="status-badge" style="position:static; top:auto; vertical-align:middle; margin-top:2px; display:inline-block;">{{ strtoupper($transaction->status) }}</span>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <div class="content">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Game</th>
          <th>Developer</th>
          <th>License Key</th>
          <th style="text-align:right">Harga</th>
        </tr>
      </thead>
      <tbody>
        @foreach($transaction->details as $i => $detail)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td><strong>{{ $detail->game->title }}</strong></td>
          <td>{{ $detail->game->developer ?? '-' }}</td>
          <td style="font-family:monospace;color:#76b900;font-weight:700;font-size:12px">
            {{ $detail->license_key ?? 'PENDING' }}
          </td>
          <td style="text-align:right">
            @if($detail->price == 0)
            GRATIS
            @else
            Rp {{ number_format($detail->price, 0, ',', '.') }}
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="summary">
      <table>
        <tr>
          <td>Subtotal</td>
          <td style="text-align:right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td>Pajak</td>
          <td style="text-align:right">Rp 0</td>
        </tr>
        <tr class="total">
          <td>TOTAL</td>
          <td style="text-align:right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
      </table>
    </div>
  </div>

</body>

</html>