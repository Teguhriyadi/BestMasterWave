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
        Schema::create('tiktok_pendapatan', function (Blueprint $table) {
            $table->id();
            $table->uuid("uuid")->unique();
            $table->string("order_or_adjustment_id", 100)->nullable()->index();
            $table->string("type", 30)->nullable();
            $table->string("nama_seller", 100)->nullable();
            $table->date("order_created_time")->nullable();
            $table->date("order_settled_time")->nullable();
            $table->string("currency", 30)->nullable();
            $table->unsignedBigInteger("total_settlement_amount")->default(0);
            $table->unsignedBigInteger("total_revenue")->default(0);
            $table->unsignedBigInteger("subtotal_after_seller_discount")->default(0);
            $table->unsignedBigInteger("subtotal_before_discount")->default(0);
            $table->unsignedBigInteger("seller_discounts")->default(0);
            $table->unsignedBigInteger("refund_subtotal_after_seller_discounts")->default(0);
            $table->unsignedBigInteger("refund_subtotal_before_seller_discounts")->default(0);
            $table->unsignedBigInteger("refund_of_seller_discounts")->default(0);
            $table->unsignedBigInteger("total_fees")->default(0);
            $table->bigInteger("tiktok_shop_commission_fee")->default(0);
            $table->bigInteger("flat_fee")->default(0);
            $table->bigInteger("sales_fee")->default(0);
            $table->bigInteger("pre_order_service_fee")->default(0);
            $table->bigInteger("mall_service_fee")->default(0);
            $table->bigInteger("payment_fee")->default(0);
            $table->bigInteger("shipping_cost")->default(0);
            $table->bigInteger("shipping_costs_passed")->default(0);
            $table->bigInteger("replacement_shipping_fee")->default(0);
            $table->bigInteger("exchange_shipping_fee")->default(0);
            $table->bigInteger("shipping_cost_borne")->default(0);
            $table->bigInteger("shipping_cost_paid")->default(0);
            $table->bigInteger("refunded_shipping_cost_paid")->default(0);
            $table->bigInteger("return_shipping_costs")->default(0);
            $table->bigInteger("shipping_cost_subsidy")->default(0);
            $table->bigInteger("affiliate_commission")->default(0);
            $table->bigInteger("affiliate_partner_commission")->default(0);
            $table->bigInteger("affiliate_shop_ads_commission")->default(0);
            $table->bigInteger("affiliate_commission_deposit")->default(0);
            $table->bigInteger("affiliate_commission_refund")->default(0);
            $table->bigInteger("tap_shop_ads_commission")->default(0);
            $table->bigInteger("sfp_service_fee")->default(0);
            $table->bigInteger("dynamic_commission")->default(0);
            $table->bigInteger("live_specials_service_fee")->default(0);
            $table->bigInteger("voucher_xtra_service_fee")->default(0);
            $table->bigInteger("order_processing_fee")->default(0);
            $table->bigInteger("installation_service_fee")->default(0);
            $table->bigInteger("eams_program_service_fee")->default(0);
            $table->bigInteger("brand_crazy_deals_fee")->default(0);
            $table->bigInteger("bonus_cashback_service_fee")->default(0);
            $table->bigInteger("dt_handling_fee")->default(0);
            $table->bigInteger("paylater_program_fee")->default(0);
            $table->bigInteger("campaign_resource_fee")->default(0);
            $table->bigInteger("ajustment_amount")->default(0);
            $table->bigInteger("related_order_id")->default(0);
            $table->bigInteger("customer_payment")->default(0);
            $table->bigInteger("customer_refund")->default(0);
            $table->bigInteger("seller_co_funded_voucher")->default(0);
            $table->bigInteger("refund_of_seller_co_funded_voucher")->default(0);
            $table->bigInteger("platform_discounts")->default(0);
            $table->bigInteger("refund_of_platform_discounts")->default(0);
            $table->bigInteger("platform_co_funded_voucher")->default(0);
            $table->bigInteger("refund_of_platform_co_funded_voucher")->default(0);
            $table->bigInteger("seller_shipping_cost_discount")->default(0);
            $table->bigInteger("estimated_package_weight")->default(0);
            $table->bigInteger("actual_package_weight")->default(0);
            $table->string("shopping_center_items", 150)->nullable();
            $table->string("order_source", 100)->nullable();
            $table->uuid("divisi_id");
            $table->unsignedBigInteger("harga_modal")->default(0);
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
        Schema::dropIfExists('tiktok_pendapatan');
    }
};
