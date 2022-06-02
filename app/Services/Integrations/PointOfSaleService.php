<?php

namespace App\Services\Integrations;

class PointOfSaleService
{
    public function create($sale)
    {
        if (!userCompany()->hasIntegration('POS')) {
            return [true, ''];
        }

        if (is_null($sale->warehouse->pos_provider)) {
            return [true, ''];
        }

        $posClass = (string) str($sale->warehouse->pos_provider)->ucfirst()->prepend('App\\Integrations\\PointOfSale\\');

        return (new $posClass($sale))->create();
    }

    public function cancel($sale)
    {
        if (!userCompany()->hasIntegration('POS')) {
            return [true, ''];
        }

        if (is_null($sale->warehouse->pos_provider)) {
            return [true, ''];
        }

        $posClass = (string) str($sale->warehouse->pos_provider)->ucfirst()->prepend('App\\Integrations\\PointOfSale\\');

        return (new $posClass($sale))->void();
    }
}
