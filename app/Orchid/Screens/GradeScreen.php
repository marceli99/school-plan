<?php

namespace App\Orchid\Screens;

use App\Models\Grade;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class GradeScreen extends Screen
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
            'grade' => $grade
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Grade preview';
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
            Layout::columns([
                Layout::rows([
                    Label::make('value')
                        ->title('Grade')
                        ->value($this->grade->value),

                    Label::make('weight')
                        ->title('Weight')
                        ->value($this->grade->weight),

                    Label::make('graded_by')
                        ->title('Graded by')
                        ->value($this->grade->teacher->name),

                    Label::make('subject')
                        ->title('Subject')
                        ->value($this->grade->group->name),

                    Label::make('graded_at')
                        ->title('Graded at')
                        ->value($this->grade->created_at),
                ])
            ])
        ];
    }
}
