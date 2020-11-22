<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Policies\CompanyPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SupplierPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Employee::class => EmployeePolicy::class,
        Permission::class => PermissionPolicy::class,
        Company::class => CompanyPolicy::class,
        Product::class => ProductPolicy::class,
        ProductCategory::class => ProductCategoryPolicy::class,
        Supplier::class => SupplierPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
