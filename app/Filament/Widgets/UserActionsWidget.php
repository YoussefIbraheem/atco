<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\PlaceSaleOrder as PagesPlaceSaleOrder;
use App\Livewire\PlaceSaleOrder;
use Filament\Widgets\Widget;

class UserActionsWidget extends Widget
{
    protected string $view = 'filament.widgets.user-actions-widget';

    public function placeSaleOrder()
    {
        return $this->redirect(PagesPlaceSaleOrder::getUrl());
    }
}
