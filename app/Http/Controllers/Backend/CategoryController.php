<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Http\Requests\Backend\CategoryRequest;
use App\Library\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Library\Services\CommonService;

class CategoryController extends BackendController
{
    public function index(Request $request)
    {
        $categories = Category::instance()->getListParentBE();

        return view('backend.category.index')->with([
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $parents = Category::instance()->getListParentBE();

        return view('backend.category.create')->with([
            'parents' => $parents,
        ]);
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $request_category = $request->except('_token');
            $request_category['code'] = str_slug($request_category['code'], '-');
            if (empty($request_category['display_order'])) {
                $request_category['display_order'] = Category::getLastOrder($request_category['parent_id']);
            }

            // create category
            Category::instance()->createCategory([
                'title' => $request_category['title'],
                'code' => $request_category['code'],
                'show_fe' => $request_category['show_fe'],
                'status' => $request_category[ 'status'],
                'display_order' => $request_category['display_order'],
                'parent_id' => $request_category['parent_id'],
            ]);

            DB::commit();

            return redirect(route('backend.category.index'))->withInput(['message' => ['Add new category successfully !']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect()->route('backend.category.create')->withInput(['error' => ['Add new category failed!']]);
        }
    }

    public function edit($id)
    {
        $category_info = Category::instance()->getDetailCategoryBE($id);

        if (!$category_info) {
            abort(404);
        }

        $parents = Category::instance()->getListParentBE();

        return view('backend.category.edit')->with([
            'parents' => $parents,
            'category_info' => $category_info,
        ]);
    }

    public function update(CategoryRequest $request, $id)
    {
        $category_info = Category::instance()->getDetailCategoryBE($id);

        if (!$category_info) {
            abort(404);
        }
        
        DB::beginTransaction();

        try {
            $request_category = $request->all();
            $request_category['code'] = str_slug($request_category['code'], '-');
            if (empty($request_category['display_order'])) {
                $request_category['display_order'] = $category_info->display_order;
            }
            
            //update category
            Category::instance()->updateCategory([
                'title' => $request_category['title'],
                'code' => $request_category['code'],
                'show_fe' => $request_category['show_fe'],
                'status' => $request_category['status'],
                'display_order' => $request_category['display_order'],
                'parent_id' => $request_category['parent_id'],
            ], $id);

            DB::commit();

            return redirect(route('backend.category.index'))->withInput(['message' => ['Update category successfully !']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect()->route('backend.category.edit', [$id])->withInput(['error' => ['Update category failed !']]);
        }
    }
    public function delete(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $arrId = $request->id ?? [];

        DB::beginTransaction();

        try {
            foreach ($arrId as $id) {
                Category::instance()->deleteCategory($id);
            }

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Done']);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }
}
