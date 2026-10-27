<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'mailpath' =>  '/usr/sbin/sendmail',
    'protocol' => 'sendmail', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'mail.ourtecads.com', 
    'smtp_port' => 465,
    'smtp_user' => 'admin@ourtecads.com',
    'smtp_pass' => 'Rama.170999',
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'text', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE
);