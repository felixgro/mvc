<?php

namespace App\Http\Controller;

use App\Lib\Http\Controller;
use App\Lib\Http\Response;

class HomeController extends Controller
{
   public static function index(): Response
   {
	   ob_start();
	   require_once path('resources/views/home.php');
	   $content = ob_get_clean();
	   return new Response($content);
   }

   public static function about(): Response
   {
	   return new Response('Hello About!');
   }
}
