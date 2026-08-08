<?php

namespace App\Livewire\Admin;

use App\Models\Competition as CompetitionModel;
use App\Models\Criterion;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Competition extends Component
{
    use WithPagination;

    public const MAX_DISPLAYED_JUDGES = 3;

    public const STATUSES = ['upcoming', 'active', 'ended'];

    #[Url]
    public string $search = '';

    #[Url]
    public string $filterStatus = '';

    // Form lomba
    public bool $showModal = false;

    public ?CompetitionModel $editing = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|date_format:Y-m-d')]
    public string $start_date = '';

    #[Validate('required|in:00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')]
    public string $start_hour = '00';

    #[Validate('required|digits:2')]
    public string $start_minute = '00';

    #[Validate('required|date_format:Y-m-d')]
    public string $end_date = '';

    #[Validate('required|in:00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23')]
    public string $end_hour = '00';

    #[Validate('required|digits:2')]
    public string $end_minute = '00';

    // Panel kriteria per lomba
    public ?string $expandedId = null;

    // Modal peserta per lomba
    public bool $showParticipantsModal = false;

    public ?string $managingParticipantsFor = null;

    // Form kriteria
    public bool $showCriterionModal = false;

    public ?Criterion $editingCriterion = null;

    #[Validate('required|string|max:255')]
    public string $criterion_name = '';

    public string $criterion_description = '';

    /** @var array<string> */
    public array $criterionSchoolTypes = ['SD', 'SMP', 'SMA'];

    /** @var array<string, array<string>> */
    public array $judgeIdsBySchoolType = ['SD' => [], 'SMP' => [], 'SMA' => []];

    // Modal daftar juri lengkap
    public bool $showJudgesModal = false;

    public string $judgesModalTitle = '';

    /** @var array<int, string> */
    public array $judgesModalNames = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    protected function resetScheduleFields(): void
    {
        $this->reset(['editing', 'name', 'start_date', 'start_hour', 'start_minute', 'end_date', 'end_hour', 'end_minute']);
        $this->start_hour = $this->end_hour = '00';
        $this->start_minute = $this->end_minute = '00';
    }

    public function create(): void
    {
        $this->resetScheduleFields();
        $this->showModal = true;
    }

    public function edit(CompetitionModel $competition): void
    {
        $this->editing = $competition;
        $this->name = $competition->name;
        $this->start_date = $competition->start_time->format('Y-m-d');
        $this->start_hour = $competition->start_time->format('H');
        $this->start_minute = $competition->start_time->format('i');
        $this->end_date = $competition->end_time->format('Y-m-d');
        $this->end_hour = $competition->end_time->format('H');
        $this->end_minute = $competition->end_time->format('i');
        $this->showModal = true;
    }

    public function save(): void
    {
        $hourRule = 'required|in:00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23';

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'start_hour' => $hourRule,
            'start_minute' => ['required', 'digits:2'],
            'end_date' => ['required', 'date_format:Y-m-d'],
            'end_hour' => $hourRule,
            'end_minute' => ['required', 'digits:2'],
        ]);

        $startTime = Carbon::createFromFormat('Y-m-d H:i', "{$validated['start_date']} {$validated['start_hour']}:{$validated['start_minute']}");
        $endTime = Carbon::createFromFormat('Y-m-d H:i', "{$validated['end_date']} {$validated['end_hour']}:{$validated['end_minute']}");

        if ($endTime->lessThanOrEqualTo($startTime)) {
            throw ValidationException::withMessages([
                'end_date' => __('Waktu selesai harus setelah waktu mulai.'),
            ]);
        }

        $payload = ['name' => $validated['name'], 'start_time' => $startTime, 'end_time' => $endTime];

        if ($this->editing) {
            $this->editing->update($payload);
        } else {
            CompetitionModel::create($payload);
        }

        $this->showModal = false;
        $this->resetScheduleFields();
    }

    public function delete(CompetitionModel $competition): void
    {
        $competition->delete();
    }

    public function toggleRankOpen(CompetitionModel $competition): void
    {
        $competition->update(['rank_open' => ! $competition->rank_open]);
    }

    public function toggleHideIdentity(CompetitionModel $competition): void
    {
        $competition->update(['hide_identity' => ! $competition->hide_identity]);
    }

    public function toggleCriteria(string $competitionId): void
    {
        $this->expandedId = $this->expandedId === $competitionId ? null : $competitionId;
    }

    public function manageParticipants(string $competitionId): void
    {
        $this->managingParticipantsFor = $competitionId;
        $this->showParticipantsModal = true;
    }

    public function closeParticipants(): void
    {
        $this->showParticipantsModal = false;
        $this->managingParticipantsFor = null;
    }

    public function createCriterion(): void
    {
        $this->reset(['editingCriterion', 'criterion_name', 'criterion_description']);
        $this->criterionSchoolTypes = ['SD', 'SMP', 'SMA'];
        $this->judgeIdsBySchoolType = ['SD' => [], 'SMP' => [], 'SMA' => []];
        $this->showCriterionModal = true;
    }

    public function editCriterion(Criterion $criterion): void
    {
        $this->editingCriterion = $criterion;
        $this->criterion_name = $criterion->name;
        $this->criterion_description = (string) $criterion->description;
        $this->criterionSchoolTypes = $criterion->school_types;

        $this->judgeIdsBySchoolType = ['SD' => [], 'SMP' => [], 'SMA' => []];
        foreach ($criterion->judges as $judge) {
            $this->judgeIdsBySchoolType[$judge->pivot->school_type][] = $judge->id;
        }

        $this->showCriterionModal = true;
    }

    public function saveCriterion(): void
    {
        $validated = $this->validate([
            'criterion_name' => ['required', 'string', 'max:255'],
            'criterion_description' => ['nullable', 'string'],
            'criterionSchoolTypes' => ['required', 'array', 'min:1'],
            'criterionSchoolTypes.*' => ['in:SD,SMP,SMA'],
        ]);

        foreach ($validated['criterionSchoolTypes'] as $type) {
            if (empty($this->judgeIdsBySchoolType[$type])) {
                $this->addError('judgeIdsBySchoolType.'.$type, __('Pilih minimal satu juri untuk jenjang :type.', ['type' => $type]));
            }
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $criterion = $this->editingCriterion ?? new Criterion(['competition_id' => $this->expandedId]);
        $criterion->name = $validated['criterion_name'];
        $criterion->description = $validated['criterion_description'];
        $criterion->school_types = $validated['criterionSchoolTypes'];
        $criterion->save();

        $criterion->judges()->newPivotStatement()->where('criterion_id', $criterion->id)->delete();

        $rows = [];
        foreach ($validated['criterionSchoolTypes'] as $type) {
            foreach ($this->judgeIdsBySchoolType[$type] as $judgeId) {
                $rows[] = [
                    'criterion_id' => $criterion->id,
                    'user_id' => $judgeId,
                    'school_type' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($rows)) {
            $criterion->judges()->newPivotStatement()->insert($rows);
        }

        $this->showCriterionModal = false;
        $this->reset(['editingCriterion', 'criterion_name', 'criterion_description']);
        $this->criterionSchoolTypes = ['SD', 'SMP', 'SMA'];
        $this->judgeIdsBySchoolType = ['SD' => [], 'SMP' => [], 'SMA' => []];
    }

    public function deleteCriterion(Criterion $criterion): void
    {
        $criterion->delete();
    }

    public function viewJudges(Criterion $criterion): void
    {
        $this->judgesModalTitle = $criterion->name;
        $this->judgesModalNames = $criterion->judges()->orderBy('school_type')->orderBy('name')->get()
            ->map(fn (User $judge) => "{$judge->name} ({$judge->pivot->school_type})")
            ->all();
        $this->showJudgesModal = true;
    }

    public function render()
    {
        return view('livewire.admin.competition', [
            'competitions' => CompetitionModel::withCount(['criteria', 'registrations'])
                ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
                ->when($this->filterStatus, fn ($query) => match ($this->filterStatus) {
                    'upcoming' => $query->where('start_time', '>', now()),
                    'active' => $query->where('start_time', '<=', now())->where('end_time', '>=', now()),
                    'ended' => $query->where('end_time', '<', now()),
                    default => $query,
                })
                ->latest('start_time')
                ->paginate(10),
            'expandedCriteria' => $this->expandedId
                ? Criterion::where('competition_id', $this->expandedId)->with(['judges' => fn ($query) => $query->orderBy('name')])->orderBy('name')->get()
                : collect(),
            'judges' => User::where('role', 'judge')->orderBy('name')->get(),
        ]);
    }
}
