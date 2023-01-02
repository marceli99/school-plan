<?php

namespace App\Orchid\Screens;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GroupListScreen extends Screen
{
    protected string $target = 'groups';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = Auth::user();

        if ($user->hasAccess('groups')) {
            return ['groups' => Group::paginate()];
        }

        return ['groups' => $user->ownedGroups];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Groups';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.systems.groups.create'),
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
            Layout::table('groups', [
                TD::make('id', 'ID')
                    ->width('100'),

                TD::make('name', __('Name'))
                    ->sort()
                    ->cantHide()
                    ->filter(Input::make())
                    ->render(fn(Group $group) => Link::make($group->name)
                        ->route('platform.systems.groups.view', $group->id)),

                TD::make('teacher', 'Teacher name')
                    ->render(fn(Group $group) => $group->teacher->name),

                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(fn(Group $group) => DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('View'))
                                ->route('platform.systems.groups.view', $group->id)
                                ->icon('eye'),

                            Link::make(__('Edit'))
                                ->route('platform.systems.groups.edit', $group->id)
                                ->canSee(Auth::user()->hasAccess('groups'))
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->confirm(__('Once the groups is deleted, all of its resources and data will be permanently deleted.'))
                                ->canSee(Auth::user()->hasAccess('groups'))
                                ->method('remove', [
                                    'id' => $group->id,
                                ]),
                        ])),
            ]),

        ];
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Group::findOrFail($request->get('id'))->delete();

        Toast::info(__('Group was removed'));
    }
}
