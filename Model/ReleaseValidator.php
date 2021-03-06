<?php

namespace Adapttive\Catalog\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class ReleaseValidator
{
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    public function __construct(
        TimezoneInterface $timezone
    ) {
        $this->timezone = $timezone;
    }

    /**
     * Validate if product is released
     * @param $product
     * @return bool
     * @throws LocalizedException
     */
    public function validate($product)
    {
        if ($product && $product->getReleaseDateTime()) {
            // if product not released throw exception.
            $releaseDateTime = $this->timezone->date((string)$product->getReleaseDateTime());
            $current = $this->timezone->date();
            if ($releaseDateTime && $releaseDateTime > $current) {
                throw new LocalizedException(
                    __(
                        "The product is not available for purchase. Please retry after %1.",
                        $releaseDateTime->format(DateTime::DATETIME_PHP_FORMAT
                        )
                    )
                );
            }
        }

        return true;
    }
}
