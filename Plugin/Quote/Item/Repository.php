<?php
namespace Adapttive\Catalog\Plugin\Quote\Item;

use Adapttive\Catalog\Model\ReleaseValidator;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Class Repository
 * Add validation is api request for add/update item in cart
 */
class Repository
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ReleaseValidator
     */
    private $validator;

    /**
     * Item Repository Plugin constructor.
     * @param ReleaseValidator $validator
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ReleaseValidator $validator,
        ProductRepositoryInterface $productRepository
    ) {
        $this->validator = $validator;
        $this->productRepository = $productRepository;
    }

    /**
     * Validate release date time on product add to cart
     * @param CartItemRepositoryInterface $subject
     * @param $cartItem
     * @return array
     * @throws LocalizedException
     */
    public function beforeSave(CartItemRepositoryInterface $subject, $cartItem)
    {
        $product = $this->getProduct($cartItem);
        $this->validator->validate($product);
        return [$cartItem];
    }

    /**
     * Get Product for Cart Item
     * @param CartItemInterface $item
     * @return false|ProductInterface
     */
    private function getProduct(CartItemInterface $item)
    {
        $product = false;
        try {
            if ($item->getProductId()) {
                $product = $item->getProduct();
            } elseif ($item->getSku()) {
                $product = $this->productRepository->get($item->getSku(), false, $item->getStoreId());
            }
        } catch (\Exception $e) {
            $product = false;
        }
        return $product;
    }
}
