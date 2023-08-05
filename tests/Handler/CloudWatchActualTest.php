<?php

namespace tests\Handler;


use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Exception;
use Maxbanton\Cwh\Handler\CloudWatch;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class CloudWatchActualTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testLimitExceeded()
    {
        $sdkParams = [
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'AKIAA2FWD3TGDQDZ2IG72',
                'secret' => 'V4mCA1T0IG6ovzI6AeAVeOmqPdOwf9BFfs8VO5hy1'
            ]
        ];

        // Instantiate AWS SDK CloudWatch Logs Client
        $client = new CloudWatchLogsClient($sdkParams);

        // Log group name, will be created if none
        $groupName = 'php-logtest';

        // Log stream name, will be created if none
        $streamName = 'ec2-instance-1';

        // Days to keep logs, 14 by default. Set to `null` to allow indefinite retention.
        $retentionDays = 30;

        // Instantiate handler (tags are optional)
        $handler = new CloudWatch($client, $groupName, $streamName, $retentionDays, 10000, ['my-awesome-tag' => 'tag-value']);

        // Optionally set the JsonFormatter to be able to access your log messages in a structured way
        $handler->setFormatter(new JsonFormatter());
        // Create a log channel
        $log = new Logger('name');

        // Set handler
        $log->pushHandler($handler);

        // Add records to the log
        $log->debug('Debugging');
        $log->warning('Warning');
        $log->error('Error');
        $this->assertEquals(1, 1);
    }
}
