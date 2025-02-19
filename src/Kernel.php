<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\HttpFoundation\Request;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

 
}
