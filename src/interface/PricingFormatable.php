<?php
namespace Src\Interface;

interface PricingFormatable
{
    public function formatPrice(float $price):string;
}