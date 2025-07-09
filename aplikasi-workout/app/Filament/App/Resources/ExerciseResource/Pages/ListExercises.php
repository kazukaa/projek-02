<?php

namespace App\Filament\App\Resources\ExerciseResource\Pages;

use App\Filament\App\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Models\UserActivity;
use App\Services\AchievementService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

/**
 * Halaman untuk menampilkan daftar Latihan (Exercises).
 * Halaman ini juga bertanggung jawab untuk menangani event
 * saat seorang pengguna menyelesaikan latihan.
 */
class ListExercises extends ListRecords
{
    protected static string $resource = ExerciseResource::class;

    /**
     * Metode ini akan dijalankan saat event 'markExerciseAsComplete' dari frontend diterima.
     * Ia akan mencatat aktivitas, memeriksa pencapaian, dan me-refresh widget.
     *
     * @param array $data Data yang dikirim dari frontend, berisi 'exerciseId'
     */
    #[On('markExerciseAsComplete')]
    public function markExerciseAsComplete(array $data = []): void
    {
        if (empty($data['exerciseId'])) {
            return;
        }

        // 1. Buat catatan aktivitas bahwa latihan telah selesai
        UserActivity::create([
            'user_id'       => Auth::id(),
            'activity_type' => 'exercise_completed',
            'related_id'    => $data['exerciseId'],
            'related_type'  => Exercise::class,
        ]);

        // 2. Kirim notifikasi sukses ke pengguna
        Notification::make()
            ->title('Latihan Selesai!')
            ->body('Kerja bagus! Aktivitasmu sudah tercatat.')
            ->success()
            ->send();

        // 3. Panggil service untuk mengecek pencapaian baru
        try {
            $achievementService = app(AchievementService::class);
            $achievementService->checkAndAwardAchievements(Auth::user());
        } catch (\Exception $e) {
            // Log error jika service gagal, agar tidak menghentikan proses utama
            Log::error('AchievementService failed: ' . $e->getMessage());
        }

        // 4. Kirim sinyal untuk me-refresh widget statistik di dashboard
        $this->dispatch('stats-overview-updated');
    }

    /**
     * Mengatur action (tombol) di bagian header halaman.
     */
    protected function getHeaderActions(): array
    {
        // Kembalikan array kosong agar tidak ada tombol yang muncul di header
        return [];
    }
}
