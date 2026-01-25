<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tiktok_pesanan', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid")->unique();
            $table->unsignedBigInteger("order_id")->nullable()->index();
            $table->string("order_status", 50)->nullable()->index();
            $table->string("order_sub_status", 100)->nullable()->index();
            $table->string("return_type", 50)->nullable();
            $table->string("normal_or_preorder", 100)->nullable();
            $table->string("sku_id", 100)->nullable();
            $table->string("seller_sku", 150)->nullable();
            $table->string("product_name", 150);
            $table->string("variant", 100);
            $table->unsignedBigInteger("quantity")->default(0);
            $table->unsignedBigInteger("sku_quantity_of_return")->default(0);
            $table->unsignedBigInteger("sku_unit_original")->default(0);
            $table->unsignedBigInteger("sku_subtotal")->default(0);
            $table->unsignedBigInteger("sku_subtotal_before_discount")->default(0);
            $table->unsignedBigInteger("sku_platform_discount")->default(0);
            $table->unsignedBigInteger("sku_seller_discount")->default(0);
            $table->unsignedBigInteger("sku_subtotal_after_discount")->default(0);
            $table->unsignedBigInteger("shipping_fee_after_discount")->default(0);
            $table->unsignedBigInteger("original_shipping_fee")->default(0);
            $table->unsignedBigInteger("shipping_fee_seller_discount")->default(0);
            $table->unsignedBigInteger("shipping_fee_platform_discount")->default(0);
            $table->unsignedBigInteger("payment_platform_discount")->default(0);
            $table->unsignedBigInteger("buyer_service_fee")->default(0);
            $table->unsignedBigInteger("handling_fee")->default(0);
            $table->unsignedBigInteger("shipping_insurance")->default(0);
            $table->unsignedBigInteger("item_insurance")->default(0);
            $table->unsignedBigInteger("order_amount")->default(0);
            $table->unsignedBigInteger("order_refund_amount")->default(0);
            $table->dateTime("created_time")->nullable()->index();
            $table->dateTime("paid_time")->nullable()->index();
            $table->dateTime("rts_time")->nullable();
            $table->dateTime("shipped_time")->nullable()->index();
            $table->dateTime("delivered_time")->nullable();
            $table->dateTime("cancelled_time")->nullable();
            $table->string("cancel_by", 100)->nullable();
            $table->string("cancel_reason", 150)->nullable();
            $table->string("fulfillment_type")->nullable();
            $table->string("warehouse_ame")->nullable();
            $table->string("tracking_id", 150)->nullable()->index();
            $table->string("delivery_option")->nullable();
            $table->string("shipping_provider_name")->nullable()->index();
            $table->string("buyer_username", 150)->nullable()->index();
            $table->string("recipient", 150)->nullable();
            $table->string("phone", 50)->nullable();
            $table->string("zipcode", 50)->nullable();
            $table->string("country", 50)->nullable();
            $table->string("province", 75)->nullable();
            $table->string("regency_and_city", 100)->nullable();
            $table->string("districts", 100)->nullable();
            $table->string("villages", 150)->nullable();
            $table->string("detail Address", 150)->nullable();
            $table->string("additional_address_information", 150)->nullable();
            $table->string("payment_method", 150)->nullable();
            $table->string("weight", 50)->nullable();
            $table->string("product_category", 75)->nullable();
            $table->string("package_id", 75)->nullable();
            $table->string("purchase_channel", 75)->nullable();
            $table->string("seller_note", 150)->nullable();
            $table->string("checked_status", 50)->nullable();
            $table->string("checked_marked_by", 150)->nullable();
            $table->string("tokopedia_invoice_number", 150)->nullable();
            $table->uuid("divisi_id");
            $table->uuid("created_by");
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('divisi_id')->references('id')->on('divisi')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_pesanan');
    }
};
