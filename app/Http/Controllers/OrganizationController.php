<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Filters\OrganizationIndexFilter;
use App\Http\Requests\OrganizationIndexRequest;
use App\Http\Requests\OrganizationSettingStoreRequest;
use App\Http\Requests\OrganizationSettingUpdateRequest;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class, 'organization');
    }

    public function index(OrganizationIndexRequest $request): View
    {
        $page_title = 'Organizations';

        $query = Organization::query();

        $organizations = OrganizationIndexFilter::applyFilters($query, $request)
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        return view('organizations.index', compact(
            'page_title',
            'organizations'
        ));
    }

    public function create(): View
    {
        $page_title = 'Create Organization';

        return view('organizations.create', compact('page_title'))
            ->with([
                'statuses' => StatusEnum::options(),
            ]);
    }

    public function store(OrganizationSettingStoreRequest $request): RedirectResponse
    {
        Organization::create($request->validated());

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    public function edit(Organization $organization): View
    {
        $page_title = 'Edit Organization';

        return view('organizations.edit', compact(
            'organization',
            'page_title'
        ));
    }

    public function update(OrganizationSettingUpdateRequest $request, Organization $organization): RedirectResponse
    {
        $organization->update($request->validated());

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization saved successfully.');
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}
