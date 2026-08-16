<?php

namespace App\Livewire\Evaluation;

use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CriterionForm extends Component
{
    public Registration $registration;

    public string $competitionId;

    /** @var array<string, int|null> */
    public array $scores = [];

    /** @var array<string, string> */
    public array $notes = [];

    public bool $showConfirm = false;

    public function mount(Registration $registration, string $competitionId): void
    {
        $this->registration = $registration;
        $this->competitionId = $competitionId;

        /** @var \App\Models\User $judge */
        $judge = Auth::user();

        $existing = Evaluation::where('user_id', $judge->id)
            ->where('registration_id', $registration->id)
            ->get()
            ->keyBy('criterion_id');

        foreach ($this->activeCriteria() as $criterion) {
            $this->scores[$criterion->id] = $existing[$criterion->id]->score ?? null;
            $this->notes[$criterion->id] = $existing[$criterion->id]->notes ?? '';
        }
    }

    /**
     * Kriteria milik juri yang login, khusus untuk jenjang sekolah peserta ini,
     * pada lomba yang dipilih dan sedang berlangsung.
     *
     * @return \Illuminate\Support\Collection<int, Criterion>
     */
    public function activeCriteria()
    {
        /** @var \App\Models\User $judge */
        $judge = Auth::user();

        return $judge->criteria()
            ->wherePivot('school_type', $this->registration->school_type)
            ->where('competition_id', $this->competitionId)
            ->with('competition')
            ->get()
            ->filter(fn (Criterion $criterion) => $criterion->competition->isActive())
            ->sortBy('name')
            ->values();
    }

    /** @return array<string, array<string>> */
    protected function rules(): array
    {
        $rules = [];

        foreach ($this->activeCriteria()->pluck('id') as $id) {
            $rules["scores.{$id}"] = ['required', 'integer', 'min:'.Criterion::MIN_SCORE, 'max:'.Criterion::MAX_SCORE];
            $rules["notes.{$id}"] = ['nullable', 'string'];
        }

        return $rules;
    }

    /** @return array<string, string> */
    protected function validationAttributes(): array
    {
        $attributes = [];

        foreach ($this->activeCriteria() as $criterion) {
            $attributes["scores.{$criterion->id}"] = __('nilai ":name"', ['name' => $criterion->name]);
            $attributes["notes.{$criterion->id}"] = __('catatan ":name"', ['name' => $criterion->name]);
        }

        return $attributes;
    }

    public function incrementScore(string $criterionId): void
    {
        $current = $this->scores[$criterionId] ?? (Criterion::MIN_SCORE - 1);
        $this->scores[$criterionId] = min(Criterion::MAX_SCORE, ((int) $current) + 1);
    }

    public function decrementScore(string $criterionId): void
    {
        $current = $this->scores[$criterionId] ?? (Criterion::MIN_SCORE + 1);
        $this->scores[$criterionId] = max(Criterion::MIN_SCORE, ((int) $current) - 1);
    }

    public function review(): void
    {
        $this->validate($this->rules());
        $this->showConfirm = true;
    }

    public function cancelReview(): void
    {
        $this->showConfirm = false;
    }

    public function save(): void
    {
        $this->validate($this->rules());

        /** @var \App\Models\User $judge */
        $judge = Auth::user();

        foreach ($this->activeCriteria()->pluck('id') as $id) {
            Evaluation::updateOrCreate(
                [
                    'user_id' => $judge->id,
                    'registration_id' => $this->registration->id,
                    'criterion_id' => $id,
                ],
                [
                    'score' => $this->scores[$id],
                    'notes' => $this->notes[$id] ?: null,
                ]
            );
        }

        $this->showConfirm = false;
        $this->dispatch('evaluation-saved');
    }

    public function render()
    {
        return view('livewire.evaluation.criterion-form', [
            'criteria' => $this->activeCriteria(),
        ]);
    }
}
