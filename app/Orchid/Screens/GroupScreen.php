<?php

namespace App\Orchid\Screens;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class GroupScreen extends Screen
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
            'students' => $group->students,
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
        return 'Group';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('students', [
                TD::make('name', __('Name')),

                TD::make(__('Grades'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn(User $user) => \Orchid\Screen\Fields\Group::make(
                        collect($user->grades)->map(function ($grade) {
                            return Link::make($grade->value)->route('platform.systems.grades.edit', $grade->id);
                        })->toArray(),
                    )),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->canSee(Auth::user()->hasAccess('grades'))
                    ->width('100px')
                    ->render(fn(User $user) => DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Add grade'))
                                ->route('platform.systems.grades.create', [$this->group->id, $user->id])
                                ->icon('pencil'),
                        ])),
            ]),
        ];
    }
}
