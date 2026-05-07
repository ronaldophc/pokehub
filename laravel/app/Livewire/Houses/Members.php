<?php

namespace App\Livewire\Houses;

use App\Models\House;
use App\Services\HouseMemberService;
use App\Services\HouseService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Members extends Component
{
    public House $house;

    public bool $editingName = false;
    public string $name = '';

    public function mount(House $house): void
    {
        $this->authorize('manageMembers', $house);
    }

    public function startRename(): void
    {
        $this->name = $this->house->name;
        $this->editingName = true;
    }

    public function cancelRename(): void
    {
        $this->editingName = false;
        $this->name = '';
        $this->resetValidation('name');
    }

    public function rename(): void
    {
        $this->authorize('update', $this->house);

        $this->validate([
            'name' => ['required', 'string', 'min:3', 'max:60', Rule::unique('houses', 'name')->ignore($this->house->id)],
        ]);

        $this->house->update([
            'name' => $this->name,
            'slug' => House::generateSlug($this->name, $this->house->id),
        ]);

        $this->editingName = false;
        $this->dispatch('notify', message: __('House updated.'), type: 'success');
        $this->redirect(route('houses.members', $this->house), navigate: true);
    }

    public function promoteToAdmin(int $userId): void
    {
        if (auth()->id() !== $this->house->owner_id) return;

        app(HouseMemberService::class)->promoteToAdmin($this->house, $userId);
        $this->dispatch('notify', message: __('Member promoted to admin.'), type: 'success');
    }

    public function demoteToMember(int $userId): void
    {
        if (auth()->id() !== $this->house->owner_id) return;

        app(HouseMemberService::class)->demoteToMember($this->house, $userId);
        $this->dispatch('notify', message: __('Admin demoted to member.'), type: 'success');
    }

    public function transferOwnership(int $userId): void
    {
        $this->authorize('delete', $this->house);

        if ($userId === $this->house->owner_id) return;

        $exists = $this->house->houseMemberships()->where('user_id', $userId)->exists();
        if (! $exists) return;

        app(HouseMemberService::class)->transferOwnership($this->house, $userId);
        $this->dispatch('notify', message: __('Ownership transferred.'), type: 'success');
    }

    public function removeMember(int $userId): void
    {
        if ($userId === $this->house->owner_id) return;

        app(HouseMemberService::class)->remove($this->house, $userId);
        $this->dispatch('notify', message: __('Member removed.'), type: 'success');
    }

    public function regenerateCode(): void
    {
        $this->house->update(['invite_code' => House::generateInviteCode()]);
        $this->dispatch('notify', message: __('Code regenerated successfully.'), type: 'success');
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->house);

        app(HouseService::class)->delete($this->house);

        session()->flash('toast', ['message' => __('House deleted.'), 'type' => 'success']);
        $this->redirect(route('houses.index'), navigate: true);
    }

    public function render()
    {
        $members = $this->house->houseMemberships()->with('user')->get();

        return view('livewire.houses.members', compact('members'));
    }
}
