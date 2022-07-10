<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once 'helpers.php';

const RUB = '₽';
$title = 'Main page';
$user_name = 'Yeti';
$is_auth = rand(0, 1);

require_once 'vendor/autoload.php';
use Src\PdoDb as PdoDb;

$db = PdoDb::getInstance();

function getCategories(PdoDb $db): array
{
    $query = "SELECT name, code FROM categories";
    return $db->fetchAll($query);
}
$categories = getCategories($db);

function getActiveLots(PdoDb $db): array
{
    $query = "
        SELECT
            u.name as ownerName,
            c.name as categoryName,
            c.code as categoryCode,
            l.id, l.title, l.description, l.img, l.enddate as endDate, l.startprice as startPrice,
            b.offer as endPrice
        FROM lots l
        INNER JOIN users u ON u.id = l.owner
        INNER JOIN categories c ON c.id = l.category
        LEFT JOIN bids b ON b.id = (
            SELECT b.id
            FROM bids b
            WHERE b.lot = l.id
            ORDER BY b.id DESC LIMIT 1
        )
    ";
    return $db->fetchAll($query);
}
$activeLots = getActiveLots($db);

function getFutureDate(): int
{
    $daySecLength = 60 * 60 * 24;
    $daysMin = 0;
    $daysMax = 7;
    $daySecLength = $daySecLength * rand($daysMin, $daysMax);
    $additionalHoursLength = 60 * 60 * rand(0, 23);
    $additionalMinutesLength = 60 * rand(0, 59);
    $additionalSecLength = rand(0, 59);
    $additionalLength = ($additionalHoursLength + $additionalMinutesLength + $additionalSecLength);

    return time() + $daySecLength + $additionalLength;
}
function addEndTimeToLots(array &$activeLots): void
{
    foreach ($activeLots as $index => $lot) {
        $activeLots[$index]['endDate'] = getFutureDate();
        $activeLots[$index]['timeLeft'] = $activeLots[$index]['endDate'] - time();
    }
}
addEndTimeToLots($activeLots);

function normalizePrice(int $price): string
{
    return number_format(ceil($price), 0, '', ' ') . RUB;
}
function getLotsFinalPrice(array &$activeLots): void
{
    foreach ($activeLots as &$lot) {
        if ($lot['endPrice']) {
            $lot['endPrice'] = normalizePrice($lot['endPrice']);
        } else {
            $lot['endPrice'] = normalizePrice($lot['startPrice']);
        }
    }
}
getLotsFinalPrice($activeLots);

$main = include_template('main.php', [
    'activeLots' => $activeLots,
    'categories' => $categories,
]);

$layout = include_template('layout.php', [
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'main' => $main,
]);

echo $layout;
