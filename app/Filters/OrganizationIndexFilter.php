<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrganizationIndexFilter
{
    public static function applyFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');

            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('remarks', 'like', '%'.$keyword.'%');
            });
        }

        if ($request->filled('fiscal_year')) {
            $query->where('fiscal_year', (int) $request->input('fiscal_year'));
        }

        if ($request->filled('fiscal_start_month')) {
            $query->where('fiscal_start_month', (int) $request->input('fiscal_start_month'));
        }

        if ($request->filled('fiscal_end_month')) {
            $query->where('fiscal_end_month', (int) $request->input('fiscal_end_month'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (int) $request->input('is_active'));
        }

        return $query;
    }
}
