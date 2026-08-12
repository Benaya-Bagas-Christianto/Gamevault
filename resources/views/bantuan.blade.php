@extends('layouts.app')

@section('title', 'Pusat Bantuan — GameVault')

@section('content')

{{-- Hero Section --}}
<div class="hero" style="padding: 60px 0; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.2);">
    <div class="container hero-content">
        <h1 class="t-display-lg" style="color: var(--c-ink); margin-bottom: 12px;">Pusat <span style="color: var(--c-primary);">Bantuan</span></h1>
        <p class="t-body-md" style="color: var(--c-mute); max-width: 600px; margin: 0 auto;">
            Temukan panduan dan informasi lengkap seputar pembelian, pembayaran, dan kebijakan di GameVault.
        </p>
    </div>
</div>

<style>
    .help-layout {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: var(--sp-xxl);
    }
    @media (max-width: 768px) {
        .help-layout {
            grid-template-columns: 1fr;
            gap: var(--sp-xl);
        }
        .help-sidebar {
            position: static !important;
            margin-bottom: 24px;
        }
        .help-content-card {
            padding: 24px !important;
        }
    }
</style>

<div class="container" style="padding-top: 48px; padding-bottom: 64px;">
    <div class="help-layout">
        
        {{-- Sidebar Navigation --}}
        <div class="help-sidebar" style="position: sticky; top: 100px;">
            <div style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid var(--c-hairline);background:var(--c-surface-soft); border:1px solid var(--c-hairline); border-radius: 0; padding: 16px;">
                <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--c-mute); margin-bottom: 16px; padding-left: 12px;">Navigasi Bantuan</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 4px;">
                    <li><a href="#cara-membeli" class="btn-ghost" style="width: 100%; justify-content: flex-start; padding: 10px 12px; font-weight: 600; font-size: 14px; border-radius: 0; color: var(--c-body); transition: all 0.2s;" onmouseover="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseout="this.style.background='transparent'; this.style.color='var(--c-body)';">Cara Membeli</a></li>
                    <li><a href="#metode-pembayaran" class="btn-ghost" style="width: 100%; justify-content: flex-start; padding: 10px 12px; font-weight: 600; font-size: 14px; border-radius: 0; color: var(--c-body); transition: all 0.2s;" onmouseover="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseout="this.style.background='transparent'; this.style.color='var(--c-body)';">Metode Pembayaran</a></li>
                    <li><a href="#kebijakan-refund" class="btn-ghost" style="width: 100%; justify-content: flex-start; padding: 10px 12px; font-weight: 600; font-size: 14px; border-radius: 0; color: var(--c-body); transition: all 0.2s;" onmouseover="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseout="this.style.background='transparent'; this.style.color='var(--c-body)';">Kebijakan Refund</a></li>
                    <li><a href="#syarat-ketentuan" class="btn-ghost" style="width: 100%; justify-content: flex-start; padding: 10px 12px; font-weight: 600; font-size: 14px; border-radius: 0; color: var(--c-body); transition: all 0.2s;" onmouseover="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseout="this.style.background='transparent'; this.style.color='var(--c-body)';">Syarat & Ketentuan</a></li>
                    <li><a href="#privasi" class="btn-ghost" style="width: 100%; justify-content: flex-start; padding: 10px 12px; font-weight: 600; font-size: 14px; border-radius: 0; color: var(--c-body); transition: all 0.2s;" onmouseover="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseout="this.style.background='transparent'; this.style.color='var(--c-body)';">Privasi</a></li>
                </ul>
            </div>
        </div>

        {{-- Content --}}
        <div style="display: flex; flex-direction: column; gap: 32px;">
            
            <div id="cara-membeli" class="help-content-card" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid var(--c-hairline);border-radius: 0; padding: 32px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-shopping-cart" style="color: var(--c-primary);"></i> Cara Membeli
                </h2>
                <div style="color: var(--c-body); line-height: 1.8; font-size: 0.95rem;">
                    <ol style="padding-left: 20px; display: flex; flex-direction: column; gap: 12px;">
                        <li><strong>Cari Game:</strong> Gunakan fitur pencarian atau telusuri katalog kami untuk menemukan game yang ingin Anda beli.</li>
                        <li><strong>Tambah ke Keranjang:</strong> Klik tombol "Tambah ke Keranjang" pada halaman detail game.</li>
                        <li><strong>Checkout:</strong> Buka Keranjang belanja Anda (di menu navigasi atas) dan klik "Lanjut ke Pembayaran".</li>
                        <li><strong>Pilih Pembayaran:</strong> Pilih metode pembayaran yang paling nyaman untuk Anda melalui Midtrans.</li>
                        <li><strong>Selesai:</strong> Setelah pembayaran berhasil diverifikasi, game akan otomatis masuk ke dalam <strong>Library Game</strong> Anda dan siap untuk diakses!</li>
                    </ol>
                </div>
            </div>

            <div id="metode-pembayaran" class="help-content-card" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid var(--c-hairline);border-radius: 0; padding: 32px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-wallet" style="color: var(--c-primary);"></i> Metode Pembayaran
                </h2>
                <div style="color: var(--c-body); line-height: 1.8; font-size: 0.95rem;">
                    <p style="margin-bottom: 12px;">Kami menggunakan gateway pembayaran terpercaya <strong>Midtrans</strong> untuk memproses semua transaksi dengan aman. Berikut adalah metode pembayaran yang kami dukung:</p>
                    <ul style="padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>Kartu Kredit / Debit:</strong> Visa, Mastercard, JCB.</li>
                        <li><strong>E-Wallet:</strong> GoPay, ShopeePay, OVO, DANA.</li>
                        <li><strong>Virtual Account (VA):</strong> BCA, Mandiri, BNI, BRI, Permata, dsb.</li>
                        <li><strong>Gerai Retail:</strong> Indomaret, Alfamart.</li>
                    </ul>
                    <div style="margin-top: 16px; padding: 12px 16px; background: rgba(118,185,0,0.1); border-left: 3px solid var(--c-primary); border-radius: 0;">
                        <i class="fas fa-shield-alt me-2" style="color: var(--c-primary);"></i> Pembayaran diproses secara instan (kecuali gerai retail) 24/7 tanpa biaya tersembunyi.
                    </div>
                </div>
            </div>

            <div id="kebijakan-refund" class="help-content-card" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid var(--c-hairline);border-radius: 0; padding: 32px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-undo" style="color: var(--c-primary);"></i> Kebijakan Refund
                </h2>
                <div style="color: var(--c-body); line-height: 1.8; font-size: 0.95rem;">
                    <p style="margin-bottom: 12px;">Kami memahami bahwa terkadang Anda mungkin berubah pikiran atau mengalami kendala teknis. Refund (pengembalian dana) dapat diajukan dengan syarat berikut:</p>
                    <ul style="padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
                        <li>Batas waktu maksimal pengajuan refund adalah <strong>14 hari</strong> sejak tanggal pembelian.</li>
                        <li>Game belum dimainkan lebih dari batas waktu yang wajar (hanya jika ada sistem tracking playtime).</li>
                        <li>Hanya berlaku untuk game dasar (base game). DLC dan in-game currency mungkin tidak dapat di-refund.</li>
                    </ul>
                    <p style="margin-top: 12px;">Proses refund memakan waktu hingga <strong>7-14 hari kerja</strong> tergantung pada kebijakan bank penerbit kartu/akun Anda.</p>
                </div>
            </div>

            <div id="syarat-ketentuan" class="help-content-card" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid var(--c-hairline);border-radius: 0; padding: 32px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-file-contract" style="color: var(--c-primary);"></i> Syarat & Ketentuan
                </h2>
                <div style="color: var(--c-body); line-height: 1.8; font-size: 0.95rem;">
                    <ul style="padding-left: 20px; display: flex; flex-direction: column; gap: 12px;">
                        <li>Dengan membuat akun dan melakukan transaksi di GameVault, Anda menyetujui bahwa semua lisensi game bersifat <strong>digital</strong> dan <strong>permanen</strong> terkait dengan akun Anda.</li>
                        <li>Anda dilarang keras untuk membagikan kredensial akun Anda kepada pihak lain. Segala aktivitas dari akun Anda adalah tanggung jawab Anda sepenuhnya.</li>
                        <li>Harga sewaktu-waktu dapat berubah tanpa pemberitahuan sebelumnya, kecuali game yang sudah berada dalam pesanan (invoice) yang belum expired.</li>
                        <li>Semua promo dan diskon tunduk pada ketentuan waktu yang berlaku.</li>
                    </ul>
                </div>
            </div>

            <div id="privasi" class="help-content-card" style="background:var(--c-surface-soft); border:1px solid var(--c-hairline); border-radius: 0; padding: 32px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--c-ink); margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-user-shield" style="color: var(--c-primary);"></i> Privasi
                </h2>
                <div style="color: var(--c-body); line-height: 1.8; font-size: 0.95rem;">
                    <p style="margin-bottom: 12px;">Privasi Anda adalah prioritas kami. Kami berkomitmen untuk melindungi data pribadi Anda:</p>
                    <ul style="padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
                        <li><strong>Pengumpulan Data:</strong> Kami hanya mengumpulkan informasi yang diperlukan untuk pemrosesan pesanan, seperti nama, email, dan detail kontak.</li>
                        <li><strong>Keamanan:</strong> Semua informasi pribadi Anda disimpan dalam server terenkripsi. Kata sandi Anda (password) selalu kami lindungi menggunakan hashing bcrypt.</li>
                        <li><strong>Pembagian Data:</strong> Kami <strong>tidak pernah</strong> menjual data Anda ke pihak ketiga. Data hanya dibagikan ke penyedia pembayaran (gateway) secara aman guna memproses tagihan.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Smooth scrolling for sticky sidebar links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                // Adjust for sticky header
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });
            }
        });
    });
