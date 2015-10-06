<?php do_action('nnr_news_int_before_table_filters_v1'); ?>

<div id="nnr-before-table" class="col-xs-12 pull-right">
	<form method="get" class="form-inline" role="form">

		<?php

		// Format dates

		$start_date = isset($_GET["start_date"]) && $_GET["start_date"] != '' ?
						date('m/d/Y', strtotime($_GET["start_date"])) :
						date('m/d/Y', mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));

		$end_date = isset($_GET["end_date"]) && $_GET["end_date"] != '' ?
						date('m/d/Y', strtotime($_GET["end_date"])) :
						date("m/d/Y", strtotime(current_time('mysql')));

		?>

		<?php if ( isset($_GET['data_id']) && $_GET['data_id'] != '' ) { ?>
			<input class="hidden" name="data_id" value="<?php echo $_GET['data_id']; ?>"/>
		<?php } ?>

		<?php if ( isset($_GET['name']) && $_GET['name'] != '' ) { ?>
			<input class="hidden" name="name" value="<?php echo $_GET['name']; ?>"/>
		<?php } ?>

		<input class="hidden" name="page" value="<?php echo self::$email_list_page; ?>"/>
		<input type="submit" value="<?php _e("Apply", self::$text_domain); ?>" class="pull-right btn btn-default nnr-margin-left"/>

		<!-- End Date -->

		<div class="form-group pull-right nnr-margin-right">
			<div class="input-group date nnr-export-end-datepicker" id="nnr-end-datepicker">
				<input id="nnr-end-date" name="end_date" type='text' class="form-control" value="<?php echo $end_date; ?>"/>
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>

		<!-- Start Date -->

		<div class="form-group pull-right nnr-margin-right">
			<div class="input-group date nnr-export-start-datepicker" id="nnr-start-datepicker">
				<input id="nnr-start-date" name="start_date" type='text' class="form-control" value="<?php echo $start_date; ?>"/>
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>

	</form>
</div>
<br />

<?php do_action('nnr_news_int_after_table_filters_v1'); ?>
<?php do_action('nnr_news_int_before_table_display_v1'); ?>

<form method="GET">
	<?php
	$newsletter_emails_table = new NNR_Newsletter_Integrations_List_Table_v1( self::$newsletter_table_name, self::$data_manager_table_name );
	$newsletter_emails_table->prepare_items();
	$newsletter_emails_table->display();
	?>
</form>

<?php do_action('nnr_news_int_after_table_display_v1'); ?>