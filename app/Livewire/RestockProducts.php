<?php

namespace App\Livewire;

use App\Models\Party;
use App\Models\Product;
use App\Models\Store;
use App\Services\StockHandler;
use App\Services\StockLedger;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RestockProducts extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make("product_id")
                ->label(__("Product"))
                ->required()
                ->preload()
                ->options(Product::query()->get()->pluck("name", "id")->toArray()),
            Select::make("party_id")
                ->label(__("Provider"))
                ->required()
                ->preload()
                ->options(Party::query()->get()->pluck("name", "id")->toArray()),
            Repeater::make("stockDistribution")
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

        ])->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $ledger = new StockLedger();
        $userId = auth()->user()->id;
        (new StockHandler($ledger))->restock(
            $data["product_id"],
            $data["party_id"],
            $userId,
            $data["stockDistribution"]
        );
    }

    public function render(): View
    {
        return view('livewire.restock-products');
    }
}
