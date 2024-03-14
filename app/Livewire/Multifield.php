<?php

namespace App\Livewire;

use Livewire\Component;

class Multifield extends Component
{

    public $fields = [];
    public $field;

    public $col_span = '12';

    public function mount($field)
    {
        $this->field = $field;
        $this->field->handler .= '[]';
        $this->fields = [...$this->fields, $this->field];
    }

    public function addField()
    {
        if ($this->col_span != '6')
            $this->col_span = '6';
        foreach ($this->fields as $field)
            if (!str_ends_with($field->handler, '[]'))  $field->handler .= '[]';
        $this->field->handler .= '[]';
        $this->fields = [...$this->fields, $this->field];
    }

    public function removeField($idx)
    {
        array_splice($this->fields, $idx, 1);
    }

    public function render()
    {
        return view('livewire.multifield');
    }
}
