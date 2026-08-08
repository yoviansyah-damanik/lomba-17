<?php

namespace App\Livewire\LiveRank;

use App\Models\Competition;
use App\Models\Registration;
use Livewire\Attributes\Url;
use Livewire\Component;

class Board extends Component
{
    public const SCHOOL_TYPES = ['SD', 'SMP', 'SMA'];

    #[Url]
    public string $school_type = 'SD';

    public string $competitionId;

    public function mount(string $competitionId): void
    {
        $this->competitionId = $competitionId;

        if (! in_array($this->school_type, self::SCHOOL_TYPES, true)) {
            $this->school_type = 'SD';
        }
    }

    public function render()
    {
        $competition = Competition::with('criteria.judges')
            ->where('id', $this->competitionId)
            ->where('rank_open', true)
            ->first();

        $registrations = collect();

        if ($competition) {
            $registrations = $competition->registrations()
                ->with('participant')
                ->withSum('evaluations as total_score', 'score')
                ->withCount('evaluations as evaluations_count')
                ->when($this->school_type, fn ($query) => $query->where('school_type', $this->school_type))
                ->get();

            $registrations = Registration::sortForRanking($registrations)
                ->map(function ($registration) use ($competition) {
                    $expected = $competition->criteria
                        ->filter(fn ($criterion) => in_array($registration->school_type, $criterion->school_types, true))
                        ->sum(fn ($criterion) => $criterion->judges->where('pivot.school_type', $registration->school_type)->count());

                    $registration->is_complete = $expected > 0 && $registration->evaluations_count >= $expected;

                    return $registration;
                });
        }

        return view('livewire.live-rank.board', [
            'competition' => $competition,
            'participants' => $registrations,
            'maskIdentity' => $competition && $competition->hide_identity,
        ]);
    }
}
