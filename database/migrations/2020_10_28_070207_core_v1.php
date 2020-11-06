<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoreV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('position')->nullable();
            $table->boolean('enabled')->default(0);
            $table->timestamp('last_online_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Password Resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Failed Jobs - Queue
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('users');
            $table->string('settings');
            $table->string('warehouses');
            $table->string('products');
            $table->string('merchandises');
            $table->string('manufacturings');
            $table->timestamps();
            $table->softDeletes();
        });

        // Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('sector')->nullable();
            $table->string('membership_plan')->nullable();
            $table->boolean('enabled')->default(0);
            $table->string('currency')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('permission_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('permission_id');
            $table->index('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('name');
            $table->string('location');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('name');
            $table->json('properties');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_category_id')->nullable()->unsigned();
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('name');
            $table->string('selling_price'); // price per unit of measurement
            $table->string('purchase_price');
            $table->string('unit_of_measurement');
            $table->string('min_on_hand');
            $table->boolean('is_expirable');
            $table->json('properties');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_category_id');
            $table->index('company_id');

            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Product Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->string('name');
            $table->string('original_name');
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        // Merchandise Products
        Schema::create('merchandises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('total_received');
            $table->string('total_on_hand');
            $table->string('total_sold');
            $table->string('total_broken');
            $table->string('total_returns');
            $table->timestamp('expires_on')->nullable();
            $table->timestamp('received_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('warehouse_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Manufacturing Products
        Schema::create('manufacturings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('total_products');
            $table->string('total_in_process');
            $table->string('total_on_hand');
            $table->string('total_sold');
            $table->string('total_broken');
            $table->string('total_returns');
            $table->string('production_status');
            $table->timestamp('started_on')->nullable();
            $table->timestamp('finishes_on')->nullable();
            $table->timestamp('expires_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('warehouse_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Raw Material
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('total_received');
            $table->string('on_hand');
            $table->string('min_on_hand');
            $table->string('unit_of_measurement');
            $table->string('purchase_price');
            $table->json('properties');
            $table->timestamp('expires_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        // Bill of Materials
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable()->unsigned();
            $table->json('materials');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        // MRO Items
        Schema::create('mro_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->nullable()->unsigned();
            $table->bigInteger('warehouse_id')->nullable()->unsigned();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->string('name');
            $table->string('total_received');
            $table->string('on_hand');
            $table->string('min_on_hand');
            $table->string('unit_of_measurement');
            $table->string('purchase_price');
            $table->json('properties');
            $table->timestamp('brought_on')->nullable();
            $table->timestamp('expires_on')->nullable();
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('password_resets');
        Schema::drop('failed_jobs');
        Schema::drop('permissions');
        Schema::drop('companies');
        Schema::drop('employees');
        Schema::drop('warehouses');
        Schema::drop('product_categories');
        Schema::drop('products');
        Schema::drop('product_images');
        Schema::drop('merchandises');
        Schema::drop('manufacturings');
        Schema::drop('raw_materials');
        Schema::drop('bill_of_materials');
        Schema::drop('mro_items');
    }
}
