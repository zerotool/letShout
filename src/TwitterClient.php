<?php

namespace App;

use Endroid\Twitter\Client;
use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class TwitterClient
 * @package App
 */
class TwitterClient extends Client
{
    const CACHE_LIFETIME_SECONDS = 60 * 60;
    const CACHE_KEY_SEPARATOR = '-';
    const CACHE_NAMESPACE = 'twitter';
    const MAX_TWEETS_AMOUNT = 200;

    private $cache;

    /**
     * TwitterClient constructor.
     * @param TwitterOAuth $oAuthParameters
     */
    public function __construct($oAuthParameters)
    {
        $this->cache = new FilesystemCache(self::CACHE_NAMESPACE, self::CACHE_LIFETIME_SECONDS);
        parent::__construct(new TwitterOAuth(
            $oAuthParameters['consumer_key'],
            $oAuthParameters['consumer_secret'],
            $oAuthParameters['app_key'],
            $oAuthParameters['app_secret']
        ));
    }

    public function getCache(){
        return $this->cache;
    }

    /**
     * @param string $screenName
     * @param int $count
     * @param LoggerInterface $logger
     * @return array
     */
    public function getTimelineByScreenName(string $screenName, int $count = 25, LoggerInterface $logger): array
    {
        if ($count > self::MAX_TWEETS_AMOUNT) {
            throw new Exception('Trying to get ' . $count . ' tweets while maximum allowed is 200 for ' . $screenName);
        }
        $cacheKey = $screenName . self::CACHE_KEY_SEPARATOR . $count;
        if ($this->getCache()->has($cacheKey)) {
            $logger->debug('Getting ' . $count . ' tweets from cache for ' . $screenName);
            return $this->getCache()->get($cacheKey);
        } else {
            $logger->debug('Getting ' . $count . ' tweets from Twitter API for ' . $screenName);
            $twitterResponse = $this->getClient()->get(
                'statuses/user_timeline',
                ['screen_name' => $screenName, 'count' => $count]
            );
            $this->getCache()->set($cacheKey, $twitterResponse);
            if (empty($twitterResponse->errors) && empty($twitterResponse->error) && is_array($twitterResponse)) {
                return $twitterResponse;
            } else {
                throw new Exception('Unable to load tweets for ' . $screenName . ': '
                . empty($twitterResponse->errors) ? $twitterResponse->error : print_r($twitterResponse->errors, true));
            }
        }
    }
}
