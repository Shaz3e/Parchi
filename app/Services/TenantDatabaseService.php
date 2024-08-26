<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class TenantDatabaseService
{
    /**
     * connectToTenantDatabase
     *
     * @param  mixed $tenant
     * @return void
     */
    public static function connectToTenantDatabase($tenant)
    {

        // Connect tenant's db
        $dbName = $tenant->tenancy_db_name;

        // Define the connection details dynamically
        config([
            'database.connections.tenant' => [
                'driver' => config('database.connections.mysql.driver'),
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => $dbName,
                'username' => config('database.connections.mysql.username'),
                'password' => config('database.connections.mysql.password'),
            ],
        ]);

        // Switch the connection to the tenant's database
        DB::setDefaultConnection('tenant'); // Use the connection name 'tenant'
    }

    /**
     * resetToDefaultDatabase
     *
     * @return void
     */
    public static function resetToDefaultDatabase()
    {
        DB::setDefaultConnection(config('database.default'));
    }

    /**
     * addTenantId
     *
     * @param  mixed $tenant
     * @param  mixed $id
     * @return void
     */
    public static function addTenantId($tenant, $id)
    {
        self::connectToTenantDatabase($tenant);

        $user = User::where('email', $tenant->email)->first();
        $user->tenant_id = $id;
        $user->save();

        self::resetToDefaultDatabase();
    }

    /**
     * updateTenantUser
     *
     * @param  mixed $tenant
     * @param  mixed $data
     * @return void
     */
    public static function updateTenantPassword($tenant, $password)
    {
        self::connectToTenantDatabase($tenant);

        // Update password
        $user = User::where('tenant_id', $tenant->id)->first();
        $user->password = bcrypt($password);
        $user->save();

        self::resetToDefaultDatabase();
    }

    /**
     * updateTenantData
     *
     * @param  mixed $tenant
     * @param  mixed $data
     * @return void
     */
    public static function updateTenantData($tenant, $data)
    {
        self::connectToTenantDatabase($tenant);

        $user = User::where('tenant_id', $tenant->id)->first();
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        self::resetToDefaultDatabase();
    }
}
