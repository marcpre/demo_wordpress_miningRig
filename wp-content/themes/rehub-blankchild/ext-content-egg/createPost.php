<?php

namespace ContentEgg\application\admin;

use ContentEgg\application\components\ModuleManager;
use ContentEgg\application\helpers\InputHelper;
use ContentEgg\application\helpers\TextHelper;
use ContentEgg\application\components\ContentManager;
// use ContentEgg\application\components\ContentProduct;
// use ContentEgg\application\components\ContentCoupon;
// use ContentEgg\application\components\ExtraData;
// use ContentEgg\application\Plugin;

/**
 * EggMetabox class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link http://www.keywordrush.com/
 * @copyright Copyright &copy; 2016 keywordrush.com
 */
class CreatePost {

    public function __construct()
    {
        \add_action('save_post', array($this, 'saveMeta'));
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function saveMeta($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (!isset($_POST['contentegg_nonce']))
            return;

        /*
         * why shouldn't i save metadata when its a revision?
         *
         * Apparently *_post_meta functions will automatically change
         * to parent post id if passed revision post id. So you might modify original post,
         * thinking you are modifying revision.
         * 
          if (\wp_is_post_revision($post_id))
          return;
         * 
         */

        \check_admin_referer('contentegg_metabox', 'contentegg_nonce');

        // Check the user's permissions.
        if ($_POST['post_type'] == 'page')
        {
            if (!current_user_can('edit_page', $post_id))
                return;
        } else
        {
            if (!current_user_can('edit_post', $post_id))
                return;
        }

        // need stripslashes? wp bug with revision post type?
        if (\wp_is_post_revision($post_id))
            $stripslashes = false;
        else
            $stripslashes = true;

        // keywords for automatic updates
        $keywords = InputHelper::post('cegg_updateKeywords', array(), $stripslashes);
        $update_params = InputHelper::post('cegg_updateParams', array(), $stripslashes);
        foreach ($keywords as $module_id => $keyword)
        {
            if (!ModuleManager::getInstance()->moduleExists($module_id) || !ModuleManager::getInstance()->isModuleActive($module_id))
                continue;

            $module = ModuleManager::getInstance()->factory($module_id);
            if (!$module->isAffiliateParser())
                continue;

            $keyword = \sanitize_text_field($keyword);
            if ($keyword)
            {
                \update_post_meta($post_id, ContentManager::META_PREFIX_KEYWORD . $module_id, $keyword);
                if (isset($update_params[$module_id]))
                {
                    \update_post_meta($post_id, ContentManager::META_PREFIX_UPDATE_PARAMS . $module_id, json_decode($update_params[$module_id], true));
                }
            } else
            {
                \delete_post_meta($post_id, ContentManager::META_PREFIX_KEYWORD . $module_id);
                \delete_post_meta($post_id, ContentManager::META_PREFIX_UPDATE_PARAMS . $module_id);
            }
        }

        // save content data
        $content = InputHelper::post('cegg_data', array(), $stripslashes);
        if (!is_array($content))
            return;

        $i = 0;
        foreach ($content as $module_id => $data)
        {
            $i++;
            if (!ModuleManager::getInstance()->moduleExists($module_id) || !ModuleManager::getInstance()->isModuleActive($module_id))
                continue;

            $data = json_decode($data, true);
            $data = $this->dataPrepare($data);
            if ($i == count($content))
                $last_iteration = true;
            else
                $last_iteration = false;
            ContentManager::saveData($data, $module_id, $post_id, $last_iteration);
        }
    }

    private function dataPrepare($data)
    {
        if (!is_array($data))
            return array();
        foreach ($data as $key => $d)
        {
            if ($key == 'description')
                $data[$key] = TextHelper::nl2br($d);
        }
        return $data;
    }

}
