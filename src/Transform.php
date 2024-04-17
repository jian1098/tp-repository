<?php

namespace Jian1098\TpRepository\Command;

abstract class Transform
{
    /**
     * @param $items
     * @return array
     */
    public function transformPages($items)
    {
        $items = [
            'total'         => $items['total'],
            'perPage'       => $items['per_page'],
            'currentPage'   => $items['current_page'],
            'lastPage'      => $items['last_page'],
            'data'          => array_map([$this, 'transform'], $items['data'])
        ];

        return $items;
    }

    /**
     * @param $items
     * @return array
     */
    public function transformCollection($items)
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * @param $items
     * @return mixed
     */
    public abstract function transform($items);
}