<?php

namespace App\Http\Controllers;

use App\Services\MailService;

class MailServiceController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
}
