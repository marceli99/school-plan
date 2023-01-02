<?php

namespace App\Orchid\Screens;

use App\Models\Grade;
use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GradeEditScreen extends Screen
{
    public Grade $grade;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Grade $grade): iterable
    {
        return [
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
        return $this->grade->exists ? 'Edit Grade' : 'Add new grade';
    }

    public function permission(): ?iterable
    {
        return ['grades'];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
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
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     * @throws BindingResolutionException
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
                        ->value($this->grade->group),

                    Relation::make('grade.student_id')
                        ->fromModel(User::class, 'name')
                        ->applyScope('InGroup', $this->grade->group->id)
                        ->title(__('Student'))
                        ->value($this->grade->student),

                    Input::make('grade.teacher_id')
                        ->type('hidden')
                        ->value(Auth::user()->id),
                ])
            ]),
        ];
    }

    public function save(Grade $grade, Request $request)
    {
        $request->validate([
            'grade.value' => ['required'],
            'grade.weight' => ['required'],
        ]);

        $grade->fill($request->collect('grade')->toArray())
            ->save();


        Toast::info(__('Grade was saved.'));

        return redirect()->route('platform.systems.groups.view', $grade->group->id);
    }

    public function remove(Grade $grade)
    {
        $grade->delete();

        Alert::info('You have successfully deleted the grade.');

        return redirect()->route('platform.systems.groups', [$grade->group->id]);
    }

}
