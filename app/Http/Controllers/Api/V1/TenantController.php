<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\StoreTenantRequest;
use App\Http\Requests\Api\V1\UpdateTenantRequest;
use App\Http\Resources\Api\V1\TenantResource;
use App\Models\Tenant;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Stancl\Tenancy\Database\Models\Domain;

class TenantController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get all tenants with domains
            $tenants = Tenant::with('domains')->paginate(10);

            // Return Tenant Resource
            return Response::success('Tenant List', new TenantResource($tenants));
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenantRequest $request)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Additional validation for the domain field
            $domainExists = Domain::where('domain', $validated['domain'] . '.' . config('app.domain'))->exists();
            if ($domainExists) {
                return Response::error('The domain has already been taken.', 422);
            }

            // Remove confirm_password from the data before creating the tenant
            unset($validated['confirm_password']);

            // Create Tenant
            $tenant = Tenant::create($validated);
            $tenant->domains()->create([
                'domain' => $validated['domain'] . '.' . config('app.domain'),
            ]);

            // Return response
            return Response::success('Tenant has been created', $tenant, 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        try {
            $tenant->load('domains');

            return Response::success('Tenant found', new TenantResource($tenant));
        } catch (ModelNotFoundException $e) {
            return Response::error('Tenant not found', 404);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Update Tenant
            $tenant->update($validated);

            // Return response
            return Response::success('Tenant has been updated', new TenantResource($tenant), 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        try {
            // Delete the tenant's domain
            $tenant->domains()->delete();

            // Delete the tenant record
            $tenant->delete();

            return Response::message('Tenant has been deleted', 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Change password for the specified resource from storage.
     */
    public function changePassword(Request $request, Tenant $tenant)
    {
        try {
            // Validation
            $validated = $request->validate([
                'password' => 'required|string|max:255',
                'confirm_password' => 'required|string|max:255|same:password',
            ]);

            // Unset confirm_password
            unset($validated['confirm_password']);

            // Update Tenant
            $tenant->update($validated);

            // Return response
            return Response::success('Password has been updated', new TenantResource($tenant), 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Add domain for the specified resource from storage.
     */
    public function addDomain(Request $request, Tenant $tenant)
    {
        try {
            // Find the tenant
            $tenant = Tenant::findOrFail($tenant->id);

            // Validation
            $validated = $request->validate([
                'domain' => 'required|max:255|unique:domains,domain'
            ]);

            // Add Domain
            $tenant->domains()->create([
                'domain' => $validated['domain'] . '.' . config('app.domain'),
            ]);

            // Return response
            return Response::success('Domain has been added', new TenantResource($tenant), 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
