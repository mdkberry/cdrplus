;test mail script to run from a browser
;mbitservices March 2013
;
<?php
$to = "mdkberry@outlook.com";
$subject = "Test mail";
$message = "Hello! This is a simple email message.";
$from = "cdr@mbitservices.com.au";
$headers = "From:" . $from;
mail($to,$subject,$message,$headers);
echo "Mail Sent.";
?> 