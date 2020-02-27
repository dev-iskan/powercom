<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromQuery, WithHeadings
{
    public function headings(): array
    {
        return [
            'Название',
            'Краткое описание',
            'Описание',
            'Цена (сум)',
            'Количество',
            'Бренд',
            'Категории',
            'Активный'
        ];
    }

    public function query()
    {
        return DB::table('products')
            ->select(
                DB::raw('products.name'),
                DB::raw('products.short_description'),
                DB::raw('products.description'),
                DB::raw('products.price'),
                DB::raw('products.quantity'),
                DB::raw('brands.name AS brand_name'),
                DB::raw("string_agg(DISTINCT categories.name, ', ') AS category_name"),
                DB::raw('products.active')
            )
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('category_product', 'category_product.product_id', '=', 'products.id')
            ->leftJoin('categories', 'categories.id', '=', 'category_product.category_id')
            ->orderBy('products.id')
            ->groupBy([
                'products.id',
                'products.name',
                'products.short_description',
                'products.description',
                'products.price',
                'products.quantity',
                'brands.name',
                'products.active'
            ]);
    }
}
