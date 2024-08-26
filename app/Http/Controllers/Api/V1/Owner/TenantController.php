<?php

namespace App\Http\Controllers\Api\V1\Owner;

use Exception;
use App\Models\Tenant;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Services\TenantDatabaseService;
use App\Http\Controllers\BaseController;
use App\Jobs\Owner\OwnerNotificationJob;
use Illuminate\Support\Facades\Response;
use Stancl\Tenancy\Database\Models\Domain;
use App\Mail\Tenant\Tenant\TenantDeletedEmail;
use App\Mail\Tenant\Tenant\TenantUpdatedEmail;
use App\Mail\Tenant\Tenant\TenantRegisterEmail;
use App\Mail\Owner\Tenant\OwnerTenantCreatedEmail;
use App\Mail\Owner\Tenant\OwnerTenantDeletedEmail;
use App\Mail\Owner\Tenant\OwnerTenantUpdatedEmail;
use App\Mail\Tenant\Tenant\TenantDomainAddedEmail;
use App\Mail\Tenant\Tenant\TenantChangePasswordEmail;
use App\Mail\Owner\Tenant\OwnerTenantDomainAddedEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Mail\Owner\Tenant\OwnerTenantChangePasswordEmail;
use App\Http\Resources\Api\V1\Owner\Tenant\TenantResource;
use App\Http\Requests\Api\V1\Owner\Tenant\StoreTenantRequest;
use App\Http\Requests\Api\V1\Owner\Tenant\UpdateTenantRequest;

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

            TenantDatabaseService::addTenantId($tenant, $tenant->id);

            // Dispatching owner notification
            $mailable = new OwnerTenantCreatedEmail($tenant);
            OwnerNotificationJob::dispatch($mailable);

            // Dispatching tenant notification
            $password = request()->input('password');
            $mailData = [
                'name' => $tenant->name,
                'email' => $tenant->email,
                'password' => $password,
            ];

            $mailable = new TenantRegisterEmail($mailData);
            SendEmailJob::dispatch($mailable, $tenant->email);

            // Return response
            return Response::success('Tenant has been created', $tenant, 200);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($tenant)
    {
        try {
            // Find the tenant with domains
            $tenant = Tenant::with('domains')->findOrFail($tenant);

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
    public function update(UpdateTenantRequest $request, $tenant)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Find the tenant
            $tenant = Tenant::findOrFail($tenant);

            // Update Tenant
            $tenant->update($validated);

            // Update tenant user's table
            TenantDatabaseService::updateTenantData($tenant, $validated);

            // Dispatching owner notification
            $mailable = new OwnerTenantUpdatedEmail($tenant);
            OwnerNotificationJob::dispatch($mailable);

            // Dipatching tenant notification
            $mailable = new TenantUpdatedEmail($tenant);
            SendEmailJob::dispatch($mailable, $tenant->email);

            // Return response
            return Response::success('Tenant has been updated', new TenantResource($tenant), 200);
        } catch (ModelNotFoundException $e) {
            return Response::error('Tenant not found', 404);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tenantId)
    {
        try {
            // Find the tenant
            $tenant = Tenant::findOrFail($tenantId);

            // Gather domain data before deletion
            $domains = $tenant->domains()->get(['domain'])->pluck('domain')->toArray();

            // Attach domain data to the tenant
            $tenant->deletionData = [
                'tenantData' => $tenant->toArray(),
                'domains' => $domains,
            ];

            // Delete the tenant's domain
            $tenant->domains()->delete();

            // Delete the tenant record
            $tenant->delete();

            // Dispatching owner notification
            $mailable = new OwnerTenantDeletedEmail($tenant->deletionData);
            OwnerNotificationJob::dispatch($mailable);

            // Dispatching tenant notifications
            $mailable = new TenantDeletedEmail($tenant->deletionData);
            SendEmailJob::dispatch($mailable, $tenant->deletionData['tenantData']['email']);

            return Response::message('Tenant has been deleted', 200);
        } catch (ModelNotFoundException $e) {
            return Response::error('Tenant not found', 404);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Change password for the specified resource from storage.
     */
    public function changePassword(Request $request, $tenant)
    {
        try {
            // Validation
            $validated = $request->validate([
                'password' => 'required|string|max:255',
                'confirm_password' => 'required|string|max:255|same:password',
            ]);

            // Unset confirm_password
            unset($validated['confirm_password']);

            // Find tenant
            $tenant = Tenant::findOrFail($tenant);

            // Update Tenant
            $tenant->update($validated);

            // Change password in tenant table
            TenantDatabaseService::updateTenantPassword($tenant, $validated['password']);

            // Dispatching owner notification
            $mailable = new OwnerTenantChangePasswordEmail($tenant);
            OwnerNotificationJob::dispatch($mailable);

            $mailable = new TenantChangePasswordEmail($tenant);
            SendEmailJob::dispatch($mailable, $tenant->email);

            // Return response
            return Response::success('Password has been updated', new TenantResource($tenant), 200);
        } catch (ModelNotFoundException $e) {
            return Response::error('Tenant not found', 404);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }

    /**
     * Add domain for the specified resource from storage.
     */
    public function addDomain(Request $request, $tenant)
    {
        try {
            // Validation
            $validated = $request->validate([
                'domain' => 'required|max:255|unique:domains,domain'
            ]);

            // Find the tenant
            $tenant = Tenant::findOrFail($tenant);

            // Add Domain
            $domain = $tenant->domains()->create([
                'domain' => $validated['domain'] . '.' . config('app.domain'),
            ]);

            // Combine tenant and domain data into single array
            $mailData = [
                'tenant' => $tenant->toArray(),
                'domain' => $domain->toArray()
            ];

            // Dispatching owner notification
            $mailable = new OwnerTenantDomainAddedEmail($mailData);
            OwnerNotificationJob::dispatch($mailable);

            // Dispatching tenant notification
            $mailable = new TenantDomainAddedEmail($mailData);
            SendEmailJob::dispatch($mailable, $tenant->email);

            // Return response
            return Response::success('Domain has been added', new TenantResource($tenant), 200);
        } catch (ModelNotFoundException $e) {
            return Response::error('Tenant not found', 404);
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
