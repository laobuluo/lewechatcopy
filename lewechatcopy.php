<?php
/**
 * Plugin Name: LeWeChatCopy
 * Plugin URI:  https://www.laojiang.me/6157.html
 * Description: WordPress关注公众号可复制插件，支持全站或指定内容的复制控制。（公众号：老蒋朋友圈）
 * Version: 1.0.0
 * Author: 老蒋和他的小伙伴
 * Author URI: https://www.laojiang.me
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lewechatcopy
 */

if (!defined('ABSPATH')) {
    exit;
}

// 定义插件常量
define('LEWECHATCOPY_VERSION', '1.0.0');
define('LEWECHATCOPY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LEWECHATCOPY_PLUGIN_URL', plugin_dir_url(__FILE__));

// 插件激活时的钩子
register_activation_hook(__FILE__, 'lewechatcopy_activate');

// 插件停用时的钩子
register_deactivation_hook(__FILE__, 'lewechatcopy_deactivate');

// 插件卸载时的钩子
register_uninstall_hook(__FILE__, 'lewechatcopy_uninstall');

// 激活插件时的操作
function lewechatcopy_activate() {
    // 设置默认选项
    $default_options = array(
        'enabled' => '0',
        'copy_type' => 'all',
        'verification_code' => '123456',
        'qrcode_url' => '',
        'verification_prompt' => '复制验证码'
    );
    
    add_option('lewechatcopy_options', $default_options);
}

// 停用插件时的操作
function lewechatcopy_deactivate() {
    // 清除可能存在的临时数据
}

// 卸载插件时的操作
function lewechatcopy_uninstall() {
    // 删除插件选项
    delete_option('lewechatcopy_options');
}

// 加载插件文件
require_once LEWECHATCOPY_PLUGIN_DIR . 'includes/admin-menu.php';
require_once LEWECHATCOPY_PLUGIN_DIR . 'includes/frontend.php'; 