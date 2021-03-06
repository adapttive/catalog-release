<?php

namespace Adapttive\Catalog\Observer;

use Adapttive\Catalog\Helper\Config;
use Adapttive\Catalog\Model\ReleaseValidator;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote\Item;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ReleaseObserver
 * Trigger the release date validation..
 * Reference: catalog inventory validation
 */
class ReleaseObserver implements ObserverInterface
{
    /**
     * @var ReleaseValidator
     */
    private $validator;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Config $config,
        ReleaseValidator $validator
    ) {
        $this->validator = $validator;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        /* @var $quoteItem Item */
        $quoteItem = $observer->getEvent()->getItem();
        if (!$quoteItem ||
            !$quoteItem->getProductId() ||
            !$quoteItem->getQuote() ||
            !$this->config->isEnabled()
        ) {
            return;
        }
        $product = $quoteItem->getProduct();

        $this->validator->validate($product);
    }
}
