<?php

declare(strict_types=1);

namespace App\Application\Admin\Controller;

use App\Annotation\View;
use App\Application\Admin\Service\AccessService;
use App\Application\Admin\Service\AdminUserService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Application\Admin\Middleware\AdminMiddleware;

/**
 * @Middleware(AdminMiddleware::class)
 * @Controller(prefix="/admin/index")
 */
class IndexController extends AdminAbstractController
{

    /**
     * @GetMapping(path="index/lists")
     */
    function lists()
    {
        $menu_list = AccessService::getInstance()
            ->getMenuByRoleId(AdminUserService::getInstance()
                ->getAdminUserRoleId());
        $admin_user = AdminUserService::getInstance()
            ->getAdminUser();

        return $this->returnSuccessJson(compact('menu_list', 'admin_user'));
    }

    /**
     * @View()
     * @GetMapping(path="index")
     */
    function index() { }
}
