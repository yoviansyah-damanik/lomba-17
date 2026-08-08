<?php

namespace App\Livewire\Admin;

use App\Models\Evaluation;
use App\Models\User;
use Livewire\Component;

class JudgeHistory extends Component
{
    public string $judgeId;

    public function mount(string $judgeId): void
    {
        $this->judgeId = $judgeId;
    }

    /** @return \Illuminate\Support\Collection<int, object> */
    protected function rows(User $judge)
    {
        return $judge->criteria()->with('competition')->get()
            ->map(function ($criterion) use ($judge) {
                $submitted = Evaluation::where('user_id', $judge->id)
                    ->where('criterion_id', $criterion->id)
                    ->count();

                $total = $criterion->competition->registrations()->count();

                return (object) [
                    'competition' => $criterion->competition,
                    'criterion' => $criterion,
                    'submitted' => $submitted,
                    'total' => $total,
                ];
            })
            ->sortByDesc(fn ($row) => $row->competition->start_time)
            ->values();
    }

    public function render()
    {
        $judge = User::findOrFail($this->judgeId);

        return view('livewire.admin.judge-history', [
            'judge' => $judge,
            'rows' => $this->rows($judge),
        ]);
    }
}
