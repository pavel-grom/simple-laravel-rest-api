<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemHistoryCollection extends ResourceCollection
{
    private $result = true;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'result' => $this->result,
        ];
    }

    /**
     * @param bool $result
     * @return ItemHistoryCollection
     */
    public function setResult(bool $result) : self
    {
        $this->result = $result;

        return $this;
    }
}
