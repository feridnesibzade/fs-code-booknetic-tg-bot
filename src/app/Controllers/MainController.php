<?php

namespace App\Controllers;

use App\Services\BookneticService;




class MainController
{

    public $bookneticService;

    public function __construct()
    {
        $this->bookneticService = new BookneticService();
    }

    public function home()
    {
        // bura testlər üçün istifadə oluna bilər
        echo 'MainController';

    }
}
