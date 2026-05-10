<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app-sidebar')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard');
    }
}
