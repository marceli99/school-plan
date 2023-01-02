<?php

namespace App\Orchid\Screens;

use App\Models\Grade;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GradeCreateScreen extends Screen
{
    public Group $group;
    public User $user;
    public Grade $grade;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Group $group, User $user, Grade $grade): iterable
    {
        return [
            'group' => $group,
            'user' => $user,
            'grade' => $grade,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Add new grade for ' . $this->user->name;
    }

    public function permission(): ?iterable
    {
        return ['grades'];
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
                ->confirm(__('Are you sure you want to remove this grade?'))
                ->method('remove')
                ->canSee($this->grade->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('create'),
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
                    Input::make('grade.value')
                        ->type('text')
                        ->max(2)
                        ->required()
                        ->title(__('Grade')),

                    Input::make('grade.weight')
                        ->type('number')
                        ->required()
                        ->title(__('Weight')),

                    Relation::make('grade.group_id')
                        ->fromModel(Group::class, 'name')
                        ->applyScope('OwnedGroup', Auth::user()->id)
                        ->title(__('Group'))
                        ->value($this->group),

                    Relation::make('grade.student_id')
                        ->fromModel(User::class, 'name')
                        ->applyScope('InGroup', $this->group->id)
                        ->title(__('Student'))
                        ->value($this->user),

                    Input::make('grade.teacher_id')
                        ->type('hidden')
                        ->value(Auth::user()->id),
                ])
            ]),
        ];
    }

    public function create(Group $group, User $user, Request $request)
    {
        $grade = new Grade();

        $request->validate([
            'grade.value' => [
                'required',
                Rule::in(['1', '1+', '2', '2-', '2+', '3', '3-', '3+', '4', '4-', '4+', '5', '5-', '5+', '6', '6-'])
            ],
            'grade.weight' => ['required', Rule::in(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'])],
            'grade.student_id' => ['required'],
            'grade.teacher_id' => ['required', Rule::in([Auth::user()->id])],
            'grade.group_id' => ['required'],
        ]);

        $grade->fill($request->collect('grade')->toArray())
            ->save();

        Toast::info(__('Grade was saved.'));

        return redirect()->route('platform.systems.groups.view', $grade->group_id);
    }

    public function remove(Grade $grade)
    {
        $grade->delete();

        Alert::info('You have successfully deleted the grade.');

        return redirect()->route('platform.systems.groups', [$grade->group_id]);
    }

}
