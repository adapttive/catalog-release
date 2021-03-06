<?php
/**
 *
 * Adapttive/Catalog: Module for Magento 2 to manage catalog release by a pre-specified date.
 *
 * @category PHP
 * @package Adapttive
 * @subpackage Ui
 * @author Milind Singh <hello@adapttive.com>
 * @copyright 2020 Milind Singh
 * @license MIT
 * @version 1.0.0
 */

namespace Adapttive\Catalog\Helper;


use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    const CONFIG_PATH_IS_ENABLED = "adapttive/catalog/enabled";

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        ScopeConfigInterface $config,
        StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * Is module enabled
     * @return bool
     */
    public function isEnabled()
    {
        try {
            $websiteId = $this->storeManager->getWebsite()->getId();
            $flag = (bool)$this->config->getValue(
                self::CONFIG_PATH_IS_ENABLED,
                ScopeInterface::SCOPE_WEBSITES,
                $websiteId
            );
        } catch (Exception $e) {
            $flag = false;
        }
        return $flag;
    }
}
