<?php

namespace App\Livewire\Dashboard;

use App\Models\Criterion;
use App\Models\Evaluation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class JudgeProgress extends Component
{
    /**
     * Jumlah penilaian yang dimasukkan juri ini per hari, 7 hari terakhir.
     *
     * @return array<string, int>
     */
    protected function evaluationTrend(string $judgeId): array
    {
        $since = Carbon::now()->subDays(6)->startOfDay();

        $counts = Evaluation::where('user_id', $judgeId)
            ->where('created_at', '>=', $since)
            ->get()
            ->groupBy(fn (Evaluation $evaluation) => $evaluation->created_at->format('Y-m-d'))
            ->map->count();

        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $trend[$date->translatedFormat('d M')] = $counts[$date->format('Y-m-d')] ?? 0;
        }

        return $trend;
    }

    public function render()
    {
        /** @var \App\Models\User $judge */
        $judge = Auth::user();

        $criteria = $judge->criteria()
            ->with('competition')
            ->get()
            ->map(function (Criterion $criterion) use ($judge) {
                $schoolType = $criterion->pivot->school_type;

                $criterion->assignment_school_type = $schoolType;
                $criterion->registrations_count = $criterion->competition->registrations()
                    ->where('school_type', $schoolType)
                    ->count();
                $criterion->evaluations_count = Evaluation::where('user_id', $judge->id)
                    ->where('criterion_id', $criterion->id)
                    ->whereHas('registration', fn ($query) => $query->where('school_type', $schoolType))
                    ->count();

                return $criterion;
            });

        $totalAssigned = $criteria->sum('registrations_count');
        $totalCompleted = $criteria->sum('evaluations_count');

        $recentEvaluations = Evaluation::where('user_id', $judge->id)
            ->with(['registration', 'criterion.competition'])
            ->latest('updated_at')
            ->limit(6)
            ->get();

        return view('livewire.dashboard.judge-progress', [
            'criteria' => $criteria,
            'totalAssigned' => $totalAssigned,
            'totalCompleted' => $totalCompleted,
            'trend' => $this->evaluationTrend($judge->id),
            'recentEvaluations' => $recentEvaluations,
        ]);
    }
}
