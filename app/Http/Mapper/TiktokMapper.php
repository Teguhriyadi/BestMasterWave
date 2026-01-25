<?php

namespace App\Http\Mapper;

use Carbon\Carbon;
use Illuminate\Support\Str;

class TiktokMapper
{
    public static function map(object $detail): array
    {
        $fields = self::fields();

        $rows = [];

        foreach ($fields as $field) {
            $value = $detail->{$field} ?? null;

            $rows[] = [
                'key'   => $field,
                'label' => self::label($field),
                'value' => self::formatValue($field, $value),
            ];
        }

        return $rows;
    }

    public static function mapPesanan(object $detail): array
    {
        $rows = [];

        foreach (self::fieldsPesanan() as $field) {
            $value = $detail->{$field} ?? null;

            $rows[] = [
                'key'   => $field,
                'label' => self::label($field),
                'value' => self::formatValue($field, $value),
            ];
        }

        return $rows;
    }

    private static function fields(): array
    {
        return [
            'order_or_adjustment_id',
            'type',
            'nama_seller',
            'order_created_time',
            'order_settled_time',
            'currency',
            'total_settlement_amount',
            'total_revenue',
            'subtotal_after_seller_discount',
            'subtotal_before_discount',
            'seller_discounts',
            'refund_subtotal_after_seller_discounts',
            'refund_subtotal_before_seller_discounts',
            'refund_of_seller_discounts',
            'total_fees',
            'tiktok_shop_commission_fee',
            'flat_fee',
            'sales_fee',
            'pre_order_service_fee',
            'mall_service_fee',
            'payment_fee',
            'shipping_cost',
            'shipping_costs_passed',
            'replacement_shipping_fee',
            'exchange_shipping_fee',
            'shipping_cost_borne',
            'shipping_cost_paid',
            'refunded_shipping_cost_paid',
            'return_shipping_costs',
            'shipping_cost_subsidy',
            'affiliate_commission',
            'affiliate_partner_commission',
            'affiliate_shop_ads_commission',
            'affiliate_commission_deposit',
            'affiliate_commission_refund',
            'tap_shop_ads_commission',
            'sfp_service_fee',
            'dynamic_commission',
            'live_specials_service_fee',
            'voucher_xtra_service_fee',
            'order_processing_fee',
            'installation_service_fee',
            'eams_program_service_fee',
            'brand_crazy_deals_fee',
            'bonus_cashback_service_fee',
            'dt_handling_fee',
            'paylater_program_fee',
            'campaign_resource_fee',
            'ajustment_amount',
            'related_order_id',
            'customer_payment',
            'customer_refund',
            'seller_co_funded_voucher',
            'refund_of_seller_co_funded_voucher',
            'platform_discounts',
            'refund_of_platform_discounts',
            'platform_co_funded_voucher',
            'refund_of_platform_co_funded_voucher',
            'seller_shipping_cost_discount',
            'estimated_package_weight',
            'actual_package_weight',
            'shopping_center_items',
            'order_source',
            'harga_modal',
        ];
    }

    private static function fieldsPesanan(): array
    {
        return [
            'order_id',
            'order_status',
            'order_sub_status',
            'return_type',
            'normal_or_preorder',
            'sku_id',
            'seller_sku',
            'product_name',
            'variant',
            'quantity',
            'sku_quantity_of_return',
            'sku_unit_original',
            'sku_subtotal',
            'sku_subtotal_before_discount',
            'sku_platform_discount',
            'sku_seller_discount',
            'sku_subtotal_after_discount',
            'shipping_fee_after_discount',
            'original_shipping_fee',
            'shipping_fee_seller_discount',
            'shipping_fee_platform_discount',
            'payment_platform_discount',
            'buyer_service_fee',
            'handling_fee',
            'shipping_insurance',
            'item_insurance',
            'order_amount',
            'order_refund_amount',

            'created_time',
            'paid_time',
            'rts_time',
            'shipped_time',
            'delivered_time',
            'cancelled_time',

            'cancel_by',
            'cancel_reason',
            'fulfillment_type',
            'warehouse_ame',
            'tracking_id',
            'delivery_option',
            'shipping_provider_name',

            'buyer_username',
            'recipient',
            'phone',
            'zipcode',
            'country',
            'province',
            'regency_and_city',
            'districts',
            'villages',
            'detail_address',
            'additional_address_information',

            'payment_method',
            'weight',
            'product_category',
            'package_id',
            'purchase_channel',
            'seller_note',

            'checked_status',
            'checked_marked_by',
            'tokopedia_invoice_number',
        ];
    }


    private static function label(string $field): string
    {
        $label = str_replace('_', ' ', $field);

        $label = Str::title($label);

        return str_replace('Id', 'ID', $label);
    }

    private static function formatValue(string $field, $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if (Str::contains($field, ['date', 'time'])) {
            return Carbon::parse($value)->translatedFormat('d F Y');
        }

        // ANGKA
        if (is_numeric($value)) {
            return number_format($value, 0, ',', '.');
        }

        return (string) $value;
    }
}
