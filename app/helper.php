<?php


if (!function_exists("get_json_permissions")) {
    function get_json_permissions() {
        $permisions = [
            "Post" => [
                'desc' => "Permissions For Manage Posts",
                'permissions' => [
                    [ 'name' => 'read_post', 'desc' => "Read Post" ],
                    [ 'name' => 'create_post', 'desc' => "Create Post" ],
                    [ 'name' => 'delete_post', 'desc' => "Delete Post" ],
                    [ 'name' => 'edit_post', 'desc' => "Edit Post" ],
                    [ 'name' => 'force_delete_post', 'desc' => "Delete From Trash" ],
                    [ 'name' => 'restore_post', 'desc' => "Restore From Trash" ],
                    [ 'name' => 'read_all_post', 'desc' => "Read All Post" ],
                    [ 'name' => 'delete_all_post', 'desc' => "Delete All Post" ],
                    [ 'name' => 'edit_all_post', 'desc' => "Edit All Post" ],
                    [ 'name' => 'force_delete_all_post', 'desc' => "Delete All Users From Trash" ],
                    [ 'name' => 'restore_all_post', 'desc' => "Restore All Users From Trash" ],
                ],
            ],
//            "Video" => [
//                'desc' => "Permissions For Manage Videos",
//                'permissions' => [
//                    [ 'name' => 'read_video', 'desc' => "Read Video" ],
//                    [ 'name' => 'create_video', 'desc' => "Create Video" ],
//                    [ 'name' => 'delete_video', 'desc' => "Delete Video" ],
//                    [ 'name' => 'edit_video', 'desc' => "Edit Video" ],
//                    [ 'name' => 'force_delete_video', 'desc' => "Delete Video From Trash" ],
//                    [ 'name' => 'read_all_video', 'desc' => "Read All Video" ],
//                    [ 'name' => 'delete_all_video', 'desc' => "Delete All Video" ],
//                    [ 'name' => 'edit_all_video', 'desc' => "Edit All Video" ],
//                    [ 'name' => 'force_delete_all_video', 'desc' => "Delete All Users Videos From Trash" ],
//                ],
//            ],
            "Page" => [
                'desc' => "Permissions For Manage Pages",
                'permissions' => [
                    [ 'name' => 'read_page', 'desc' => "Read Page" ],
                    [ 'name' => 'create_page', 'desc' => "Create Page" ],
                    [ 'name' => 'delete_page', 'desc' => "Delete Page" ],
                    [ 'name' => 'edit_page', 'desc' => "Edit Page" ],
                    [ 'name' => 'force_delete_page', 'desc' => "Delete Page From Trash" ],
                    [ 'name' => 'restore_page', 'desc' => "Restore Page From Trash" ],
                    [ 'name' => 'read_all_page', 'desc' => "Read All Page" ],
                    [ 'name' => 'delete_all_page', 'desc' => "Delete All Page" ],
                    [ 'name' => 'edit_all_page', 'desc' => "Edit All Page" ],
                    [ 'name' => 'force_delete_all_page', 'desc' => "Delete All Users Page From Trash" ],
                    [ 'name' => 'restore_all_page', 'desc' => "Restore All Users Page From Trash" ],
                ],
            ],
            'Media'=>[
                'desc' => "Permissions For Manage Media`s",
                'permissions' => [
                    [ 'name' => 'read_media', 'desc' => "Read Media" ],
                    [ 'name' => 'create_media', 'desc' => "Create Media" ],
                    [ 'name' => 'delete_media', 'desc' => "Delete Media" ],
                    [ 'name' => 'edit_media', 'desc' => "Edit Media" ],
                    [ 'name' => 'force_delete_media', 'desc' => "Delete Media From Trash" ],
                    [ 'name' => 'read_all_media', 'desc' => "Read All Media" ],
                    [ 'name' => 'delete_all_media', 'desc' => "Delete All Media" ],
                    [ 'name' => 'edit_all_media', 'desc' => "Edit All Media" ],
                    [ 'name' => 'force_delete_all_media', 'desc' => "Delete All Users Media From Trash" ],
                ],
            ],
            "Category" => [
                'desc' => "Permissions For Manage Pages",
                'permissions' => [
                    [ 'name' => 'read_category', 'desc' => "Read Category" ],
                    [ 'name' => 'create_category', 'desc' => "Create Category" ],
                    [ 'name' => 'delete_category', 'desc' => "Delete Category" ],
                    [ 'name' => 'edit_category', 'desc' => "Edit Category" ],
                    [ 'name' => 'force_delete_category', 'desc' => "Delete Category From Trash" ],
                ],
            ],
            "Tag" => [
                'desc' => "Permissions For Manage Tags",
                'permissions' => [
                    [ 'name' => 'read_tag', 'desc' => "Read Tag" ],
                    [ 'name' => 'create_tag', 'desc' => "Create Tag" ],
                    [ 'name' => 'delete_tag', 'desc' => "Delete Tag" ],
                    [ 'name' => 'edit_tag', 'desc' => "Edit Tag" ],
                ],
            ],
            "Users" => [
                'desc' => "Permissions For Manage Users",
                'permissions' => [
                    [ 'name' => 'read_users', 'desc' => "Read All Users" ],
                    [ 'name' => 'create_users', 'desc' => "Create All Users" ],
                    [ 'name' => 'delete_users', 'desc' => "Delete All Users" ],
                    [ 'name' => 'edit_users', 'desc' => "Edit All Users" ],
                    [ 'name' => 'force_delete_users', 'desc' => "Delete Category From Trash" ],
                ],
            ],
            "Role" => [
                'desc' => "Permission For User Roles",
                'permissions'=>[
                    [ 'name' => 'read_role', 'desc' => "Read All Roles" ],
                    [ 'name' => 'create_role', 'desc' => "Create All Roles" ],
                    [ 'name' => 'delete_role', 'desc' => "Delete All Roles" ],
                    [ 'name' => 'edit_role', 'desc' => "Edit All Roles" ],
                    [ 'name' => 'force_delete_role', 'desc' => "Delete Roles From Trash" ],
                ]
            ],
            "Settings" => [
                "desc" => "Permission Change Site Settings",
                'permissions' => [
                    ['name' => 'change_site_settings', 'desc' => "Change Site Settings"]
                ]
            ],
            "Comments" => [
                'desc' => "Permission for manage comments",
                'permissions' => [
                    [ 'name' => 'create_comment', 'desc' => "Permission for create Comment"],
                    ['name' => 'comments_actions', 'desc'=> 'Permission for [Accept, Delete, Edit, Spam] Comments ']
                ]
            ],
        ];
        return $permisions;
    }
}
