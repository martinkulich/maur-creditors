<?php

function my_format_currency($amount, $currency = null, $culture = null)
{
      if (null === $amount)
  {
    return null;
  }

  $numberFormat = new sfNumberFormat(_current_language($culture));
  $amount = round($amount, 0);
  return $numberFormat->format($amount, '#,### ¤', $currency);
}