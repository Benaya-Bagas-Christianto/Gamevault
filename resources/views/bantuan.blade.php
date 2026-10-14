@extends('layouts.app')

@section('title', 'Pusat Bantuan — GameVault')

@section('content')

<style>
    /* ════ PUSAT BANTUAN RESPONSIVE STYLES ════ */
    .help-page-wrapper {
        padding-bottom: 70px;
    }

    /* Hero Section */
    .help-hero {
        background: linear-gradient(180deg, rgba(18, 22, 25, 0.9) 0%, rgba(10, 12, 14, 0.95) 100%);
        border-bottom: 1px solid var(--c-hairline);
        padding: 48px 0 36px;
        text-align: center;
        position: relative;
    }
    .help-hero-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 8px;
    }
    .help-hero-subtitle {
        color: var(--c-mute);
        font-size: 0.95rem;
        max-width: 580px;
        margin: 0 auto 24px;
        line-height: 1.6;
    }

    /* Live Search Box */
    .help-search-container {
        max-width: 520px;
        margin: 0 auto;
        position: relative;
    }
    .help-search-input {
        width: 100%;
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        color: var(--c-ink);
        padding: 12px 18px 12px 44px;
        font-size: 0.92rem;
        border-radius: 0;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .help-search-input:focus {
        border-color: var(--c-primary);
        box-shadow: 0 0 12px rgba(118, 185, 0, 0.25);
    }
    .help-search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--c-mute);
        font-size: 15px;
        pointer-events: none;
    }

    /* Layout Grid */
    .help-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 32px;
        align-items: flex-start;
        padding-top: 36px;
    }

    /* Sidebar Navigation (Desktop) */
    .help-sidebar {
        position: sticky;
        top: 90px;
    }
    .help-sidebar-card {
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        padding: 16px;
    }
    .help-sidebar-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--c-mute);
        margin: 0 0 12px 0;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--c-hairline);
    }
    .help-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .help-nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        color: var(--c-body);
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        border-left: 3px solid transparent;
        border-radius: 0;
        transition: all 0.2s ease;
    }
    .help-nav-link i {
        font-size: 14px;
        width: 18px;
        color: var(--c-mute);
        transition: color 0.2s;
    }
    .help-nav-link:hover {
        background: var(--c-surface-soft);
        color: var(--c-primary);
        border-left-color: var(--c-primary);
    }
    .help-nav-link:hover i {
        color: var(--c-primary);
    }
    .help-nav-link.active {
        background: rgba(118, 185, 0, 0.12);
        color: var(--c-primary);
        border-left-color: var(--c-primary);
    }
    .help-nav-link.active i {
        color: var(--c-primary);
    }

    /* Mobile Quick Categories (Horizontal Bar) */
    .help-mobile-nav {
        display: none;
        background: var(--c-surface-dark);
        border-bottom: 1px solid var(--c-hairline);
        padding: 10px 14px;
        position: sticky;
        top: 60px;
        z-index: 90;
    }
    .help-mobile-scroll {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }
    .help-mobile-scroll::-webkit-scrollbar {
        display: none;
    }
    .help-mobile-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        background: var(--c-surface-soft);
        border: 1px solid var(--c-hairline);
        color: var(--c-mute);
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 0;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .help-mobile-pill.active,
    .help-mobile-pill:active {
        background: rgba(118, 185, 0, 0.15);
        border-color: var(--c-primary);
        color: var(--c-primary);
    }

    /* Content Cards */
    .help-content-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
        min-width: 0;
    }
    .help-card {
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        padding: 28px;
        transition: border-color 0.2s;
    }
    .help-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--c-hairline);
    }
    .help-card-icon {
        width: 38px;
        height: 38px;
        background: rgba(118, 185, 0, 0.12);
        border: 1px solid rgba(118, 185, 0, 0.3);
        color: var(--c-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
        border-radius: 0;
    }
    .help-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--c-ink);
        margin: 0;
    }
    .help-card-body {
        color: var(--c-body);
        font-size: 0.92rem;
        line-height: 1.75;
    }

    /* Step-by-Step Purchase Flow (Mobile-First Stepper) */
    .help-stepper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .help-step-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        background: rgba(0,0,0,0.2);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        padding: 12px 14px;
        transition: all 0.2s;
    }
    .help-step-item:hover {
        background: rgba(255,255,255,0.02);
        border-color: rgba(118, 185, 0, 0.35);
    }
    .help-step-number {
        width: 28px;
        height: 28px;
        background: var(--c-primary);
        color: #000;
        font-weight: 800;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 0;
    }
    .help-step-content {
        flex: 1;
        min-width: 0;
    }
    .help-step-title {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }
    .help-step-desc {
        color: var(--c-mute);
        font-size: 0.86rem;
        margin: 0;
        line-height: 1.5;
    }

    /* Payment Badges Grid */
    .help-payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }
    .help-payment-box {
        background: rgba(0,0,0,0.22);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        padding: 12px 14px;
    }
    .help-payment-box-title {
        color: var(--c-primary);
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .help-payment-box-list {
        color: var(--c-body);
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.5;
    }

    /* Highlight Banner Box */
    .help-highlight-box {
        margin-top: 16px;
        padding: 12px 14px;
        background: rgba(118,185,0,0.08);
        border: 1px solid rgba(118,185,0,0.25);
        border-left: 3px solid var(--c-primary);
        border-radius: 0;
        color: var(--c-ink);
        font-size: 0.86rem;
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Checkmark & Info Lists */
    .help-list-clean {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .help-list-clean li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.9rem;
        color: var(--c-body);
        line-height: 1.6;
    }
    .help-list-clean li i {
        color: var(--c-primary);
        font-size: 13px;
        margin-top: 4px;
        flex-shrink: 0;
    }

    /* VaultBot Help Banner Card */
    .help-bot-banner {
        background: linear-gradient(135deg, rgba(118,185,0,0.12) 0%, rgba(14,18,20,0.95) 100%);
        border: 1px solid var(--c-primary);
        border-radius: 0;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 10px;
    }
    .help-bot-banner-btn {
        padding: 10px 20px;
        background: var(--c-primary);
        color: #000;
        border: none;
        border-radius: 0;
        font-weight: 800;
        font-size: 0.88rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .help-bot-banner-btn:hover {
        filter: brightness(1.15);
        transform: translateY(-1px);
    }

    /* ════ MOBILE RESPONSIVE OVERRIDES (<= 768px) ════ */
    @media (max-width: 768px) {
        .help-hero {
            padding: 30px 16px 24px;
        }
        .help-hero-title {
            font-size: 1.6rem;
        }
        .help-hero-subtitle {
            font-size: 0.85rem;
            margin-bottom: 18px;
        }
        .help-search-input {
            font-size: 0.85rem;
            padding: 10px 14px 10px 38px;
        }
        .help-search-icon {
            left: 14px;
            font-size: 13px;
        }

        /* Show Horizontal Scroll Bar */
        .help-mobile-nav {
            display: block;
        }

        /* Hide Desktop Sidebar */
        .help-sidebar {
            display: none !important;
        }

        .help-layout {
            grid-template-columns: 1fr;
            gap: 16px;
            padding-top: 16px;
            padding-left: 12px;
            padding-right: 12px;
        }

        .help-content-column {
            gap: 16px;
        }

        .help-card {
            padding: 16px 14px;
        }
        .help-card-header {
            gap: 10px;
            margin-bottom: 14px;
            padding-bottom: 10px;
        }
        .help-card-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
        .help-card-title {
            font-size: 1.05rem;
        }
        .help-card-body {
            font-size: 0.86rem;
            line-height: 1.65;
        }

        .help-stepper {
            gap: 10px;
        }
        .help-step-item {
            padding: 10px 12px;
            gap: 10px;
        }
        .help-step-number {
            width: 24px;
            height: 24px;
            font-size: 0.78rem;
        }
        .help-step-title {
            font-size: 0.88rem;
        }
        .help-step-desc {
            font-size: 0.8rem;
        }

        .help-payment-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .help-payment-box {
            padding: 10px 12px;
        }

        .help-bot-banner {
            flex-direction: column;
            align-items: flex-start;
            padding: 16px;
            gap: 12px;
        }
        .help-bot-banner-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* ════ VAULTBOT WIDGET STYLES (MOBILE-FIRST) ════ */
    #vaultbot-widget {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
    }
    #vaultbot-toggle {
        width: 54px;
        height: 54px;
        border-radius: 0;
        background: var(--c-primary);
        color: #000;
        border: none;
        font-size: 20px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(118,185,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }
    #vaultbot-toggle:hover {
        transform: scale(1.05);
    }
    #vaultbot-box {
        display: none;
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 360px;
        height: 500px;
        background: var(--c-surface-elevated);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.6);
        flex-direction: column;
        overflow: hidden;
    }

    @media (max-width: 600px) {
        #vaultbot-widget {
            bottom: 16px;
            right: 16px;
        }
        #vaultbot-toggle {
            width: 48px;
            height: 48px;
            font-size: 18px;
        }
        #vaultbot-box {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100vw;
            height: 100vh;
            max-width: 100vw;
            max-height: 100vh;
            border: none;
            z-index: 100000;
        }
    }
