<?php

namespace App\Livewire;

use Livewire\Component;

class Multifield extends Component
{

    public $fields = [];
    public $field;
    public $dummyfield;

    public $col_span = '12';

    public function mount($field)
    {
        $this->field = $field;
        $this->dummyfield = $this->field->replicate(
            [
                'value',
                'column',
                'category_id',
                'post_id',
                'placeholder',
                'label',
                'description',
                'suffix',
                'prefix',
                'min_value',
                'max_value',
                'user_id',
                'type',
                'step',
                'as_option',
                'active',
                'deleted_at',
                'created_at'
            ]
        );
        $this->field->handler .= '[]';
        $this->dummyfield->handler .= '[]';
        $this->fields = [...$this->field->fields];
        if (!count($this->fields))
            $this->fields[] = $this->dummyfield;
        else if (count($this->fields) > 1) $this->col_span = '6';
        foreach ($this->fields as $field)
            $field->handler = $this->field->handler;
    }

    public function addField()
    {
        $this->handleReRender();
        foreach ($this->fields as $field)
            $field->handler = $this->field->handler;
        $this->fields = [...$this->fields, $this->dummyfield];
    }

    public function removeField($idx)
    {
        $this->handleReRender();
        foreach ($this->fields as $field)
            $field->handler = $this->field->handler;
        array_splice($this->fields, $idx, 1);
    }

    public function handleReRender()
    {
        if ($this->col_span != '6')
            $this->col_span = '6';
        foreach ($this->fields as $field)
            if (!str_ends_with($field->handler, '[]'))  $field->handler .= '[]';
        $this->field->handler .= '[]';
        $this->dummyfield->handler = $this->field->handler.'[]';
    }

    public function render()
    {
        return view('livewire.multifield');
    }
}
