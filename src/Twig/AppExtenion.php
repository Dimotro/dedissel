<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 25/01/2018
 * Time: 10:35
 */

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtenion extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('dateDiff', array($this, 'dateDifference')),
        );
    }

    public function dateDifference($date1, $date2)
    {
        $dt1 = new \DateTime($date1);
        $dt2 = new \DateTime($date2);
        $differenceDays = $date1->diff($date2)->days;
    }
}