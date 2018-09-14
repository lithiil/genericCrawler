<?php
require 'genericCrawler.php';

$genericCrawler = new GenericCrawler();
$genericCrawler->getItems('https://www.survivalschool.us/survival-info/survival-manuals-pdfs/','.mltry_munl_ul_one li a', 1);
