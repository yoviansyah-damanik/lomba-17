<?php

namespace App\Livewire\Admin;

use App\Models\Participant as ParticipantModel;
use Livewire\Component;

class ParticipantHistory extends Component
{
    public string $participantId;

    public function mount(string $participantId): void
    {
        $this->participantId = $participantId;
    }

    /** @return \Illuminate\Support\Collection<int, object> */
    protected function rows(ParticipantModel $participant)
    {
        return $participant->registrations()->with('competition')->get()
            ->sortByDesc(fn ($registration) => $registration->competition->start_time)
            ->map(function ($registration) use ($participant) {
                $competition = $registration->competition;

                $peers = $competition->registrations()
                    ->where('school_type', $participant->school_type)
                    ->withSum('evaluations as total_score', 'score')
                    ->orderByDesc('total_score')
                    ->get();

                $rank = $peers->search(fn ($peer) => $peer->id === $registration->id);

                return (object) [
                    'competition' => $competition,
                    'npp' => $registration->npp,
                    'total_score' => $peers->firstWhere('id', $registration->id)?->total_score ?? 0,
                    'rank' => $rank === false ? null : $rank + 1,
                    'total_peers' => $peers->count(),
                ];
            })
            ->values();
    }

    public function render()
    {
        $participant = ParticipantModel::findOrFail($this->participantId);

        return view('livewire.admin.participant-history', [
            'participant' => $participant,
            'rows' => $this->rows($participant),
        ]);
    }
}
