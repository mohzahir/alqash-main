<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\StoreType;
use App\Model\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;

class StoreTypeController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $types = StoreType::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $types = StoreType::where('id' ,'!=', 0);
        }

        $types = $types->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.store.view', compact('types','search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'StoreType name is required!',
        ]);

        $type = new StoreType;
        $type->name = $request->name[array_search('en', $request->lang)];
        $type->save();

        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Model\StoreType',
                    'translationable_id' => $type->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                ));
            }
        }
        if (count($data)) {
            Translation::insert($data);
        }

        Toastr::success('Store Type added successfully!');
        return back();
    }

    public function edit(Request $request, $id)
    {
        $category = StoreType::with('translations')->withoutGlobalScopes()->find($id);
        return view('admin-views.store.store-edit', compact('category'));
    }

    public function update(Request $request)
    {
        $category = StoreType::find($request->id);
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\StoreType',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success('Store Type updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $translation = Translation::where('translationable_type','App\Model\StoreType')
                                    ->where('translationable_id',$request->id);
        $translation->delete();
        StoreType::destroy($request->id);

        return response()->json();
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $data = StoreType::where('position', 0)->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }
}
