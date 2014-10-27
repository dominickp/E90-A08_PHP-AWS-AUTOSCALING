<?php
// Include my SQS QM
require_once $_SERVER['DOCUMENT_ROOT']."/E90-A08_PHP-AWS-AUTOSCALING/".'src/QueueManager.php';

// Define some variables
$queueUrl = 'https://sqs.us-east-1.amazonaws.com/330312668718/Problem8Queue';
$waitSeconds = 1;

// Settings
$timeLimitHours = 1;
set_time_limit (60*60*$timeLimitHours);

// Make a message
$myMessage = 'This is a message for assignment 08 at - '.date('l \t\h\e jS h:i:s A');

// Send the message to the queue
try{

    while(true){

        $qm = new QueueManager(); // Get the QueueManager
        $response = $qm->sendMessage($queueUrl, $myMessage);
        echo 'Message submitted. ';
        flush();

        sleep($waitSeconds); // Sleep for a bit
    }

} catch (\Aws\Sqs\Exception\SqsException $e) {

    echo 'Something went wrong: '.$e->getMessage();

}