</style>

<div class="help-page-wrapper">
    
    {{-- Hero Section --}}
    <div class="help-hero">
        <div class="container">
            <h1 class="help-hero-title">Pusat <span style="color: var(--c-primary);">Bantuan</span></h1>
            <p class="help-hero-subtitle">
                Temukan panduan lengkap seputar pembelian game, metode pembayaran, kebijakan refund, dan privasi akun Anda.
            </p>

            {{-- Live Search Input --}}
            <div class="help-search-container">
                <i class="fas fa-search help-search-icon"></i>
                <input type="text" id="help-search" class="help-search-input" placeholder="Cari topik bantuan (cth: refund, gopay, library)..." onkeyup="filterHelpTopics(this.value)">
            </div>
        </div>
    </div>

    {{-- Mobile Sticky Quick Navigation Bar --}}
    <div class="help-mobile-nav">
        <div class="help-mobile-scroll">
            <a href="#cara-membeli" class="help-mobile-pill active"><i class="fas fa-shopping-cart"></i> Cara Beli</a>
            <a href="#metode-pembayaran" class="help-mobile-pill"><i class="fas fa-wallet"></i> Pembayaran</a>
            <a href="#kebijakan-refund" class="help-mobile-pill"><i class="fas fa-undo"></i> Refund</a>
            <a href="#syarat-ketentuan" class="help-mobile-pill"><i class="fas fa-file-contract"></i> Syarat &amp; Ketentuan</a>
            <a href="#privasi" class="help-mobile-pill"><i class="fas fa-user-shield"></i> Privasi</a>
        </div>
    </div>

    <div class="container">
        <div class="help-layout">
            
            {{-- Desktop Sidebar Navigation --}}
            <aside class="help-sidebar">
                <div class="help-sidebar-card">
                    <h3 class="help-sidebar-title">Kategori Bantuan</h3>
                    <ul class="help-nav-list">
                        <li>
                            <a href="#cara-membeli" class="help-nav-link active">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Cara Membeli</span>
                            </a>
                        </li>
                        <li>
                            <a href="#metode-pembayaran" class="help-nav-link">
                                <i class="fas fa-wallet"></i>
                                <span>Metode Pembayaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="#kebijakan-refund" class="help-nav-link">
                                <i class="fas fa-undo"></i>
                                <span>Kebijakan Refund</span>
                            </a>
                        </li>
                        <li>
                            <a href="#syarat-ketentuan" class="help-nav-link">
                                <i class="fas fa-file-contract"></i>
                                <span>Syarat &amp; Ketentuan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#privasi" class="help-nav-link">
                                <i class="fas fa-user-shield"></i>
                                <span>Privasi Pengguna</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>

            {{-- Main Content Column --}}
            <main class="help-content-column">
                
                {{-- 1. Cara Membeli (Interactive Stepper) --}}
                <section id="cara-membeli" class="help-card help-topic-item">
                    <div class="help-card-header">
                        <div class="help-card-icon"><i class="fas fa-shopping-cart"></i></div>
                        <h2 class="help-card-title">Cara Membeli Game</h2>
                    </div>
                    <div class="help-card-body">
                        <div class="help-stepper">
                            <div class="help-step-item">
                                <div class="help-step-number">1</div>
                                <div class="help-step-content">
                                    <h3 class="help-step-title">Pilih Game Favorit</h3>
                                    <p class="help-step-desc">Gunakan menu <strong>Toko</strong> atau kolom pencarian untuk menemukan judul game atau bundle DLC yang ingin Anda miliki.</p>
                                </div>
                            </div>

                            <div class="help-step-item">
                                <div class="help-step-number">2</div>
                                <div class="help-step-content">
                                    <h3 class="help-step-title">Tambah ke Keranjang</h3>
                                    <p class="help-step-desc">Klik tombol <strong>"Tambah ke Keranjang"</strong>. Anda dapat menambahkan beberapa game sekaligus sebelum checkout.</p>
                                </div>
                            </div>

                            <div class="help-step-item">
                                <div class="help-step-number">3</div>
                                <div class="help-step-content">
                                    <h3 class="help-step-title">Periksa Keranjang &amp; Checkout</h3>
                                    <p class="help-step-desc">Buka ikon keranjang di pojok atas, pastikan pesanan sesuai, lalu klik <strong>"Lanjut ke Pembayaran"</strong>.</p>
                                </div>
                            </div>

                            <div class="help-step-item">
                                <div class="help-step-number">4</div>
                                <div class="help-step-content">
                                    <h3 class="help-step-title">Selesaikan Pembayaran</h3>
                                    <p class="help-step-desc">Pilih opsi pembayaran (QRIS, E-Wallet, Virtual Account, dsb) via Midtrans dan selesaikan transaksi sesuai instruksi.</p>
                                </div>
                            </div>

                            <div class="help-step-item">
                                <div class="help-step-number">5</div>
                                <div class="help-step-content">
                                    <h3 class="help-step-title">Akses Instan di Library</h3>
                                    <p class="help-step-desc">Setelah pembayaran sukses diverifikasi, game otomatis aktif di halaman <strong>Library Game</strong> dan lisensi resmi langsung dikirim ke email Anda!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 2. Metode Pembayaran --}}
                <section id="metode-pembayaran" class="help-card help-topic-item">
                    <div class="help-card-header">
                        <div class="help-card-icon"><i class="fas fa-wallet"></i></div>
                        <h2 class="help-card-title">Metode Pembayaran</h2>
                    </div>
                    <div class="help-card-body">
                        <p style="margin-top: 0; margin-bottom: 12px;">
                            GameVault bermitra resmi dengan payment gateway terpercaya <strong>Midtrans</strong> untuk memproses transaksi secara aman, cepat, dan terenkripsi 24/7:
                        </p>

                        <div class="help-payment-grid">
                            <div class="help-payment-box">
                                <div class="help-payment-box-title"><i class="fas fa-qrcode"></i> QRIS &amp; E-Wallet</div>
                                <p class="help-payment-box-list">QRIS (Semua Bank &amp; E-wallet), GoPay, ShopeePay, DANA, OVO.</p>
                            </div>

                            <div class="help-payment-box">
                                <div class="help-payment-box-title"><i class="fas fa-building-columns"></i> Virtual Account</div>
                                <p class="help-payment-box-list">BCA, Mandiri, BNI, BRI, Permata, Danamon, dan bank lainnya.</p>
                            </div>

                            <div class="help-payment-box">
                                <div class="help-payment-box-title"><i class="far fa-credit-card"></i> Kartu Kredit/Debit</div>
                                <p class="help-payment-box-list">Visa, Mastercard, JCB berlogo 3D Secure dengan verifikasi OTP.</p>
                            </div>

                            <div class="help-payment-box">
                                <div class="help-payment-box-title"><i class="fas fa-store"></i> Gerai Tunai</div>
                                <p class="help-payment-box-list">Indomaret dan Alfamart di seluruh Indonesia.</p>
                            </div>
                        </div>

                        <div class="help-highlight-box">
                            <i class="fas fa-bolt" style="color: var(--c-primary); font-size: 16px;"></i>
                            <div>Semua transaksi diproses secara instan (real-time) tanpa perlu konfirmasi manual.</div>
                        </div>
                    </div>
                </section>

                {{-- 3. Kebijakan Refund --}}
                <section id="kebijakan-refund" class="help-card help-topic-item">
                    <div class="help-card-header">
                        <div class="help-card-icon"><i class="fas fa-undo"></i></div>
                        <h2 class="help-card-title">Kebijakan Pengembalian Dana (Refund)</h2>
                    </div>
                    <div class="help-card-body">
                        <p style="margin-top: 0; margin-bottom: 14px;">
                            Kami memberikan jaminan kepuasan pelanggan dengan ketentuan refund yang adil dan transparan:
                        </p>

                        <ul class="help-list-clean">
                            <li>
                                <i class="fas fa-check-square"></i>
                                <div><strong>Jangka Waktu:</strong> Pengajuan refund dapat dilakukan maksimal <strong>14 hari</strong> terhitung sejak tanggal transaksi berhasil.</div>
                            </li>
                            <li>
                                <i class="fas fa-check-square"></i>
                                <div><strong>Game Belum Dimainkan Lama:</strong> Klaim refund disetujui jika game mengalami kendala teknis berat atau belum dimainkan melebihi batas 2 jam.</div>
                            </li>
                            <li>
                                <i class="fas fa-check-square"></i>
                                <div><strong>Cakupan Produk:</strong> Berlaku untuk game dasar (*Base Game*). Konten tambahan (DLC) atau bundle promo khusus tunduk pada syarat dan ketentuan promosi.</div>
                            </li>
                        </ul>

                        <div class="help-highlight-box" style="margin-top: 16px;">
                            <i class="fas fa-clock" style="color: var(--c-primary); font-size: 16px;"></i>
                            <div>Dana refund akan dikembalikan ke metode pembayaran awal dalam rentang <strong>7 - 14 hari kerja</strong> tergantung bank/e-wallet Anda.</div>
                        </div>
                    </div>
                </section>

                {{-- 4. Syarat & Ketentuan --}}
                <section id="syarat-ketentuan" class="help-card help-topic-item">
                    <div class="help-card-header">
                        <div class="help-card-icon"><i class="fas fa-file-contract"></i></div>
                        <h2 class="help-card-title">Syarat &amp; Ketentuan Layanan</h2>
                    </div>
                    <div class="help-card-body">
                        <ul class="help-list-clean">
                            <li>
                                <i class="fas fa-shield-alt"></i>
                                <div><strong>Kepemilikan Digital:</strong> Semua game yang dibeli di GameVault adalah lisensi digital resmi yang terikat secara permanen dengan akun GameVault Anda.</div>
                            </li>
                            <li>
                                <i class="fas fa-user-lock"></i>
                                <div><strong>Keamanan Akun:</strong> Pengguna bertanggung jawab penuh atas kerahasiaan kata sandi akun dan dilarang menyalahgunakan akun untuk kegiatan ilegal.</div>
                            </li>
                            <li>
                                <i class="fas fa-tags"></i>
                                <div><strong>Ketetapan Harga:</strong> Harga sewaktu-waktu dapat diperbarui mengikuti promo penerbit (*publisher*), namun harga pada invoice yang sudah terbit dijamin tetap.</div>
                            </li>
                        </ul>
                    </div>
                </section>

                {{-- 5. Privasi Pengguna --}}
                <section id="privasi" class="help-card help-topic-item">
                    <div class="help-card-header">
                        <div class="help-card-icon"><i class="fas fa-user-shield"></i></div>
                        <h2 class="help-card-title">Kebijakan Privasi</h2>
                    </div>
                    <div class="help-card-body">
                        <ul class="help-list-clean">
                            <li>
                                <i class="fas fa-lock"></i>
                                <div><strong>Perlindungan Data:</strong> Data pribadi Anda (nama, email) disimpan dalam sistem terenkripsi berstandar industri dengan proteksi *bcrypt hash*.</div>
                            </li>
                            <li>
                                <i class="fas fa-ban"></i>
                                <div><strong>Tanpa Penjualan Data:</strong> Kami berjanji 100% tidak akan pernah menjual atau membagikan data Anda kepada pihak ketiga untuk kepentingan iklan spam.</div>
                            </li>
                            <li>
                                <i class="fas fa-credit-card"></i>
                                <div><strong>Data Keuangan Aman:</strong> Detail kartu kredit diproses langsung oleh payment gateway Midtrans yang tersertifikasi PCI-DSS tingkat internasional; GameVault tidak menyimpan nomor kartu Anda.</div>
                            </li>
                        </ul>
                    </div>
                </section>

                {{-- Promo Card: Tanya VaultBot AI --}}
                <div class="help-bot-banner">
                    <div>
                        <div style="font-weight: 800; font-size: 1.05rem; color: #fff; margin-bottom: 4px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-robot" style="color: var(--c-primary);"></i> Masih Punya Pertanyaan Lain?
                        </div>
                        <div style="font-size: 0.85rem; color: var(--c-mute);">
                            Tanyakan kendala atau rekomendasi game langsung ke <strong>VaultBot AI</strong> yang siap membantu 24 jam nonstop!
                        </div>
                    </div>
                    <button type="button" class="help-bot-banner-btn" onclick="openVaultBot()">
                        <i class="fas fa-comments"></i> Tanya VaultBot AI
                    </button>
                </div>

            </main>
        </div>
    </div>
