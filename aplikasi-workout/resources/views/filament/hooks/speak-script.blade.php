<script>
    document.addEventListener('livewire:initialized', () => {
        // Kode ini akan berjalan setelah Livewire (mesin Filament) siap
        
        // Kita membuat 'pendengar' untuk sinyal 'speak-text' dari backend
        Livewire.on('speak-text', (event) => {
            
            // Pertama, cek apakah browser pengguna mendukung fitur ini
            if ('speechSynthesis' in window) {
                // Ambil data teks yang dikirim dari backend
                const text = event.text;

                // Hentikan suara lain yang mungkin sedang berjalan untuk mencegah tumpang tindih
                window.speechSynthesis.cancel(); 

                // Buat objek 'ucapan' baru dengan teks yang kita terima
                const utterance = new SpeechSynthesisUtterance(text);

                // Atur beberapa properti agar terdengar lebih natural (opsional)
                utterance.lang = 'id-ID'; // Penting! Atur bahasa ke Indonesia
                utterance.rate = 0.9;     // Atur kecepatan bicara

                // Perintahkan browser untuk mulai berbicara!
                window.speechSynthesis.speak(utterance);
                
            } else {
                // Jika browser tidak mendukung, beri tahu pengguna
                alert('Maaf, browser Anda tidak mendukung fitur panduan suara.');
            }
        });
    });
</script>