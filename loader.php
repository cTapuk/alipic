<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

// If not a post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
     die('Nothing to do here! Go <a href="/">there</a>.');
}

// We return a json
header('Content-Type: application/json');


$client = new Client();

// Get page from user
$response = $client->request('GET', trim($_POST['ali_url']));
// Crawler for find feedback url
$crawler = new Crawler((string)$response->getBody());
// Get url from feedback iframe attribute
$feedbackUrl = $crawler->filter('#feedback > iframe')->attr('thesrc') . '&withPictures=true' . '&page=' . trim($_POST['page']);

// Get content of feedback iframe
$response = $client->request('GET', $feedbackUrl);

$crawler = new Crawler((string)$response->getBody());

$feedbackCountWithPhoto = $crawler->filter('.f-filter-list > label > em')->html();
$pages = ceil($feedbackCountWithPhoto/10);

$feedbackListWrap = $crawler->filter('.feedback-list-wrap');
$photosCrawler = $feedbackListWrap->filter('.feedback-item > .fb-main .pic-view-item > img');;
$photos = [];
foreach($photosCrawler as $domElement)
{
    $photos[] = $domElement->getAttribute('src');
}

//echo $photos;

echo json_encode(['content' => $photos, 'pages' => $pages, 'currentPage' => trim($_POST['page']), 'oldUrl' => trim($_POST['ali_url'])]);