</div>

{{-- VaultBot Floating Chat Widget (Mobile & Desktop Responsive) --}}
<div id="vaultbot-widget">
    <!-- Chat Trigger Icon -->
    <button id="vaultbot-toggle" title="Buka VaultBot AI Support">
        <i class="fas fa-robot"></i>
    </button>

    <!-- Chat Box Drawer / Modal -->
    <div id="vaultbot-box">
        <!-- Header -->
        <div style="background: var(--c-primary); color: #000; padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; border-radius: 0;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-robot" style="font-size: 20px;"></i>
                <div>
                    <div style="font-weight: 800; font-size: 0.98rem; line-height: 1.1;">VaultBot AI</div>
                    <div style="font-size: 0.74rem; font-weight: 700; opacity: 0.85; margin-top: 2px;">Dukungan Resmi GameVault</div>
                </div>
            </div>
            <button id="vaultbot-close" style="background: transparent; border: none; color: #000; font-size: 1.3rem; cursor: pointer; padding: 4px 8px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-times"></i></button>
        </div>

        <!-- Messages Area -->
        <div id="vaultbot-messages" style="flex: 1; padding: 16px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; background: var(--c-surface-dark);">
            <!-- Initial Bot Message -->
            <div style="align-self: flex-start; background: var(--c-surface-soft); color: var(--c-body); padding: 12px 14px; border: 1px solid var(--c-hairline); border-radius: 0; max-width: 88%; font-size: 0.88rem; line-height: 1.55;">
                Halo! Aku <strong>VaultBot AI</strong> 🤖. Ada kendala seputar cara beli, verifikasi pembayaran, refund, atau ingin rekomendasi game di GameVault? Tanyakan saja di sini!
            </div>
        </div>

        <!-- Input Area -->
        <div style="padding: 12px 14px; background: var(--c-surface-elevated); border-top: 1px solid var(--c-hairline); display: flex; gap: 8px;">
            <input type="text" id="vaultbot-input" placeholder="Ketik pertanyaanmu..." style="flex: 1; background: var(--c-surface-dark); border: 1px solid var(--c-hairline); color: var(--c-ink); padding: 10px 14px; border-radius: 0; outline: none; font-size: 0.88rem;">
            <button id="vaultbot-send" style="background: var(--c-primary); color: #000; border: none; border-radius: 0; width: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 15px;"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
    // Smooth scrolling & active indicator for Help Navigation
    const navLinks = document.querySelectorAll('.help-nav-link, .help-mobile-pill');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if(href && href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if(target) {
                    const headerOffset = window.innerWidth <= 768 ? 120 : 90;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    // Update active link state
                    navLinks.forEach(l => l.classList.remove('active'));
                    document.querySelectorAll(`a[href="${href}"]`).forEach(el => el.classList.add('active'));
                }
            }
        });
    });

    // Simple Live Search Filter for Help Topics
    function filterHelpTopics(query) {
        const q = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.help-topic-item');

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            if(q === '' || text.includes(q)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // --- VaultBot Chat Logic ---
    const chatToggle = document.getElementById('vaultbot-toggle');
    const chatBox = document.getElementById('vaultbot-box');
    const chatClose = document.getElementById('vaultbot-close');
    const messagesArea = document.getElementById('vaultbot-messages');
    const inputField = document.getElementById('vaultbot-input');
    const sendBtn = document.getElementById('vaultbot-send');

    let chatHistory = [];

    function openVaultBot() {
        chatBox.style.display = 'flex';
        inputField.focus();
    }

    chatToggle.addEventListener('click', () => {
        chatBox.style.display = chatBox.style.display === 'none' ? 'flex' : 'none';
        if(chatBox.style.display === 'flex') inputField.focus();
    });

    chatClose.addEventListener('click', () => {
        chatBox.style.display = 'none';
    });

    function parseBotText(text) {
        return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                   .replace(/\*(.*?)\*/g, '<em>$1</em>')
                   .replace(/\n/g, '<br>');
    }

    function appendMessage(text, role) {
        const msgDiv = document.createElement('div');
        msgDiv.style.padding = '10px 14px';
        msgDiv.style.borderRadius = '0';
        msgDiv.style.maxWidth = '88%';
        msgDiv.style.fontSize = '0.88rem';
        msgDiv.style.lineHeight = '1.55';
        
        if(role === 'user') {
            msgDiv.style.alignSelf = 'flex-end';
            msgDiv.style.background = 'var(--c-primary)';
            msgDiv.style.color = '#000';
            msgDiv.style.fontWeight = '600';
            msgDiv.textContent = text;
        } else {
            msgDiv.style.alignSelf = 'flex-start';
            msgDiv.style.background = 'var(--c-surface-soft)';
            msgDiv.style.border = '1px solid var(--c-hairline)';
            msgDiv.style.color = 'var(--c-body)';
            msgDiv.innerHTML = parseBotText(text);
        }

        messagesArea.appendChild(msgDiv);
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'vaultbot-typing';
        typingDiv.style.alignSelf = 'flex-start';
        typingDiv.style.background = 'transparent';
        typingDiv.style.color = 'var(--c-mute)';
        typingDiv.style.padding = '4px 8px';
        typingDiv.style.fontSize = '0.82rem';
        typingDiv.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> VaultBot sedang berpikir...';
        messagesArea.appendChild(typingDiv);
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function hideTyping() {
        const typingDiv = document.getElementById('vaultbot-typing');
        if(typingDiv) typingDiv.remove();
    }

    async function sendMessage() {
        const text = inputField.value.trim();
        if(!text) return;

        appendMessage(text, 'user');
        inputField.value = '';
        showTyping();
        
        const payload = {
            message: text,
            history: chatHistory
        };
        chatHistory.push({ role: 'user', text: text });

        let csrfToken = '';
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if(metaTag) csrfToken = metaTag.content;

        try {
            const response = await fetch('{{ route("bantuan.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            hideTyping();

            if (data.reply) {
                appendMessage(data.reply, 'model');
                chatHistory.push({ role: 'model', text: data.reply });
            } else if (data.error) {
                appendMessage("❌ Error: " + data.error, 'model');
            }
        } catch (e) {
            hideTyping();
            appendMessage("❌ Gagal terhubung ke server. Coba periksa koneksi kamu.", 'model');
        }
    }

    sendBtn.addEventListener('click', sendMessage);
    inputField.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') sendMessage();
    });
</script>

@endsection
