<?php

namespace Adapttive\Catalog\Plugin\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\General as Subject;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form\Element\DataType\Date;
use Magento\Ui\Component\Form\Field;

class General
{
    /**
     * Meta config path
     */
    const META_CONFIG_PATH = '/arguments/data/config';

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    /**
     * Add time to datepicker in product edit form
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterModifyMeta(Subject $subject, array $result)
    {
        $switcherConfig = [
            'dataType' => Date::NAME,
            'formElement' => Date::NAME,
            'componentType' => Field::NAME,
            'options' => [
                'showsTime' => true,
                'maxDate' => "+30y",
            ]
        ];

        $path = $this->arrayManager->findPath("release_date_time", $result, null, 'children');
        $result = $this->arrayManager->merge($path . static::META_CONFIG_PATH, $result, $switcherConfig);

        return $result;
    }
}
