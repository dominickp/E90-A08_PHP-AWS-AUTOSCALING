<?php
// Require AWS
require '/var/www/E90-A08_PHP-AWS-AUTOSCALING/vendor/autoload.php';
#require '../vendor/autoload.php';

use Aws\Sqs\SqsClient;

class QueueManager{

    protected $sqsClient;

    public function __construct()
    {
        // Set the SQS client
        $this->sqsClient = $this->getSqsClient();
    }

    // Return the SQS client
    protected function getSqsClient()
    {
        $clientParams = array(
            'region'  => 'us-east-1',
            'key'    => 'AKIAIYIGEXE7FPVUD5XQ',
            'secret' => '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',
        );
        $client = SqsClient::factory($clientParams);
        return $client;
    }

    // List queues
    public function getQueues()
    {
        $queues = $this->sqsClient->listQueues();

        return $queues;
    }

    // Create a queue, returns the queue URL
    public function createQueue($queueName)
    {
        $result = $this->sqsClient->createQueue(array('QueueName' => $queueName));
        $queueUrl = $result->get('QueueUrl');

        return $queueUrl;
    }

    // Set attributes of an already created queue. Returns a response Model object
    public function setAttributes($queueUrl, $seconds)
    {
        $result = $this->sqsClient->setQueueAttributes(array(
            'QueueUrl'   => $queueUrl,
            'Attributes' => array(
                'VisibilityTimeout' => $seconds,
            ),
        ));

        return $result;
    }

    // Send a message to an already created queue.
    public function sendMessage($queueUrl, $message, $delaySeconds = null)
    {
        $messageParams = array(
            'QueueUrl'     => $queueUrl,
            'MessageBody'  => $message
        );

        // Set the delay seconds only if something provided
        if(!empty($delaySeconds)) $messageParams['DelaySeconds'] = $delaySeconds;

        $result = $this->sqsClient->sendMessage($messageParams);

        return $result;
    }

    // Receive message in the queue
    public function receiveMessage($queueUrl)
    {
        $result = $this->sqsClient->receiveMessage(array(
            'QueueUrl' => $queueUrl,
        ));

        #return $result->getPath('Messages')[0];
        return $result->getPath('Messages');
    }

    // Delete a message
    public function deleteMessage($queueUrl, $receiptHandle)
    {
        $result = $this->sqsClient->deleteMessage(array(
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $receiptHandle,
        ));

        return $result;
    }

    // Returns all attributes
    public function getAllAttributes($queueUrl)
    {
        $result = $this->sqsClient->getQueueAttributes(array(
            'QueueUrl' => $queueUrl,
            'AttributeNames' => array('All')
        ));

        return $result;
    }

    // Delete a queue
    public function deleteQueue($queueUrl)
    {
        $result = $this->sqsClient->deleteQueue(array(
            'QueueUrl' => $queueUrl,
        ));

        return $result;
    }

}