</script>

{{-- VaultBot Floating Chat Widget --}}
<div id="vaultbot-widget" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999;">
    <!-- Chat Icon -->
    <button id="vaultbot-toggle" style="width: 60px; height: 60px; border-radius: 0; background: var(--c-primary); color: #000; border: none; font-size: 24px; cursor: pointer; box-shadow: 0 4px 15px rgba(118,185,0,0.4); display: flex; align-items: center; justify-content: center; transition: transform 0.2s;">
        <i class="fas fa-robot"></i>
    </button>

    <!-- Chat Box -->
    <div id="vaultbot-box" style="display: none; position: absolute; bottom: 80px; right: 0; width: 350px; max-width: calc(100vw - 60px); height: 500px; max-height: calc(100vh - 120px); background: var(--c-surface-elevated); border: 1px solid var(--c-hairline); border-radius: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.5); flex-direction: column; overflow: hidden;">
        <!-- Header -->
        <div style="background: var(--c-primary); color: #000; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-robot" style="font-size: 20px;"></i>
                <div>
                    <div style="font-weight: 700; font-size: 1rem; line-height: 1;">VaultBot AI</div>
                    <div style="font-size: 0.75rem; font-weight: 600; opacity: 0.8; margin-top: 2px;">GameVault Support</div>
                </div>
            </div>
            <button id="vaultbot-close" style="background: transparent; border: none; color: #000; font-size: 1.2rem; cursor: pointer;"><i class="fas fa-times"></i></button>
        </div>

        <!-- Messages Area -->
        <div id="vaultbot-messages" style="flex: 1; padding: 16px; overflow-y: auto; display: flex; flex-direction: column; gap: 16px; background: var(--c-surface-dark);">
            <!-- Initial Bot Message -->
            <div style="align-self: flex-start; background: var(--c-surface-soft); color: var(--c-body); padding: 12px 16px; border-radius: 0; border-bottom-left-radius: 4px; max-width: 85%; font-size: 0.9rem; line-height: 1.5;">
                Halo! Aku VaultBot 🤖. Ada yang bisa aku bantu seputar GameVault? (Pembelian, Refund, Library, dsb)
            </div>
        </div>

        <!-- Input Area -->
        <div style="padding: 16px; background: var(--c-surface-elevated); border-top: 1px solid var(--c-hairline); display: flex; gap: 8px;">
            <input type="text" id="vaultbot-input" placeholder="Ketik pesan..." style="flex: 1; background: var(--c-surface-dark); border: 1px solid var(--c-hairline); color: var(--c-ink); padding: 10px 14px; border-radius: 0; outline: none; font-size: 0.9rem;">
            <button id="vaultbot-send" style="background: var(--c-primary); color: #000; border: none; border-radius: 0; width: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer;"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
    // --- VaultBot Chat Logic ---
    const chatToggle = document.getElementById('vaultbot-toggle');
    const chatBox = document.getElementById('vaultbot-box');
    const chatClose = document.getElementById('vaultbot-close');
    const messagesArea = document.getElementById('vaultbot-messages');
    const inputField = document.getElementById('vaultbot-input');
    const sendBtn = document.getElementById('vaultbot-send');

    let chatHistory = [];

    // Toggle Chat
    chatToggle.addEventListener('click', () => {
        chatBox.style.display = chatBox.style.display === 'none' ? 'flex' : 'none';
        if(chatBox.style.display === 'flex') inputField.focus();
    });
    chatClose.addEventListener('click', () => {
        chatBox.style.display = 'none';
    });

    // Formatting simple markdown from bot
    function parseBotText(text) {
        return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                   .replace(/\*(.*?)\*/g, '<em>$1</em>')
                   .replace(/\n/g, '<br>');
    }

    function appendMessage(text, role) {
        const msgDiv = document.createElement('div');
        msgDiv.style.padding = '12px 16px';
        msgDiv.style.borderRadius = '12px';
        msgDiv.style.maxWidth = '85%';
        msgDiv.style.fontSize = '0.9rem';
        msgDiv.style.lineHeight = '1.5';
        
        if(role === 'user') {
            msgDiv.style.alignSelf = 'flex-end';
            msgDiv.style.background = 'var(--c-primary)';
            msgDiv.style.color = '#000';
            msgDiv.style.borderBottomRightRadius = '4px';
            msgDiv.textContent = text; // user text is raw
        } else {
            msgDiv.style.alignSelf = 'flex-start';
            msgDiv.style.background = 'var(--c-surface-soft)';
            msgDiv.style.color = 'var(--c-body)';
            msgDiv.style.borderBottomLeftRadius = '4px';
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
        typingDiv.style.fontSize = '0.85rem';
        typingDiv.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> VaultBot sedang mengetik...';
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

        // UI update
        appendMessage(text, 'user');
        inputField.value = '';
        showTyping();
        
        // Prepare payload
        const payload = {
            message: text,
            history: chatHistory
        };
        
        // Add user msg to local history
        chatHistory.push({ role: 'user', text: text });

        // Add csrf token manually if meta doesn't exist, though it should
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
