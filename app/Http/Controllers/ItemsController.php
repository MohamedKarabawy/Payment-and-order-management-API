<?php

namespace App\Http\Controllers;

use App\DTO\Items\ItemDTO;
use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $items = Item::query()->paginate(10);
        
        return ItemResource::collection($items);
    }

    public function show(int $id): ItemResource
    {
        return new ItemResource(Item::findOrFail($id));
    }

    public function store(StoreItemRequest $request): JsonResponse
    {
        $item = ItemDTO::fromRequest($request->validated());

        $item = Item::create($item->toArray());

        return response()->json(
            [
                'message' => 'Item created successfully',
                'item' => new ItemResource($item)
            ],201);
    }

    public function update(UpdateItemRequest $request, int $id): JsonResponse
    {
        $itemData = ItemDTO::fromRequest($request->validated());

        $item = Item::findOrFail($id);

        $item->update($itemData->toArray());

        return response()->json([
            'message' => 'Item updated successfully',
            'item' => new ItemResource($item)
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Item::findOrFail($id);

        $item->delete();  // use forceDelete() for permanent deletion

        return response()->json(['message' => 'Item deleted successfully'], 200);
    }
}
