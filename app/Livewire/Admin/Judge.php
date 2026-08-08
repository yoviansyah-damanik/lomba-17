<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class Judge extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public bool $showModal = false;

    public ?User $editing = null;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $username = '';

    public string $password = '';

    // Modal riwayat penjurian
    public bool $showHistoryModal = false;

    public ?string $viewingHistoryFor = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->editing?->id)],
            'password' => [$this->editing ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }

    public function create(): void
    {
        $this->reset(['editing', 'name', 'username', 'password']);
        $this->showModal = true;
    }

    public function edit(User $judge): void
    {
        $this->editing = $judge;
        $this->name = $judge->name;
        $this->username = $judge->username;
        $this->password = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editing) {
            $this->editing->update([
                'name' => $validated['name'],
                'username' => $validated['username'],
                ...($validated['password'] ? ['password' => Hash::make($validated['password'])] : []),
            ]);
        } else {
            User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => 'judge',
            ]);
        }

        $this->showModal = false;
        $this->reset(['editing', 'name', 'username', 'password']);
    }

    public function delete(User $judge): void
    {
        $judge->delete();
    }

    public function viewHistory(string $judgeId): void
    {
        $this->viewingHistoryFor = $judgeId;
        $this->showHistoryModal = true;
    }

    public function closeHistory(): void
    {
        $this->showHistoryModal = false;
        $this->viewingHistoryFor = null;
    }

    public function render()
    {
        return view('livewire.admin.judge', [
            'judges' => User::where('role', 'judge')
                ->when($this->search, fn ($query) => $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('username', 'like', "%{$this->search}%");
                }))
                ->latest()
                ->paginate(10),
        ]);
    }
}
