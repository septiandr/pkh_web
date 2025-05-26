<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputField extends Component
{
    public $type, $name, $label, $value, $required, $old, $existing, $options;

    public function __construct(
        $type = 'text',              // Defaultnya text
        $name,                      // Wajib diisi
        $label = '',                // Bisa kosong
        $value = '',                // Nilai awal
        $required = false,          // Wajib isi atau tidak
        $old = null,                // Old value Laravel
        $existing = '',            // Untuk preview file
        $options = []            
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->required = $required;
        $this->old = $old;
        $this->existing = $existing;
        $this->options = $options;
    }

    public function render()
    {
        return view('components.input-field'); // Menunjuk ke Blade view-nya
    }
}
