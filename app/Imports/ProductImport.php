<?php

namespace App\Imports;

use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow
{
    // nazvanie
    // kratkoe_opisanie
    // opisanie
    // tsena_sum
    // kolichestvo
    // brend
    // kategorii
    // aktivnyy
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $validator = Validator::make($row->toArray(), [
                'nazvanie' => 'required|string|max:255',
                'kratkoe_opisanie' => 'nullable',
                'opisanie' => 'required',
                'tsena_sum' => 'required|integer',
                'kolichestvo' => 'required|integer',
                'brend' => 'nullable',
                'kategorii' => 'nullable',
                'aktivnyy' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                continue;
            }

            DB::transaction(function () use ($row) {
                $category_ids = [];
                if ($row['kategorii']) {
                    $categories = explode(', ', $row['kategorii']);
                    foreach ($categories as $category_name) {
                        $category = Category::firstOrCreate(['name' => $category_name]);
                        $category_ids[] = $category->id;
                    }
                }

                $product = Product::firstOrNew(['name' => $row['nazvanie']]);
                $product->short_description = $row['kratkoe_opisanie'];
                $product->description = $row['opisanie'];
                $product->price = $row['tsena_sum'];
                $product->quantity = $row['kolichestvo'];
                $product->active = $row['aktivnyy'];

                if ($brand_name = $row['brend']) {
                    $brand = Brand::firstOrCreate(['name' => $brand_name]);
                    $product->brand()->associate($brand);
                }

                $product->save();

                $product->categories()->sync($category_ids);
            });
        }
    }
}
