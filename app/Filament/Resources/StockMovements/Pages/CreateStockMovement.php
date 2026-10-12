<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = parent::mutateFormDataBeforeCreate($data);

        $data["user_id"] = auth()->user()->id;

        return $data;
    }
}
