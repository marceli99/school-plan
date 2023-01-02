<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Main page';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Welcome to your school plan application!';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Your grades')
                ->route('platform.systems.grades')
                ->icon('docs')
            ->canSee(!Auth::user()->hasAccess('grades')),

            Link::make('Manage grades')
                ->route('platform.systems.groups')
                ->icon('docs')
                ->canSee(Auth::user()->hasAccess('groups')),

            Link::make('Manage users')
                ->route('platform.systems.users')
                ->icon('user')
                ->canSee(Auth::user()->hasAccess('platform.systems.users')),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
