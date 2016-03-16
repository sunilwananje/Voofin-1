<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewForPiAndBandConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        DB::statement( 'CREATE VIEW pi_view AS select `pi`.`id` AS `pi_id`,`pi`.`pi_number` AS `pi_number`,`pi`.`net_amount` AS `pi_net_amount`,`pi`.`amount` AS `pi_amount`,`pi`.`status` AS `pi_status`,`inv`.`id` AS `invoice_id`,`inv`.`uuid` AS `invoice_uuid`,`inv`.`invoice_number` AS `invoice_number`,`inv`.`final_amount` AS `invoice_final_amount`,`inv`.`due_date` AS `due_date`,`inv`.`status` AS `invoice_status`,`inv`.`currency` AS `invoice_currency`,`inv`.`tax_details` AS `tax_details`,`inv`.`discount_type` AS `discount_type`,`inv`.`discount` AS `invoice_discount`,`inv`.`amount` AS `invoice_amount`,`inv`.`seller_id` AS `seller_id`,`inv`.`buyer_id` AS `buyer_id` from (`geo`.`invoices` `inv` join `geo`.`payment_instructions` `pi` on((`pi`.`invoice_id` = `inv`.`id`)))' );
        DB::statement( 'CREATE VIEW company_band_view AS select `cb`.`id` AS `buyer_id`,`cb`.`uuid` AS `buyer_uuid`,`cb`.`name` AS `buyer_name`,`cb`.`address` AS `buyer_address`,`cs`.`id` AS `seller_id`,`cs`.`uuid` AS `seller_uuid`,`cs`.`name` AS `seller_name`,`cs`.`address` AS `seller_address`,`bn`.`tax_percentage` AS `tax_percentage`,`bn`.`band_id` AS `band_id`,`b`.`manualDiscounting` AS `manualDiscounting`,`b`.`autoDiscounting` AS `autoDiscounting`,`b`.`discounting` AS `discounting` from (((`geo`.`band_configurations` `bn` join `geo`.`companies` `cb` on((`cb`.`id` = `bn`.`buyer_id`))) join `geo`.`companies` `cs` on((`cs`.`id` = `bn`.`seller_id`))) join `geo`.`bands` `b` on((`b`.`id` = `bn`.`band_id`)))' );
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement( 'DROP VIEW pi_view' );
        DB::statement( 'DROP VIEW company_band_view' );
    }
}
