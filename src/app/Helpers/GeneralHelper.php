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

function mapDays($data)
{
    $i = 0;
    $arr = [];
    $ii = 0;
    foreach ($data as $key => $d) {
        if (!empty($d)) {
            $day = (new \DateTimeImmutable($key))->format('d');
            if ($ii > 4) {
                $i++;
                $ii = 0;
            }
            $arr[$i][] = ['text' => $day, 'callback_data' => 'day.' . $day, 'times' => $d];
            $ii++;
        }
    }

    return $arr;
}

function findInMultiDimensionalNestedArray($data, $text)
{
    foreach ($data as $row) {
        $index = array_search($text, array_column($row, 'text'));
        if ($index !== false) {
            return $row[$index];
        }
    }
}

function mapTimes($times)
{
    $i = 0;
    $arr = [];
    $ii = 0;
    foreach ($times as $t) {
        if ($ii > 3) {
            $i++;
            $ii = 0;
        }
        $arr[$i][] = ['text' => $t->start_time . ' - ' . $t->end_time, 'callback_data' => 'time.' . $t->start_time . ' - ' . $t->end_time];
        $ii++;
    }

    return $arr;
}
