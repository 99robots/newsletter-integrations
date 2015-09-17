<form method="GET">
	<?php
	$newsletter_emails_table = new NNR_Newsletter_Integrations_List_Table_v1( 'newsletter_submissions_v1' );
	$newsletter_emails_table->prepare_items();
	$newsletter_emails_table->display();
	?>
</form>