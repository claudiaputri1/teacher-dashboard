<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Report extends Page
{
    protected string $view = 'filament.pages.report';

    public function getAverageClassScore(int $classId)
{
    // Mengambil rata-rata nilai dari semua progress siswa di kelas yang dipilih
    $averageScore = StudentProgress::whereHas('student', function ($query) use ($classId) {
        $query->where('class_id', $classId);
    })->avg('score');

    return round($averageScore, 1);
}
}
