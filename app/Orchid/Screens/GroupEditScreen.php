<?php

namespace App\Orchid\Screens;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GroupEditScreen extends Screen
{
    public Group $group;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Group $group): iterable
    {
        return [
            'group' => $group,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->group->exists ? 'Edit Group' : 'Create Group';
    }

    public function permission(): ?iterable
    {
        return ['groups'];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Once the group is deleted, all of its resources and data will be permanently deleted.'))
                ->method('remove')
                ->canSee($this->group->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::columns([
                Layout::rows([
                    Input::make('group.name')
                        ->type('text')
                        ->max(255)
                        ->required()
                        ->title(__('Name'))
                        ->placeholder(__('Name')),

                    Relation::make('group.teacher_id')
                        ->fromModel(User::class, 'name')
                        ->title(__('Teacher')),

                    Relation::make('group.students.')
                        ->fromModel(User::class, 'name')
                        ->multiple()
                        ->title('Students')
                ])
            ])
        ];
    }

    /**
     * @param Group $group
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Group $group, Request $request): RedirectResponse
    {
        $request->validate([
            'group.name' => [
                'required',
                Rule::unique(Group::class, 'name')->ignore($group->id),
            ],
        ]);

        $group->fill($request->collect('group')->except(['students'])->toArray())
            ->save();

        $group->students()->sync($request->collect('group')['students']);
        Toast::info(__('Group was saved.'));

        return redirect()->route('platform.systems.groups');
    }

    /**
     * @param Group $group
     *
     * @return RedirectResponse
     * @throws \Exception
     *
     */
    public function remove(Group $group)
    {
        $group->delete();

        Toast::info(__('Group was removed'));

        return redirect()->route('platform.systems.groups');
    }
}
