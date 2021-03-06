<?php

namespace Adapttive\Catalog\Plugin\Checkout;

use Adapttive\Catalog\Helper\Config;
use Adapttive\Catalog\Model\ReleaseValidator;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ValidateAddToCart
 * This validation plugin is added to fix the redirection while add to cart
 * from product listing page
 */
class ValidateAddToCart
{
    /**
     * @var ReleaseValidator
     */
    private $validator;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        ReleaseValidator $validator
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->validator = $validator;
    }

    /**
     * @param Cart $subject
     * @param int|Product $productInfo
     * @param DataObject|int|array $requestInfo
     * @return array
     * @throws LocalizedException
     */
    public function beforeAddProduct(
        Cart $subject,
        $productInfo,
        $requestInfo = null
    ) {
        if ($this->config->isEnabled()) {
            $product = $this->getProduct($productInfo);
            $this->validator->validate($product);
        }
        return [$productInfo, $requestInfo];
    }

    /**
     * Get product object based on requested product information
     * copied from: \Magento\Checkout\Model\Cart::_getProduct
     * @param Product|int|string $productInfo
     * @return Product
     * @throws LocalizedException
     */
    protected function getProduct($productInfo)
    {
        $product = null;
        if ($productInfo instanceof Product) {
            $product = $productInfo;
            if (!$product->getId()) {
                throw new LocalizedException(
                    __("The product wasn't found. Verify the product and try again.")
                );
            }
        } elseif (is_int($productInfo) || is_string($productInfo)) {
            $storeId = $this->storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productInfo, false, $storeId);
            } catch (NoSuchEntityException $e) {
                throw new LocalizedException(
                    __("The product wasn't found. Verify the product and try again."),
                    $e
                );
            }
        } else {
            throw new LocalizedException(
                __("The product wasn't found. Verify the product and try again.")
            );
        }
        $currentWebsiteId = $this->storeManager->getStore()->getWebsiteId();
        if (!is_array($product->getWebsiteIds()) || !in_array($currentWebsiteId, $product->getWebsiteIds())) {
            throw new LocalizedException(
                __("The product wasn't found. Verify the product and try again.")
            );
        }
        return $product;
    }
}
