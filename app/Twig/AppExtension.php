<?php

namespace App\Twig;

use App\Model\AddressModel;
use App\Model\ParameterModel;
use App\Model\ProductModel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension.
 *
 * @author  Jerfeson Guerreiro <jerfeson@codeis.com.br>
 *
 * @since   1.1.0
 *
 * @version 1.1.0
 */
class AppExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice',]),
        ];
    }

    /**
     * @param $number
     * @param int $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
    public function formatPrice($number, $decimals = 2, $decPoint = ',', $thousandsSep = '.')
    {
        return number_format($number, $decimals, $decPoint, $thousandsSep);
    }
}
