<?php
namespace EmvigoTech\CurrencySetup\Plugin;

class PricingHelperFormatter
{
    public function afterCurrency($subject, $result)
    {
        // Match a price pattern like 1,234.00 or 12,345.67
        if (preg_match('/(\d{1,3}),(\d{3})(\.\d{2})?/', $result, $matches)) {
            $formatted = $matches[1] . "'" . $matches[2] . '.00';
            $result = preg_replace('/' . preg_quote($matches[0], '/') . '/', $formatted, $result);
        }
        return $result;
    }
}