<?php

namespace App;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * Class TwitterClientTest
 * @package App
 */
class TwitterClientTest extends TestCase
{
    private $fakeTwitterCredentials = [
        'consumer_key' => 'fake',
        'consumer_secret' => 'fake',
        'app_key' => 'fake',
        'app_secret' => 'fake',
    ];
    private $correctTwitterCredentials = [
        'consumer_key' => '1U4jBCULC7HSd1u9gbAOLoRdv',
        'consumer_secret' => 'PO7bg6fyh04GnHA8cs3r32BcbCIgoPNeWZifnHPmqvbvgoAoMt',
        'app_key' => '956850409796263936-jD9sRSPv8bBgXz3QikELKX9ovMKchk3',
        'app_secret' => 'hq9Smgi9mZPeVrFqa01y1Y2w1bnQv9J8mnZhW92iR0mTZ',
    ];
    private $username = 'eazerytest';
    private $wrongTweetCount = TwitterClient::MAX_TWEETS_AMOUNT + 1;
    private $correctTweetCount = 2;
    private $firstTweetText = 'test two';

    public function testGetTimelineByScreenNameWrongCredentials()
    {
        $this->clearCache();
        $logger = $this->getLoagger();
        $twitterClient = new TwitterClient($this->fakeTwitterCredentials);
        $this->expectExceptionMessageRegExp('/Invalid or expired token+/');
        $twitterClient->getTimelineByScreenName($this->username, $logger);
    }

    public function testGetTimelineByScreenNameWrongTweetCount()
    {
        $logger = $this->getLoagger();
        $twitterClient = new TwitterClient($this->correctTwitterCredentials);
        $this->expectExceptionMessageRegExp('/Trying to get ' . $this->wrongTweetCount
            . ' tweets while maximum allowed is ' . TwitterClient::MAX_TWEETS_AMOUNT . ' for eazerytest+/');
        $twitterClient->getTimelineByScreenName($this->username, $logger, $this->wrongTweetCount);
    }

    public function testGetTimelineByScreenNameCorrectTweetCount()
    {
        $logger = $this->getLoagger();
        $twitterClient = new TwitterClient($this->correctTwitterCredentials);
        $tweets = $twitterClient->getTimelineByScreenName($this->username, $logger, $this->correctTweetCount);
        $this->assertEquals(json_decode($this->getCorrectTweetsJson()), $tweets);
        $this->assertEquals($this->correctTweetCount, count($tweets));
        $this->assertEquals($this->firstTweetText, $tweets[0]->text);
    }

    public function testGetTimelineByScreenNameCorrectCached()
    {
        $this->assertEquals(json_decode($this->getCorrectTweetsJson()), $this->getCache()->get($this->getCacheKey()));
        $this->clearCache();
    }

    private function getCorrectTweetsJson()
    {
        return file_get_contents(__DIR__ . '/tweets.json');
    }

    private function getCacheKey()
    {
        return $this->username . TwitterClient::CACHE_KEY_SEPARATOR . $this->correctTweetCount;
    }

    private function getCache()
    {
        return new FilesystemCache(TwitterClient::CACHE_NAMESPACE, TwitterClient::CACHE_LIFETIME_SECONDS);
    }

    private function clearCache()
    {
        $this->getCache()->delete($this->username . TwitterClient::CACHE_KEY_SEPARATOR . $this->wrongTweetCount);
        $this->getCache()->delete($this->username . TwitterClient::CACHE_KEY_SEPARATOR . TwitterClient::DEFAULT_TWEETS_COUNT);
        $this->getCache()->delete($this->username . TwitterClient::CACHE_KEY_SEPARATOR . $this->correctTweetCount);
    }

    private function getLoagger()
    {
        return new Logger('fake');
    }
}
