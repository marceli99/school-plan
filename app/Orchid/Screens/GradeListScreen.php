<?php

namespace App\Orchid\Screens;

use App\Models\Grade;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class GradeListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'groups' => Auth::user()->studentGroups,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'My Grades';
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
            Layout::table('groups', [
                TD::make('name', __('Group Name')),
                TD::make(__('Grades'))
                    ->align(TD::ALIGN_CENTER)
                    ->render(fn(Group $group) => \Orchid\Screen\Fields\Group::make(
                        collect(Grade::ownedBy(Auth::user())->inGroup($group)->get())->map(function ($grade) {
                            return Link::make($grade->value)->route('platform.systems.grades.view', $grade->id);
                        })->toArray(),
                    )),
            ])
        ];
    }
}
