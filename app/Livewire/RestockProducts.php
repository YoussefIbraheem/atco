<?php

namespace App\Livewire;

use App\Enums\PartyTypeEnum;
use App\Models\Party;
use App\Models\Product;
use App\Models\Store;
use App\Services\DTOs\StockEntry;
use App\Services\StockHandler;
use App\Services\StockLedger;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class RestockProducts extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use RestrictsFileUploadsToSchemaComponents;

    public function restockProducts(): Action
    {
        return Action::make("restockProducts")
            ->label(__("Restock Products"))
            ->schema([
                Select::make("product_id")
                    ->label(__("Product"))
                    ->required()
                    ->preload()
                    ->options(Product::query()->get()->pluck("name", "id")->toArray()),
                Select::make("party_id")
                    ->label(__("Provider"))
                    ->required()
                    ->preload()
                    ->options(Party::query()->where("type",PartyTypeEnum::PRODVIDER)->get()->pluck("name", "id")->toArray()),
                Repeater::make("stockDistribution")
                    ->label(__("Stock Distribution"))
                    ->schema([
                        Select::make("store_id")
                            ->label(__("Store"))
                            ->required()
                            ->preload()
                            ->options(Store::query()->get()->pluck("name", "id")->toArray())
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        TextInput::make("quantity")
                            ->label(__("Quantity"))
                            ->required()
                            ->numeric()
                            ->minValue(1)

                    ])

            ])->action(function ($data) {
                $ledger = new StockLedger();
                $userId = auth()->user()->id;
                (new StockHandler($ledger))->processPurchase(
                    $data["product_id"],
                    $data["party_id"],
                    $userId,
                    $data["stockDistribution"]
                );
            })->successNotification(
                Notification::make()
                    ->success()
                    ->title(__("Products Stocked"))
                    ->body(__("Products Stocked successfully"))
            )
        ;
    }

    public function render()
    {
        return view('livewire.restock-products');
    }
}
