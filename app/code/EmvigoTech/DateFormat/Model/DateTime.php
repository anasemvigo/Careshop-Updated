<?php

namespace EmvigoTech\DateFormat\Model;

use Magento\Framework\Stdlib\DateTime as MagentoDateTime;

class DateTime extends MagentoDateTime
{
    const DATETIME_PHP_FORMAT = 'Y-m-d H:i:s';
    const DATE_PHP_FORMAT = 'Y-m-d';

    /**
     * Format date to internal format
     *
     * @param string|\DateTimeInterface|bool|null $date
     * @param boolean $includeTime
     * @return string|null
     */
    public function formatDate($date, $includeTime = true)
    {
        $format = $includeTime ? self::DATETIME_PHP_FORMAT : self::DATE_PHP_FORMAT;

        if ($date instanceof \DateTimeInterface) {
            return $date->format($format);
        } elseif (empty($date)) {
            return null;
        } elseif ($date === true) {
            $date = (new \DateTime())->getTimestamp();
        } elseif (is_string($date)) {
            $dateObject = \DateTime::createFromFormat('d/m/Y', $date);
            if ($dateObject === false) {
                $dateObject = new \DateTime($date);
            }
            $date = $dateObject->getTimestamp();
        } elseif (!is_numeric($date)) {
            $date = (new \DateTime($date))->getTimestamp();
        }

        return (new \DateTime())->setTimestamp($date)->format($format);
    }
}
