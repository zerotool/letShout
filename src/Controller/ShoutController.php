<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
use App\TwitterClient;
use Psr\Log\LoggerInterface;

class ShoutController extends Controller
{
    /**
     * @Route(
     *     "/api/get/{screenName}/{postsCount}", name="shout_get", defaults={"postsCount": 25},
     *     requirements={
     *         "postsCount": "\d+"
     *     }
     * )
     * @param string $screenName
     * @param int $postsCount
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(string $screenName, int $postsCount, LoggerInterface $logger)
    {
        try {
            $twitterClient = new TwitterClient($this->getParameter('twitter_oauth'), $logger);
            $responseData = array_map(
                function ($tweet) {
                    return utf8_encode(strtoupper($tweet->text) . '!');
                },
                $twitterClient->getTimelineByScreenName($screenName, $postsCount, $logger)
            );
            return $this->json($responseData);
        } catch (Exception $ex) {
            $logger->critical('Unable to load ' . $postsCount . ' tweets for ' . $screenName . ': ' . $ex->getMessage());
            return $this->json(['error' => 'Unable to load tweets, incident details are reported in log']);
        }
    }
}
