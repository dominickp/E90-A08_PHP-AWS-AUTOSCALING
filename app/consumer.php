<?php
// Include my SQS QM
require_once '/var/www/E90-A08_PHP-AWS-AUTOSCALING/src/QueueManager.php';
#require_once '../src/QueueManager.php';

// Define some variables
$waitSeconds = 5;

// Settings
$timeLimitHours = 1;
set_time_limit (60*60*$timeLimitHours);
set_time_limit(15);

$messagesDeleted = 0;

// Send the message to the queue
try{

    while(true){

        $qm = new QueueManager(); // Get the QueueManager
        $queues = $qm->getQueues();

        #print_r($queues['QueueUrls']); die;

        foreach($queues['QueueUrls'] as $queueUrl)
        {
            $messageResponse = $qm->receiveMessage($queueUrl);

            $messageResponse = $messageResponse[0];

            if(empty($messageResponse['ReceiptHandle'])) continue;
            $qm->deleteMessage($queueUrl, $messageResponse['ReceiptHandle']);
            $messagesDeleted++;
            echo 'Message deleted. ';
            flush();

            sleep($waitSeconds);

        }

    }

} catch (\Aws\Sqs\Exception\SqsException $e) {

    echo 'Something went wrong: '.$e->getMessage();

}

echo $messagesDeleted.' messages deleted';