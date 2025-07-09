<div id="timer-overlay">
    <div id="timer-container">
        <div id="timer-display">00:00</div>
        <div id="timer-controls">
            <button id="pause-resume-btn">Pause</button>
            <button id="finish-btn">Selesai</button>
            <button id="close-timer-btn">Tutup</button>
        </div>
    </div>
</div>


<style>
    #timer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.75);
        display: none;
        /* Disembunyikan secara default */
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    #timer-container {
        background-color: #1f2937;
        padding: 40px;
        border-radius: 50%;
        width: 300px;
        height: 300px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.5);
        border: 5px solid #4f46e5;
        text-align: center;
    }

    #timer-display {
        color: white;
        font-size: 4rem;
        /* 64px */
        font-weight: bold;
        font-family: monospace, sans-serif;
    }

    #timer-controls button {
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 15px 5px 0 5px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        /* 16px */
        transition: background-color 0.2s;
    }

    #pause-resume-btn {
        background-color: #4f46e5;
    }

    #pause-resume-btn:hover {
        background-color: #4338ca;
    }

    #finish-btn {
        background-color: #16a34a;
        /* Warna hijau */
        display: none;
        /* Sembunyi secara default */
    }

    #finish-btn:hover {
        background-color: #15803d;
    }

    #close-timer-btn {
        background-color: #6b7280;
    }

    #close-timer-btn:hover {
        background-color: #4b5563;
    }
</style>


<script>
    document.addEventListener('livewire:initialized', () => {
        // Mengambil semua elemen dari DOM untuk dimanipulasi
        const timerOverlay = document.getElementById('timer-overlay');
        const timerDisplay = document.getElementById('timer-display');
        const pauseResumeBtn = document.getElementById('pause-resume-btn');
        const closeTimerBtn = document.getElementById('close-timer-btn');
        const finishBtn = document.getElementById('finish-btn');

        // Suara notifikasi saat timer selesai
        const finishSound = new Audio('https://www.soundjay.com/buttons/sounds/button-1.mp3');

        // Variabel untuk menyimpan state (status) dari timer
        let countdownInterval;
        let timeLeftInSeconds;
        let isPaused = false;
        let currentExerciseId = null;
        let initialDuration = 0;


        // Fungsi bantuan untuk memformat detik menjadi format MM:SS
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
        }

        // Fungsi utama untuk menjalankan timer
        function runTimer(duration) {
            clearInterval(countdownInterval);

            initialDuration = duration;
            timeLeftInSeconds = duration;
            isPaused = false;

            pauseResumeBtn.textContent = 'Pause';
            finishBtn.style.display = 'none';

            timerOverlay.style.display = 'flex';
            timerDisplay.textContent = formatTime(timeLeftInSeconds);

            countdownInterval = setInterval(() => {
                if (isPaused) return;

                timeLeftInSeconds--;
                timerDisplay.textContent = formatTime(timeLeftInSeconds);

                if (timeLeftInSeconds <= 0) {
                    clearInterval(countdownInterval);
                    timerDisplay.textContent = "SELESAI!";
                    finishSound.play();
                    finishBtn.style.display = 'inline-block';
                }
            }, 1000);
        }


        // Mendengarkan sinyal 'start-timer' dari Filament (PHP)
        Livewire.on('start-timer', (event) => {
            currentExerciseId = event.exerciseId; // Simpan ID Latihan
            runTimer(event.duration);
        });

        // Menambahkan fungsi pada tombol 'Pause/Resume'
        pauseResumeBtn.addEventListener('click', () => {
            isPaused = !isPaused;
            pauseResumeBtn.textContent = isPaused ? 'Resume' : 'Pause';
        });

        // Menambahkan fungsi pada tombol 'Selesai'
        finishBtn.addEventListener('click', () => {
            if (currentExerciseId) {
                const durationUsed = initialDuration - timeLeftInSeconds;

                fetch('/log-workout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        body: JSON.stringify({
                            exercise_id: currentExerciseId,
                            duration_seconds: durationUsed,
                        }),
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal menyimpan workout log');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Log berhasil disimpan:', data);
                        Livewire.dispatch('markExerciseAsComplete', {
                            exerciseId: currentExerciseId
                        });
                        closeTimerBtn.click();
                    })
                    .catch(error => {
                        console.error('Error menyimpan log:', error);
                        alert('Gagal menyimpan log latihan.');
                    });
            }
        });

        // Menambahkan fungsi pada tombol 'Tutup'
        closeTimerBtn.addEventListener('click', () => {
            clearInterval(countdownInterval);
            timerOverlay.style.display = 'none';
        });
    });
</script>
