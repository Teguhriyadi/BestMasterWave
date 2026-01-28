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
        Schema::table('tiktok_pesanan', function (Blueprint $table) {

            $table->decimal("sku_quantity_of_return", 20, 2)->default(0)->change();
            $table->decimal("sku_unit_original", 20, 2)->default(0)->change();
            $table->decimal("sku_subtotal_before_discount", 20, 2)->default(0)->change();
            $table->decimal("sku_platform_discount", 20, 2)->default(0)->change();
            $table->decimal("sku_seller_discount", 20, 2)->default(0)->change();
            $table->decimal("sku_subtotal_after_discount", 20, 2)->default(0)->change();

            $table->decimal("shipping_fee_after_discount", 20, 2)->default(0)->change();
            $table->decimal("original_shipping_fee", 20, 2)->default(0)->change();
            $table->decimal("shipping_fee_seller_discount", 20, 2)->default(0)->change();
            $table->decimal("shipping_fee_platform_discount", 20, 2)->default(0)->change();

            $table->decimal("payment_platform_discount", 20, 2)->default(0)->change();
            $table->decimal("buyer_service_fee", 20, 2)->default(0)->change();
            $table->decimal("handling_fee", 20, 2)->default(0)->change();
            $table->decimal("shipping_insurance", 20, 2)->default(0)->change();
            $table->decimal("item_insurance", 20, 2)->default(0)->change();

            $table->decimal("order_amount", 20, 2)->default(0)->change();
            $table->decimal("order_refund_amount", 20, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tiktok_pesanan', function (Blueprint $table) {

            $table->bigInteger("sku_quantity_of_return")->default(0)->change();
            $table->bigInteger("sku_unit_original")->default(0)->change();
            $table->bigInteger("sku_subtotal_before_discount")->default(0)->change();
            $table->bigInteger("sku_platform_discount")->default(0)->change();
            $table->bigInteger("sku_seller_discount")->default(0)->change();
            $table->bigInteger("sku_subtotal_after_discount")->default(0)->change();

            $table->bigInteger("shipping_fee_after_discount")->default(0)->change();
            $table->bigInteger("original_shipping_fee")->default(0)->change();
            $table->bigInteger("shipping_fee_seller_discount")->default(0)->change();
            $table->bigInteger("shipping_fee_platform_discount")->default(0)->change();

            $table->bigInteger("payment_platform_discount")->default(0)->change();
            $table->bigInteger("buyer_service_fee")->default(0)->change();
            $table->bigInteger("handling_fee")->default(0)->change();
            $table->bigInteger("shipping_insurance")->default(0)->change();
            $table->bigInteger("item_insurance")->default(0)->change();

            $table->bigInteger("order_amount")->default(0)->change();
            $table->bigInteger("order_refund_amount")->default(0)->change();
        });
    }
};
