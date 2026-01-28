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
        Schema::table('tiktok_pendapatan', function (Blueprint $table) {

            $table->decimal("total_settlement_amount", 20, 2)->default(0)->change();
            $table->decimal("total_revenue", 20, 2)->default(0)->change();
            $table->decimal("subtotal_after_seller_discount", 20, 2)->default(0)->change();
            $table->decimal("subtotal_before_discount", 20, 2)->default(0)->change();
            $table->decimal("seller_discounts", 20, 2)->default(0)->change();

            $table->decimal("refund_subtotal_after_seller_discounts", 20, 2)->default(0)->change();
            $table->decimal("refund_subtotal_before_seller_discounts", 20, 2)->default(0)->change();
            $table->decimal("refund_of_seller_discounts", 20, 2)->default(0)->change();

            $table->decimal("total_fees", 20, 2)->default(0)->change();
            $table->decimal("tiktok_shop_commission_fee", 20, 2)->default(0)->change();
            $table->decimal("flat_fee", 20, 2)->default(0)->change();
            $table->decimal("sales_fee", 20, 2)->default(0)->change();
            $table->decimal("pre_order_service_fee", 20, 2)->default(0)->change();
            $table->decimal("mall_service_fee", 20, 2)->default(0)->change();
            $table->decimal("payment_fee", 20, 2)->default(0)->change();

            $table->decimal("shipping_cost", 20, 2)->default(0)->change();
            $table->decimal("shipping_costs_passed", 20, 2)->default(0)->change();
            $table->decimal("replacement_shipping_fee", 20, 2)->default(0)->change();
            $table->decimal("exchange_shipping_fee", 20, 2)->default(0)->change();
            $table->decimal("shipping_cost_borne", 20, 2)->default(0)->change();
            $table->decimal("shipping_cost_paid", 20, 2)->default(0)->change();
            $table->decimal("refunded_shipping_cost_paid", 20, 2)->default(0)->change();
            $table->decimal("return_shipping_costs", 20, 2)->default(0)->change();
            $table->decimal("shipping_cost_subsidy", 20, 2)->default(0)->change();

            $table->decimal("affiliate_commission", 20, 2)->default(0)->change();
            $table->decimal("affiliate_partner_commission", 20, 2)->default(0)->change();
            $table->decimal("affiliate_shop_ads_commission", 20, 2)->default(0)->change();
            $table->decimal("affiliate_commission_deposit", 20, 2)->default(0)->change();
            $table->decimal("affiliate_commission_refund", 20, 2)->default(0)->change();
            $table->decimal("tap_shop_ads_commission", 20, 2)->default(0)->change();

            $table->decimal("sfp_service_fee", 20, 2)->default(0)->change();
            $table->decimal("dynamic_commission", 20, 2)->default(0)->change();
            $table->decimal("live_specials_service_fee", 20, 2)->default(0)->change();
            $table->decimal("voucher_xtra_service_fee", 20, 2)->default(0)->change();
            $table->decimal("order_processing_fee", 20, 2)->default(0)->change();
            $table->decimal("installation_service_fee", 20, 2)->default(0)->change();
            $table->decimal("eams_program_service_fee", 20, 2)->default(0)->change();
            $table->decimal("brand_crazy_deals_fee", 20, 2)->default(0)->change();
            $table->decimal("bonus_cashback_service_fee", 20, 2)->default(0)->change();
            $table->decimal("dt_handling_fee", 20, 2)->default(0)->change();
            $table->decimal("paylater_program_fee", 20, 2)->default(0)->change();
            $table->decimal("campaign_resource_fee", 20, 2)->default(0)->change();

            $table->decimal("ajustment_amount", 20, 2)->default(0)->change();

            $table->decimal("customer_payment", 20, 2)->default(0)->change();
            $table->decimal("customer_refund", 20, 2)->default(0)->change();

            $table->decimal("seller_co_funded_voucher", 20, 2)->default(0)->change();
            $table->decimal("refund_of_seller_co_funded_voucher", 20, 2)->default(0)->change();
            $table->decimal("platform_discounts", 20, 2)->default(0)->change();
            $table->decimal("refund_of_platform_discounts", 20, 2)->default(0)->change();
            $table->decimal("platform_co_funded_voucher", 20, 2)->default(0)->change();
            $table->decimal("refund_of_platform_co_funded_voucher", 20, 2)->default(0)->change();
            $table->decimal("seller_shipping_cost_discount", 20, 2)->default(0)->change();

            $table->decimal("estimated_package_weight", 20, 2)->default(0)->change();
            $table->decimal("actual_package_weight", 20, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tiktok_pendapatan', function (Blueprint $table) {
            $table->bigInteger("total_settlement_amount")->default(0)->change();
            $table->bigInteger("total_revenue")->default(0)->change();
            $table->bigInteger("subtotal_after_seller_discount")->default(0)->change();
            $table->bigInteger("subtotal_before_discount")->default(0)->change();
            $table->bigInteger("seller_discounts")->default(0)->change();

            $table->bigInteger("refund_subtotal_after_seller_discounts")->default(0)->change();
            $table->bigInteger("refund_subtotal_before_seller_discounts")->default(0)->change();
            $table->bigInteger("refund_of_seller_discounts")->default(0)->change();

            $table->bigInteger("total_fees")->default(0)->change();
            $table->bigInteger("tiktok_shop_commission_fee")->default(0)->change();
            $table->bigInteger("flat_fee")->default(0)->change();
            $table->bigInteger("sales_fee")->default(0)->change();
            $table->bigInteger("pre_order_service_fee")->default(0)->change();
            $table->bigInteger("mall_service_fee")->default(0)->change();
            $table->bigInteger("payment_fee")->default(0)->change();

            $table->bigInteger("shipping_cost")->default(0)->change();
            $table->bigInteger("shipping_costs_passed")->default(0)->change();
            $table->bigInteger("replacement_shipping_fee")->default(0)->change();
            $table->bigInteger("exchange_shipping_fee")->default(0)->change();
            $table->bigInteger("shipping_cost_borne")->default(0)->change();
            $table->bigInteger("shipping_cost_paid")->default(0)->change();
            $table->bigInteger("refunded_shipping_cost_paid")->default(0)->change();
            $table->bigInteger("return_shipping_costs")->default(0)->change();
            $table->bigInteger("shipping_cost_subsidy")->default(0)->change();

            $table->bigInteger("affiliate_commission")->default(0)->change();
            $table->bigInteger("affiliate_partner_commission")->default(0)->change();
            $table->bigInteger("affiliate_shop_ads_commission")->default(0)->change();
            $table->bigInteger("affiliate_commission_deposit")->default(0)->change();
            $table->bigInteger("affiliate_commission_refund")->default(0)->change();
            $table->bigInteger("tap_shop_ads_commission")->default(0)->change();

            $table->bigInteger("sfp_service_fee")->default(0)->change();
            $table->bigInteger("dynamic_commission")->default(0)->change();
            $table->bigInteger("live_specials_service_fee")->default(0)->change();
            $table->bigInteger("voucher_xtra_service_fee")->default(0)->change();
            $table->bigInteger("order_processing_fee")->default(0)->change();
            $table->bigInteger("installation_service_fee")->default(0)->change();
            $table->bigInteger("eams_program_service_fee")->default(0)->change();
            $table->bigInteger("brand_crazy_deals_fee")->default(0)->change();
            $table->bigInteger("bonus_cashback_service_fee")->default(0)->change();
            $table->bigInteger("dt_handling_fee")->default(0)->change();
            $table->bigInteger("paylater_program_fee")->default(0)->change();
            $table->bigInteger("campaign_resource_fee")->default(0)->change();

            $table->bigInteger("ajustment_amount")->default(0)->change();

            $table->bigInteger("customer_payment")->default(0)->change();
            $table->bigInteger("customer_refund")->default(0)->change();

            $table->bigInteger("seller_co_funded_voucher")->default(0)->change();
            $table->bigInteger("refund_of_seller_co_funded_voucher")->default(0)->change();
            $table->bigInteger("platform_discounts")->default(0)->change();
            $table->bigInteger("refund_of_platform_discounts")->default(0)->change();
            $table->bigInteger("platform_co_funded_voucher")->default(0)->change();
            $table->bigInteger("refund_of_platform_co_funded_voucher")->default(0)->change();
            $table->bigInteger("seller_shipping_cost_discount")->default(0)->change();

            $table->bigInteger("estimated_package_weight")->default(0)->change();
            $table->bigInteger("actual_package_weight")->default(0)->change();
        });
    }
};
