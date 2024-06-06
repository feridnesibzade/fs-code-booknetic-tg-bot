<?php



function dump($data, $die = false)
{
    echo '<pre>';
    print_r($data);
    echo '<pre/>';
    $die ? die() : '';
}

function getActiveMonths()
{
    $months = [];
    $range = range(date('m'), 12);
    foreach ($range as $month) {
        // dump($month);
        $month = (new \DateTimeImmutable(date('Y') . '-' . $month));
        $months[$month->format('m')] = $month->format('F');
    }
    return $months;
}