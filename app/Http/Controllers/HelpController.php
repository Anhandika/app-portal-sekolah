<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function faq()
    {
        $faqs = [
            // ===== TENTANG PAS & KEBIJAKAN UMUM =====
            [
                'cat' => 'Tentang PAS',
                'q' => 'Apa itu PAS - Portal Academy Sekolah?',
                'a' => 'PAS adalah platform akademik digital multi-sekolah yang menyatukan absensi, tugas, nilai, SPP, perpustakaan, ekstrakurikuler, chat, dan Global Portal antar-sekolah dalam satu aplikasi web dan Android. Satu akun dapat digunakan sesuai peran Anda (Siswa, Guru, atau Admin Sekolah).'
            ],
            [
                'cat' => 'Tentang PAS',
                'q' => 'Siapa saja yang boleh menggunakan PAS?',
                'a' => 'Siswa dan guru dari sekolah yang telah terdaftar dan diaktifkan oleh Admin Pusat, serta Admin Sekolah yang ditunjuk. Pendaftaran membutuhkan Kode Pendaftaran resmi dari sekolah Anda — akun tanpa kode yang valid akan otomatis ditolak.'
            ],
            [
                'cat' => 'Tentang PAS',
                'q' => 'Apakah PAS gratis?',
                'a' => 'Ya. Seluruh fitur inti PAS (absensi, tugas, nilai, chat, Global Portal) gratis untuk sekolah, siswa, dan guru yang terdaftar. Tidak ada biaya tersembunyi dan tidak ada fitur yang dikunci di balik pembayaran.'
            ],
            [
                'cat' => 'Tentang PAS',
                'q' => 'Bagaimana kebijakan akun yang tidak aktif atau lulus?',
                'a' => 'Akun siswa yang telah lulus atau pindah sekolah akan dinonaktifkan oleh Admin Sekolah setelah tahun ajaran berakhir. Data akademik (nilai, rapor) tetap tersimpan sebagai arsip sekolah sesuai ketentuan retensi data, sedangkan akses login akan dicabut.'
            ],
            // ===== PENDAFTARAN & AKUN =====
            [
                'cat' => 'Akun & Login',
                'q' => 'Bagaimana cara mendaftar akun baru?',
                'a' => 'Minta Kode Pendaftaran ke admin sekolah Anda, buka halaman Pendaftaran, masukkan kode tersebut (data sekolah terisi otomatis), pilih peran Guru/Siswa yang sedang dibuka, lalu lengkapi data diri. Akun Anda aktif setelah diverifikasi Admin Sekolah.'
            ],
            [
                'cat' => 'Akun & Login',
                'q' => 'Bagaimana jika saya lupa password?',
                'a' => 'Anda dapat menggunakan fitur "Lupa Password" di halaman login. Masukkan email terdaftar, dan sistem akan mengirimkan link reset. Jika email tidak aktif, silakan hubungi Guru Pembimbing atau bagian IT sekolah.'
            ],
            [
                'cat' => 'Akun & Login',
                'q' => 'Apakah akun saya bisa dibuka di dua perangkat sekaligus?',
                'a' => 'Ya, namun demi keamanan kami menyarankan untuk tetap login di satu perangkat utama. Setiap sesi login baru akan tercatat di sistem keamanan kami.'
            ],
            [
                'cat' => 'Akun & Login',
                'q' => 'Bagaimana cara keluar (logout) dengan aman?',
                'a' => 'Gunakan tombol KELUAR di halaman Profil. Sistem akan menghapus seluruh sesi, token perangkat, dan data sementara di aplikasi. Jangan hanya menutup aplikasi tanpa logout, terutama di perangkat bersama (milik sekolah/warnet).'
            ],
            // ===== PRIVASI & PERLINDUNGAN DATA (UU PDP) =====
            [
                'cat' => 'Privasi & Data',
                'q' => 'Data pribadi apa saja yang dikumpulkan PAS?',
                'a' => 'Kami mengumpulkan data yang diperlukan untuk layanan akademik: nama, NIK/NIS, email, nomor HP, kelas, sekolah, foto profil, data kehadiran (termasuk lokasi GPS saat absensi), nilai, dan riwayat aktivitas belajar. Kami TIDAK mengumpulkan data di luar kebutuhan tersebut.'
            ],
            [
                'cat' => 'Privasi & Data',
                'q' => 'Apakah data saya dilindungi undang-undang?',
                'a' => 'Ya. Pengelolaan data di PAS mengacu pada UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP) yang berlaku penuh sejak Oktober 2024. Sekolah bertindak sebagai pengendali data, dan kami memproses data secara sah, terbatas pada tujuan akademik, transparan, akurat, dan aman.'
            ],
            [
                'cat' => 'Privasi & Data',
                'q' => 'Apakah data saya dijual atau dibagikan ke pihak ketiga?',
                'a' => 'TIDAK, dan tidak akan pernah. Data Anda tidak diperjualbelikan dan tidak dibagikan untuk kepentingan pemasaran. Data hanya diproses untuk keperluan akademik dan dibagikan terbatas kepada pihak yang berhak (sekolah, guru, dan orang tua/wali) sesuai peran masing-masing.'
            ],
            [
                'cat' => 'Privasi & Data',
                'q' => 'Apa hak saya atas data pribadi saya?',
                'a' => 'Sesuai UU PDP, Anda berhak: (1) memperoleh informasi tujuan pengumpulan data; (2) mengakses dan meminta salinan data Anda; (3) memperbaiki data yang tidak akurat melalui menu Edit Profil atau bantuan admin; (4) meminta penghapusan data yang sudah tidak relevan; (5) menarik persetujuan pemrosesan data tertentu. Hubungi Admin Sekolah atau Admin Pusat untuk menggunakan hak-hak ini.'
            ],
            [
                'cat' => 'Privasi & Data',
                'q' => 'Berapa lama data saya disimpan?',
                'a' => 'Data aktif disimpan selama Anda terdaftar sebagai siswa/guru. Setelah lulus, pindah, atau keluar, akses login dicabut dan data pribadi dihapus atau dianonimkan, kecuali arsip akademik (nilai/rapor) yang wajib disimpan sekolah sebagai dokumen resmi sesuai ketentuan yang berlaku.'
            ],
            [
                'cat' => 'Privasi & Data',
                'q' => 'Apa yang terjadi jika ada kebocoran data?',
                'a' => 'Kami wajib memberitahukan kebocoran data pribadi kepada Anda dan otoritas terkait sesuai UU PDP, disertai langkah pemulihan (reset kredensial, audit keamanan). Laporkan dugaan kebocoran segera ke Admin Sekolah atau adminpusat@pusat.com.'
            ],
            // ===== KEAMANAN =====
            [
                'cat' => 'Keamanan',
                'q' => 'Apakah fitur Biometrik (Sidik Jari) aman digunakan?',
                'a' => 'Sangat aman. Aplikasi tidak menyimpan data sidik jari Anda, melainkan hanya memverifikasi kunci yang sudah ada di sistem Android perangkat Anda.'
            ],
            [
                'cat' => 'Keamanan',
                'q' => 'Bagaimana cara mengamankan akun saya?',
                'a' => 'Gunakan password yang kuat dan berbeda dari akun lain, aktifkan Kunci PIN + Biometrik di menu Keamanan, jangan bagikan kode OTP/password kepada siapa pun (termasuk yang mengaku admin), dan selalu logout di perangkat bersama.'
            ],
            // ===== KEBIJAKAN KONTEN GLOBAL PORTAL =====
            [
                'cat' => 'Global Portal',
                'q' => 'Apa itu Global Portal dan apa aturannya?',
                'a' => 'Global Portal adalah linimasa sosial antar-sekolah (postingan, stories 24 jam, suka, komentar, ikuti). Kebijakannya: dilarang konten SARA, pornografi, kekerasan, perundungan (bullying), ujaran kebencian, hoaks, promosi judi/pinjol, dan data pribadi orang lain (mis. nomor HP, alamat rumah).'
            ],
            [
                'cat' => 'Global Portal',
                'q' => 'Bagaimana moderasi konten bekerja?',
                'a' => 'Tiga lapis: (1) filter kata otomatis; (2) pemeriksaan gambar oleh AI; (3) laporan komunitas. Postingan yang dilaporkan 3 pengguna berbeda akan otomatis disembunyikan menunggu peninjauan admin.'
            ],
            [
                'cat' => 'Global Portal',
                'q' => 'Apa sanksi pelanggaran konten?',
                'a' => 'Bertahap: peringatan dan penghapusan konten (pelanggaran pertama), pembatasan posting sementara (berulang), hingga penonaktifan akun permanen untuk pelanggaran berat (pornografi anak, ujaran kebencian ekstrem, doxing). Keputusan admin bersifat final dan dapat diajukan banding melalui Guru Pembimbing.'
            ],
            [
                'cat' => 'Global Portal',
                'q' => 'Bagaimana cara melaporkan konten yang melanggar?',
                'a' => 'Gunakan tombol Laporkan pada postingan/komentar yang melanggar. Sertakan alasan yang jelas. Identitas pelapor dirahasiakan dan tidak akan ditampilkan kepada pemilik konten.'
            ],
            // ===== ABSENSI =====
            [
                'cat' => 'Absensi',
                'q' => 'Mengapa lokasi saya tidak terdeteksi saat absensi?',
                'a' => 'Pastikan GPS perangkat aktif dan Anda telah memberikan izin lokasi (High Accuracy) kepada aplikasi. Jika masih terkendala, coba buka Google Maps sejenak untuk memperbarui koordinat GPS Anda.'
            ],
            [
                'cat' => 'Absensi',
                'q' => 'Apa yang harus dilakukan jika gagal melakukan Vermuk (Verifikasi Muka)?',
                'a' => 'Pastikan wajah berada di area terang (cukup cahaya), tidak menggunakan masker atau kacamata hitam yang menutupi area mata/hidung. Gunakan latar belakang yang polos jika memungkinkan.'
            ],
            [
                'cat' => 'Absensi',
                'q' => 'Untuk apa data lokasi dan foto absensi saya digunakan?',
                'a' => 'Semata-mata untuk memverifikasi kehadiran Anda di lokasi sekolah pada waktu yang tercatat. Data ini hanya dapat dilihat oleh guru dan admin sekolah Anda, tidak dipublikasikan, dan tidak digunakan untuk pelacakan di luar jam absensi.'
            ],
            // ===== TUGAS & LMS =====
            [
                'cat' => 'Tugas & LMS',
                'q' => 'Bagaimana cara mengunggah tugas dalam bentuk file besar?',
                'a' => 'Batas maksimal unggahan file adalah 10MB. Jika file Anda lebih besar dari itu, kami menyarankan untuk mengunggahnya ke Google Drive/OneDrive lalu mencantumkan link-nya di kolom deskripsi tugas.'
            ],
            [
                'cat' => 'Tugas & LMS',
                'q' => 'Tugas sudah dikirim tapi statusnya masih "Belum Dinilai"?',
                'a' => 'Status "Belum Dinilai" berarti tugas Anda sudah masuk ke sistem guru, namun guru yang bersangkutan belum memberikan evaluasi. Anda akan menerima notifikasi otomatis saat nilai sudah diberikan.'
            ],
            [
                'cat' => 'Tugas & LMS',
                'q' => 'Apakah boleh mengumpulkan tugas melewati tenggat (deadline)?',
                'a' => 'Keterlambatan mengikuti kebijakan masing-masing guru/mata pelajaran. Sistem mencatat waktu pengumpulan apa adanya sehingga guru dapat melihat status tepat waktu atau terlambat. Hubungi guru Anda jika ada kendala.'
            ],
            // ===== SPP =====
            [
                'cat' => 'SPP & Keuangan',
                'q' => 'Bagaimana cara melihat tagihan dan riwayat pembayaran SPP?',
                'a' => 'Buka menu SPP di dashboard untuk melihat tagihan berjalan dan riwayat pembayaran Anda. Setiap pembayaran yang dicatat admin akan memunculkan bukti dan notifikasi.'
            ],
            [
                'cat' => 'SPP & Keuangan',
                'q' => 'Siapa yang dapat melihat data pembayaran SPP saya?',
                'a' => 'Hanya Anda, orang tua/wali (melalui akun terkait), dan admin keuangan sekolah. Data keuangan tidak ditampilkan ke pengguna lain dan tidak dipublikasikan di Global Portal.'
            ],
            // ===== NOTIFIKASI =====
            [
                'cat' => 'Notifikasi',
                'q' => 'Mengapa saya tidak menerima notifikasi tugas baru?',
                'a' => 'Periksa pengaturan notifikasi di Profil > Pengaturan Notifikasi. Pastikan "Polling Otomatis" aktif dan aplikasi tidak dibatasi oleh fitur "Penghemat Baterai" sistem Android Anda.'
            ],
            // ===== TEKNIS =====
            [
                'cat' => 'Teknis',
                'q' => 'Aplikasi terasa lambat atau sering tertutup sendiri (Force Close)?',
                'a' => 'Coba bersihkan cache aplikasi melalui Pengaturan Android > Aplikasi > Portal Sekolah > Hapus Cache. Pastikan Anda juga menggunakan versi aplikasi terbaru.'
            ],
            [
                'cat' => 'Teknis',
                'q' => 'Apakah PAS bisa digunakan saat offline (tanpa internet)?',
                'a' => 'Sebagian. Halaman yang sudah pernah dibuka dapat tampil dari cache, tetapi absensi, chat, pengumpulan tugas, dan Global Portal membutuhkan koneksi internet. Data yang dibuat offline tidak akan tersimpan — pastikan Anda online saat beraktivitas penting.'
            ],
            [
                'cat' => 'Teknis',
                'q' => 'Di mana saya mengunduh aplikasi Android (APK) resmi?',
                'a' => 'Hanya dari halaman login/pendaftaran resmi atau situs sekolah Anda (tombol "Unduh Aplikasi Android"). Jangan menginstal APK dari sumber tidak dikenal karena berisiko malware dan pencurian akun.'
            ],
        ];

        return view('mobile.faq', compact('faqs'));
    }

    public function about()
    {
        return view('mobile.about');
    }

    public function security()
    {
        return view('mobile.security');
    }

    public function notificationSettings()
    {
        return view('mobile.notification-settings');
    }
}
