<pre>
<?php
$dateTime = new \DateTime();
$dateTime->add(new \DateInterval('P1Y'));
echo $dateTime->getTimestamp() . "\n";
?>
</pre>