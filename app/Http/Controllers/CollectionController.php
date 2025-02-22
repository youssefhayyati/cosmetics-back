<?php

namespace App\Http\Controllers;

use App\Http\Requests\Collection\StoreCollectionRequest;
use App\Http\Resources\Collection\CollectionCollection;
use App\Http\Resources\Collection\CollectionResource;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::with('products')->get();

        return new CollectionCollection($collections);
    }

    public function show($id)
    {
        return new CollectionResource(Collection::findOrFail($id));
    }

    public function store(StoreCollectionRequest $request)
    {
        $collection = Collection::create($request->validated());

        return new CollectionResource($collection);
    }

    public function update(StoreCollectionRequest $request, Collection $collection)
    {
        $collection->update($request->validated());

        return new CollectionResource($collection);
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();

        return response(null, 204);
    }
}
