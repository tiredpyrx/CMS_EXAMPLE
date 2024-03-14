<?php

namespace App\Livewire;

use Livewire\Component;

class SiblingfieldComponent extends Component
{

    public $fields = [];
    public $field;

    public $col_span = '5';

    public function mount($field)
    {
        $this->field = $field;
        $this->field->handler .= '[]';
        $this->fields = [...$this->fields, $this->field , $this->field];
    }

    public function addField()
    {
        foreach ($this->fields as $field)
            if (!str_ends_with($field->handler, '[]'))  $field->handler .= '[]';
        $this->field->handler .= '[]';
        $this->fields = [...$this->fields, $this->field, $this->field];
    }

    public function removeField($idx)
    {
        array_splice($this->fields, $idx - 1, 2);
    }

    public function render()
    {
        return view('livewire.siblingfield-component');
    }
}
