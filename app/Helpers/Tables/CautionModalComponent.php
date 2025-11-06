<?php

namespace App\Helpers\Tables;

use App\Helpers\Tables\Component;

class CautionModalComponent implements Component
{
    public string $cautionMessage;
    public string $action;
    public array $columns;
    public array $rows;
    public string $lastWarningMessage;
    public string $confirmButton;
    public string $cancelButton;
    public bool $isForm;
    public string $dataInputName;

    public function __construct(
        string $cautionMessage,
        string $action,
        array $columns,
        array $rows,
        string $lastWarningMessage,
        string $confirmButton,
        string $cancelButton,
        bool $isForm = false,
        string $dataInputName = ''
    ) {
        $this->cautionMessage = $cautionMessage;
        $this->action = $action;
        $this->columns = $columns;
        $this->rows = $rows;
        $this->lastWarningMessage = $lastWarningMessage;
        $this->confirmButton = $confirmButton;
        $this->cancelButton = $cancelButton;
        $this->isForm = $isForm;
        $this->dataInputName = $dataInputName;
    }

    public static function new(): ModalComponentBuilder
    {
        return new ModalComponentBuilder();
    }

    public function render()
    {
        return view('tablesv2.modals.modal-01', [
            'caution_message' => $this->cautionMessage,
            'action' => $this->action,
            'columns' => $this->columns,
            'rows' => $this->rows,
            'last_warning_message' => $this->lastWarningMessage,
            'confirm_button' => $this->confirmButton,
            'cancel_button' => $this->cancelButton,
            'is_form' => $this->isForm,
            'data_input_name' => $this->dataInputName,
        ]);
    }
}

class ModalComponentBuilder
{
    private string $cautionMessage = '';
    private string $action = '';
    private array $columns = [];
    private array $rows = [];
    private string $lastWarningMessage = '';
    private string $confirmButton = '';
    private string $cancelButton = '';
    private bool $isForm = false;
    private string $dataInputName = '';

    public function cautionMessage(string $msg): self { $this->cautionMessage = $msg; return $this; }
    public function action(string $action): self { $this->action = $action; return $this; }
    public function columns(array $columns): self { $this->columns = $columns; return $this; }
    public function rows(array $rows): self { $this->rows = $rows; return $this; }
    public function lastWarningMessage(string $msg): self { $this->lastWarningMessage = $msg; return $this; }
    public function confirmButton(string $txt): self { $this->confirmButton = $txt; return $this; }
    public function cancelButton(string $txt): self { $this->cancelButton = $txt; return $this; }
    public function isForm(bool $isForm): self { $this->isForm = $isForm; return $this; }
    public function dataInputName(string $name): self { $this->dataInputName = $name; return $this; }

    public function build(): CautionModalComponent
    {
        return new CautionModalComponent(
            $this->cautionMessage,
            $this->action,
            $this->columns,
            $this->rows,
            $this->lastWarningMessage,
            $this->confirmButton,
            $this->cancelButton,
            $this->isForm,
            $this->dataInputName
        );
    }
}
