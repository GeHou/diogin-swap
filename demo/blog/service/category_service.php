<?php

use swap\service_except;

class category_service {
    public static function delete($category_id) {
        $category = category_model::get_by_id($category_id);
        if ($category !== null) {
            if (!$category->can_be_deleted()) {
                throw new service_except('默认分类不能删除');
            }
            category_model::del_by_id($category_id);
            if ($category->has_posts()) {
                post_model::set(array('category_id' => category_model::default_id), array('category_id' => $category_id));
                category_model::inc_by_id(array('post_count' => $category->post_count), category_model::default_id);
            }
        }
    }
}
