define([
        'underscore',
        'jquery',
        'mage/translate',
        'jquery/ui'
    ],
    function (_, $, $t) {
        'use strict';

        return function (target) {
            $.widget('mage.catalogAddToCart', target, {
                _create: function () {
                    // TODO: add timer and disable add to cart button
                    return this._super();
                }
            });

            return $.mage.catalogAddToCart;
        };
});
