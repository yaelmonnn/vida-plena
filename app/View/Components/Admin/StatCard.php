<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatCard extends Component
{
    public $icon;
    public $value;
    public $label;
    public $color;

    public function __construct($icon, $value, $label, $color = '')
    {
        $this->icon = $icon;
        $this->value = $value;
        $this->label = $label;
        $this->color = $color;
    }

    public function render()
    {
        return view('components.admin.stat-card');
    }
}
