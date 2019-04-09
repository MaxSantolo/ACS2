<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';

builder::startSession();
builder::Header('ACS 2.0 - Menu Principale','sfondo.jpg');
builder::Navbar('DataTable');

//html
echo "
<div style=\"width: 600px;margin: auto;padding: 10px\"><br><br><br><br><img src=\"images/logo_acs2.png\" width=\"550\"></div>
";

builder::Scripts();


