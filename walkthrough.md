# Perombakan Layout Epic Games Selesai!

Sesuai dengan panduan gambar referensi dari Epic Games Store, saya telah melakukan perombakan besar pada struktur layout dan tampilan Carousel TokoGame.

## 1. Navigasi Sidebar Kiri (Left Sidebar)
![Left Sidebar Design](https://upload.wikimedia.org/wikipedia/commons/3/31/Epic_Games_logo.svg)

Menu navigasi utama (*Toko, Kategori, Bantuan, Library*) yang tadinya ada di bagian paling atas halaman sekarang telah **dipindahkan ke panel navigasi vertikal di sisi kiri layar**. Sidebar ini bersifat lengket (*sticky*), sehingga ketika Anda men-scroll layar ke bawah, menu navigasi akan selalu ada di sebelah kiri Anda. 

Sementara itu, untuk fungsi *Cart*, *Wishlist*, dan *Profil User*, tetap kami pertahankan di barisan kanan atas.

## 2. Desain Hero Carousel ala Epic Games
> [!TIP]
> Tampilan ini dirancang untuk memberikan fokus penuh pada satu game (Active Slide) sekaligus memberikan kemudahan berpindah (*Discover/Browse*) melalui daftar thumbnail.

![Epic Hero](https://cms-assets.unrealengine.com/26eaec387c544bd0b6910ce4205566de)

Carousel pada halaman Beranda tidak lagi menggunakan titik-titik (dot) navigasi. Sebagai gantinya:
- **Kiri (Viewport Utama):** Menampilkan gambar besar (*cover*) dari game yang sedang disorot. Terdapat overlay gelap gradasi, nama game yang mencolok, deskripsi singkat, dan harga/label "GRATIS".
- **Kanan (Thumbnail List):** Sebuah panel vertikal berisi daftar nama game yang masuk ke dalam sorotan (*Hero Games*). 
- **Animasi Progress:** Terdapat animasi garis hijau progres (*progress bar*) yang berjalan pelan pada thumbnail yang sedang aktif, menandakan waktu (5 detik) sebelum gambar berganti secara otomatis. Mengeklik salah satu thumbnail akan secara langsung mengganti gambar besar ke game yang bersangkutan.

Silakan [*refresh* (muat ulang) halaman web TokoGame](http://localhost/tokoGame/public) Anda untuk menikmati tampilan baru yang super *premium* ini!
