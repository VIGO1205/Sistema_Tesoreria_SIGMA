<?php

namespace App\Helpers;

use App\Helpers\Tables\Component;

class CRUDTablePage
{
    public ?Component $sidebar;
    public ?Component $topbar;
    public ?Component $header;
    public ?Component $content;
    public ?Component $footer;
    public string $title = "Página por defecto";
    public array $modals = [];

    public function __construct(
        ?Component $sidebar = null,
        ?Component $topbar = null,
        ?Component $header = null,
        ?Component $content = null,
        ?Component $footer = null,
        string $title = "Página por defecto",
        array $modals = []
    ) {
        $this->sidebar = $sidebar;
        $this->topbar = $topbar;
        $this->header = $header;
        $this->content = $content;
        $this->footer = $footer;
        $this->title = $title;
        $this->modals = $modals;
    }

    public function render(){
        return view('tablesv2.base', ['page' => $this]);
    }

    public static function new(){
        return new CRUDTablePageBuilder();
    }
}

class CRUDTablePageBuilder
{
    public ?Component $sidebar = null;
    public ?Component $topbar = null;
    public ?Component $header = null;
    public ?Component $content = null;
    public ?Component $footer = null;
    public string $title = "Página por defecto";
    public array $modals = [];

    public function sidebar(?Component $sidebar): self
    {
        $this->sidebar = $sidebar;
        return $this;
    }

    public function topbar(?Component $topbar): self
    {
        $this->topbar = $topbar;
        return $this;
    }

    public function header(?Component $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function content(?Component $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function footer(?Component $footer): self
    {
        $this->footer = $footer;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function modals(array $modals): self
    {
        $this->modals = $modals;
        return $this;
    }

    public function build(): CRUDTablePage
    {
        return new CRUDTablePage(
            $this->sidebar,
            $this->topbar,
            $this->header,
            $this->content,
            $this->footer,
            $this->title,
            $this->modals
        );
    }

    public function render(){
        return $this->build()->render();
    }
}