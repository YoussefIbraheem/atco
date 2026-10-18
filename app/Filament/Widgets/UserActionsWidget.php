<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\PlaceSaleOrder;
use App\Filament\Pages\RestockProducts;
use Filament\Widgets\Widget;

class UserActionsWidget extends Widget
{
    protected string $view = 'filament.widgets.user-actions-widget';

    public function restockProducts():void
    {
        $this->redirect(RestockProducts::getUrl());
    }

        public function placeSaleOrder():void
    {
        $this->redirect(PlaceSaleOrder::getUrl());
    }
}
