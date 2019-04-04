<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Models\Category;
use App\Library\Models\Tag;
use App\Library\Models\Post;
use App\Library\Models\Chat;
use App\Library\Models\Notice;
use Carbon\Carbon;

/**
 * This is provider for using view share
 * @author TienDQ
 */
class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Call function composer
        $this->composer();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Composer
     */
    private function composer()
    {
        view()->composer(['frontend.layouts.layout'], function ($view) {
            //get controller and action
            $action = app('request')->route()->getAction();

            $namespace = str_replace('App\Http\Controllers', '', $action['namespace']);
            $namespace = snake_case(str_replace('\\', '', $namespace));

            $controller = class_basename($action['controller']);
            list($controller, $action) = explode('@', $controller);
            $controller = strtolower(str_replace('Controller', '', $controller));

            $view->with(compact('action'));
        });

        view()->composer(['frontend.layouts.partials.header', 'frontend.layouts.partials.footer', 'frontend.layouts.partials.box_category'], function ($view) {
            $listCategoryParent = Category::instance()->getListParent();
            $listNotice = Notice::instance()->getListNotice([
                'date_from' => Carbon::now()->subDays(7),
                'date_to' => Carbon::now(),
                'item' => 0,
                'page' => 1
            ]);

            $view->with(compact('listCategoryParent', 'listNotice'));
        });

        view()->composer(['frontend.layouts.partials.footer'], function ($view) {
            $listPost = Post::instance()->getListTopView(config('constants.post.limit.top_view'));

            $view->with(compact('listPost'));
        });

        view()->composer(['frontend.layouts.layout', 'frontend.layouts.partials.box_chat'], function ($view) {
            $listMessage = Chat::instance()->getListMessage();
            $user_id = Chat::instance()->makeUserId(csrf_token());

            $view->with(compact('listMessage', 'user_id'));
        });

        view()->composer(['frontend.layouts.partials.box_tag'], function ($view) {
            $listTag = Tag::instance()->getListTag();

            $view->with(compact('listTag'));
        });

        view()->composer(['backend.layouts.layout'], function ($view) {
            //get controller and action
            $action = app('request')->route()->getAction();

            $namespace = str_replace('App\Http\Controllers', '', $action['namespace']);
            $namespace = snake_case(str_replace('\\', '', $namespace));

            $controller = class_basename($action['controller']);
            list($controller, $action) = explode('@', $controller);
            $controller = strtolower(str_replace('Controller', '', $controller));

            $menu_code = ($namespace ? $namespace . '_' : '') . $controller . '_index';

            $view->with(compact('namespace', 'controller', 'action', 'menu_code'));
        });
    }
}
