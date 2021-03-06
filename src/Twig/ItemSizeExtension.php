<?php

namespace App\Twig;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ItemSizeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('sortSizes', [$this, 'sortSize']),
        ];
    }


    public function getFunctions(): array
    {
        return [
            new TwigFunction('getSize', [$this, 'getSize']),
            new TwigFunction('getSizeForProd',[$this,'getSizeForProd'])
        ];
    }

    public function getSize(?string $size)
    {
        switch ($size) {
            case "XL" :
                return "<div class=\"size-xl\">
                        <span class=\"size-x\">x</span><span class=\"size-l\">L</span>
                    </div>";
            case "XXL":
                return "<div class=\"size-xl\">
                            <span class=\"size-xx\">xx</span><span class=\"size-l\">L</span>
                        </div>";
            case "S":
            case "M":
            case "L":
                return "<div class=\"size\">$size</div>";
            default:
                return null;
        }
    }

    public function sortSize(array $items)
    {
        $baseSizes = ['S', 'M', 'L', 'XL', 'XXL'];
        usort($items,function ($a, $b) use ($baseSizes){
            $a = array_search($a->getProductSize(), $baseSizes);
            $b = array_search($b->getProductSize(), $baseSizes);

            return $a-$b;
        });
        return $items;
    }

    public function getSizeForProd(?string $size, ?string $name): string
    {
        switch ($size)
        {
            case "XL":
                return "<span class=\"size-x l-0\">x</span><span class=\"size-l l-20\">L</span>";
            case "XXL":
                return "<span class=\"size-xx l-0\">xx</span><span class=\"size-l l-20\">L</span>";
            case "S":
            case "M":
            case "L":
                return "<span class=\"size\">$size</span>";
            default:
                return "<span class=\"product-name\">$name</span>";
        }
    }
}
