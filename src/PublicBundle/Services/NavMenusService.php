<?php

namespace PublicBundle\Services;

class NavMenusService
{
    private $function = array(
        'adminMenusService',
    );
    private $doctrine;
    private $session;

    public function __construct($doctrine, $session)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
    }

    /**
     * 根据当前角色获取相对应的Menus
     * @param $role 角色
     */
    public function getNavMenusService($role)
    {
        switch ($role) {
            case 'ROLE_ADMIN_USER':return $this->getAdminMenusService(); break;
            case 'ROLE_WORDS_USER':return $this->getWordsMenusService(); break;
            case 'ROLE_GOODS_USER':return $this->getGoodsMenusService(); break;
            default: return $this->getAnonymousMenusService(); break;
        }
    }

    /**
     * 获取Admin的Menus
     * @return $navMenus Menus
     */
    public function getAdminMenusService()
    {
        $navMenus[0] = array(
            'title' => '全局设置',
            'icon' => 'home',
            'children' => array(
                array(
                    'title' => '概述',
                    'icon' => 'dashboard',
                    'url' => 'adminIndex'
                )
            )
        );

        $navMenus[1] = array(
            'title' => '用户管理',
            'icon' => 'user-group',
            'children' => array(
                array(
                    'title' => '用户列表',
                    'icon' => 'user',
                    'url' => 'users'
                ),
                array(
                    'title' => '用户分组',
                    'icon' => 'user-follow',
                    'url' => 'list'
                )
            )
        );

        $navMenus[2] = array(
            'title' => '内容管理',
            'icon' => 'content',
            'children' => array(
                array(
                    'title' => '平台列表',
                    'icon' => 'platform',
                    'url' => 'plats'
                ),
                array(
                    'title' => '品牌列表',
                    'icon' => 'brand',
                    'url' => 'brands'
                ),
                array(
                    'title' => '店铺列表',
                    'icon' => 'shop',
                    'url' => 'shops'
                )
            )
        );

        $navMenus[3] = array(
            'title' => '系统设置',
            'icon' => 'setting',
            'children' => array(
                array(
                    'title' => '清除缓存',
                    'icon' => 'clean',
                    'url' => 'trash'
                )
            )
        );

        return $navMenus;
    }

    public function getWordsMenusService()
    {

    }
    public function getGoodsMenusService()
    {

    }
    public function getAnonymousMenusService()
    {

    }
}
