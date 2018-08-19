<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemCollection;
use App\Http\Resources\ItemHistoryCollection;
use App\Http\Resources\ItemResource;
use App\Models\Item;

class ItemController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return ItemCollection
     */
    public function index()
    {
        return new ItemCollection(Item::orderBy('id')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateItemRequest $request
     * @return ItemResource
     */
    public function store(CreateItemRequest $request)
    {
        $item = Item::create($request->validated());

        return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return ItemResource
     */
    public function show($id)
    {
        return new ItemResource(Item::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemRequest $request
     * @param  int $id
     * @return ItemResource
     */
    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        $item->fill($request->validated());

        $item->save();

        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return ItemResource
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return new ItemResource($item);
    }

    /**
     * Item updates list
     *
     * @param $id
     * @return ItemHistoryCollection
     */
    public function history($id)
    {
        $item = Item::findOrFail($id);

        return new ItemHistoryCollection($item->history);
    }
}
