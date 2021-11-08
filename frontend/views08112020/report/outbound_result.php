<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$gridColumns = [

		'salutation_id',
'salutation',
'full_name',
'preferred_name',
'mobile_number',
'marital_status',
'spouse_name',
'business_name',
'staff_pf',
'alternative_number',
'email',
'address1',
'address2',
'address3',
'town',
'district',
'postal_code',
'alternate_address1',
'alternate_address2',
'alternate_address3',
'alternate_town',
'alternate_district',
'alternate_postal_code',
'preferred_language',
'customer_since',
'citizenship',
'profession',
'employer',
'dob',
'branch',
'relationship_manager',
'customer_status',
'customer_type',
'customer_created_by',
'customer_created_by_name',

'customer_created_date',
'cif',
'latest_campaign_id',
'vip_flag',
'group_code',
'group_description',
'province',
'case_id',
'channel_type',
'campaign',
'outcome_code1',
'outcome_code2',
'outcome_code3',
'case_status',
'cid',
'case_note',
'case_field1',
'case_field2',
'case_field3',
'case_field5',
'customer_cases_created_by',
'customer_cases_created_by_name',

'customer_cases_created_datetime',
'escalated_to',
'customer_id',
'severity_level',
'call_back_date',
'location_of_atm',
'debit_card_number',
'branch_department',
'credit_card_number',
'charge_disputed__note',
'reference_number_of_application',
'application_date',
'new_credit_limit_requested',
'current_credit_limit',
'reason_for_change_in_credit_limit',
'mobile_number_requested_on',
'current_debit_limit',
'new_debit_limit_requested',
'reason_for_change_in_debit_limit',
'charge_disputed',
'product_interest_in',
'responsible_officer',
'TAT',
'merchant_number',
'amount',
'app_version',
'account_number',
'product_name',
'account_limit',
'account_status',
'digital_products',
'product_field1',
'product_field2',
'card_number',
'relationship',
'relation_cif',
'nic',
'currency',
'outbound_interaction_id',
'case_tbl_id',
'inbound_interaction_channel_type',
'interaction_status',
'notes',
'inbound_interaction_created_by_name',
'inbound_interaction_created_created_date'

        ];

?>
<div id="report-list">

    <?php \yii\widgets\Pjax::begin(['id' => 'report-widget','enablePushState'=>FALSE,'timeout' => 1000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <h5 style="line-height:40px">Data Generated:


    <div id="export-box">
    <?php if(Yii::$app->user->can('Export Report (Complete)')) { ?>
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
				//'autoXlFormat'=>true,
                'columns' => $gridColumns,
                'showColumnSelector'=>false,
				'filename' => 'Outbound_Interaction_Data',
                'exportConfig'=> [
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_EXCEL => true,

                    /*ExportMenu::FORMAT_EXCEL_X => [
                        'label' => Yii::t('kvexport', 'Excel'),
                    ]*/
                ],
                'dropdownOptions' => [
                    'label' => 'Export',
                    'class' => 'btn btn-sm btn-primary'
                ]
            ]);
        ?>
    <?php } ?>
    </div></h5>
    <div style="clear: both;"></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'report-list-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => $gridColumns
    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>

</div>

  <?php
    $this->registerJs("
		/*
        $('td').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if(e.target == this && id != undefined)
                location.href = '" . Url::to(['/tools/user']) . "/' + id;
        });*/
		$('.table').DataTable({ \"scrollX\": true,   \"scrollY\":        \"200px\",
        \"scrollCollapse\": true,
        \"paging\":         false,
		
});
    ");
    ?>

<style>

.summary {
	display:none;
}
#w0-excel5 {
	display:none;
}
#w0-excel2007 {
	display:none;
}
</style>