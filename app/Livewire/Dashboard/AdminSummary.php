<?php

namespace App\Livewire\Dashboard;

use App\Models\Competition;
use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class AdminSummary extends Component
{
    /**
     * Jumlah penilaian masuk per hari, 7 hari terakhir.
     *
     * @return array<string, int>
     */
    protected function evaluationTrend(): array
    {
        $since = Carbon::now()->subDays(6)->startOfDay();

        $counts = Evaluation::where('created_at', '>=', $since)
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
        $registrationCountByCompetition = Registration::selectRaw('competition_id, count(*) as total')
            ->groupBy('competition_id')
            ->pluck('total', 'competition_id');

        $competitionProgress = Competition::withCount('criteria')
            ->get()
            ->map(function (Competition $competition) use ($registrationCountByCompetition) {
                $registrationCount = $registrationCountByCompetition[$competition->id] ?? 0;
                $expected = Criterion::where('competition_id', $competition->id)->withCount('judges')->get()->sum('judges_count') * $registrationCount;
                $actual = Evaluation::whereHas('criterion', fn ($query) => $query->where('competition_id', $competition->id))->count();

                return ['label' => $competition->name, 'value' => $actual, 'max' => $expected];
            })
            ->filter(fn (array $row) => $row['max'] > 0)
            ->values();

        $judgeProgress = User::where('role', 'judge')
            ->with('criteria')
            ->withCount('evaluations')
            ->get()
            ->map(function (User $judge) use ($registrationCountByCompetition) {
                $max = $judge->criteria->sum(fn (Criterion $criterion) => $registrationCountByCompetition[$criterion->competition_id] ?? 0);

                return [
                    'label' => $judge->name,
                    'value' => $judge->evaluations_count,
                    'max' => $max,
                ];
            })
            ->filter(fn (array $row) => $row['max'] > 0)
            ->sortByDesc('value')
            ->values();

        return view('livewire.dashboard.admin-summary', [
            'participantsByType' => Participant::selectRaw('school_type, count(*) as total')->groupBy('school_type')->pluck('total', 'school_type'),
            'judgeCount' => User::where('role', 'judge')->count(),
            'criterionCount' => Criterion::count(),
            'evaluationCount' => Evaluation::count(),
            'expectedEvaluations' => $competitionProgress->sum('max'),
            'trend' => $this->evaluationTrend(),
            'competitionProgress' => $competitionProgress,
            'judgeProgress' => $judgeProgress,
        ]);
    }
}
