<?php

namespace App\Rules;

use App\Helpers\ArabicSlugger;
use App\Models\ProductType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueArabicSlug implements ValidationRule
{


    public function __construct(protected string $context, protected ?Model $record) {}
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slugged = ArabicSlugger::handle($value);
        $exists = ProductType::query()->where("slug", $slugged)->first();

        if ($exists) {
            $errorMessage = "نوع المنتج موجود بالفعل";
            if ($this->context == "create") {
                $fail($errorMessage);
            }

            if ($this->context == "edit") {
                if ($this->record->id != $exists->id) {
                    $fail($errorMessage);
                }
            }
        }
    }
}
