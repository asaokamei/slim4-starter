<?php


namespace App\Application\Twig;


use Twig\Markup;

class TwigFilters
{
    public function filterArrayToString(array $array)
    {
        return new Markup($this->arrayToString($array), 'UTF-8');
    }

    private function arrayToString($value)
    {
        if (is_array($value)) {
            $list = '';
            foreach ($value as $key => $v) {
                $v = $this->arrayToString($v);
                $list .= "<li>{$key}: $v</li>\n";
            }
            return "<ul>{$list}</ul>";
        }
        return $value;
    }
}