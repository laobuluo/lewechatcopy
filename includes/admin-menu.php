<?php
if (!defined('ABSPATH')) {
    exit;
}

// 添加管理菜单
add_action('admin_menu', 'lewechatcopy_add_admin_menu');
add_action('admin_init', 'lewechatcopy_settings_init');
// 添加媒体上传脚本
add_action('admin_enqueue_scripts', 'lewechatcopy_admin_scripts');

function lewechatcopy_admin_scripts($hook) {
    // 只在插件设置页面加载脚本
    if ('settings_page_lewechatcopy' !== $hook) {
        return;
    }
    
    // 加载WordPress媒体上传脚本
    wp_enqueue_media();
}

function lewechatcopy_add_admin_menu() {
    add_options_page(
        '公众号关注复制设置',
        '公众号关注复制',
        'manage_options',
        'lewechatcopy',
        'lewechatcopy_options_page'
    );
}

function lewechatcopy_settings_init() {
    register_setting('lewechatcopy', 'lewechatcopy_options');

    add_settings_section(
        'lewechatcopy_section',
        '基本设置',
        'lewechatcopy_section_callback',
        'lewechatcopy'
    );

    add_settings_field(
        'enabled',
        '启用插件',
        'lewechatcopy_enabled_render',
        'lewechatcopy',
        'lewechatcopy_section'
    );

    add_settings_field(
        'copy_type',
        '复制控制类型',
        'lewechatcopy_copy_type_render',
        'lewechatcopy',
        'lewechatcopy_section'
    );

    add_settings_field(
        'verification_code',
        '验证码',
        'lewechatcopy_verification_code_render',
        'lewechatcopy',
        'lewechatcopy_section'
    );

    add_settings_field(
        'qrcode_url',
        '二维码图片',
        'lewechatcopy_qrcode_url_render',
        'lewechatcopy',
        'lewechatcopy_section'
    );

    add_settings_field(
        'verification_prompt',
        '验证提示词',
        'lewechatcopy_verification_prompt_render',
        'lewechatcopy',
        'lewechatcopy_section'
    );
}

function lewechatcopy_section_callback() {
    echo '配置公众号关注复制功能的相关选项';
}

function lewechatcopy_enabled_render() {
    $options = get_option('lewechatcopy_options');
    ?>
    <input type='checkbox' name='lewechatcopy_options[enabled]' <?php checked($options['enabled'], '1'); ?> value='1'>
    <?php
}

function lewechatcopy_copy_type_render() {
    $options = get_option('lewechatcopy_options');
    ?>
    <select name='lewechatcopy_options[copy_type]'>
        <option value='all' <?php selected($options['copy_type'], 'all'); ?>>全站复制控制</option>
        <option value='shortcode' <?php selected($options['copy_type'], 'shortcode'); ?>>短代码控制</option>
    </select>
    <?php
    echo '<p class="description">选择"短代码控制"时，使用 [lewechatcopy]内容[/lewechatcopy] 包裹需要控制的内容</p>';
}

function lewechatcopy_verification_code_render() {
    $options = get_option('lewechatcopy_options');
    ?>
    <input type='text' name='lewechatcopy_options[verification_code]' value='<?php echo esc_attr($options['verification_code']); ?>'>
    <?php
}

function lewechatcopy_qrcode_url_render() {
    $options = get_option('lewechatcopy_options');
    ?>
    <div class="qrcode-upload-container">
        <input type='text' id='qrcode_url' name='lewechatcopy_options[qrcode_url]' value='<?php echo esc_attr($options['qrcode_url']); ?>' style="width: 300px;">
        <input type="button" class="button" id="upload_qrcode_button" value="选择图片">
        <div id="qrcode_preview" style="margin-top: 10px;">
            <?php if (!empty($options['qrcode_url'])): ?>
                <img src="<?php echo esc_url($options['qrcode_url']); ?>" style="max-width: 200px;">
            <?php endif; ?>
        </div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#upload_qrcode_button').click(function(e) {
            e.preventDefault();
            
            var custom_uploader = wp.media({
                title: '选择二维码图片',
                button: {
                    text: '使用此图片'
                },
                multiple: false
            });

            custom_uploader.on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#qrcode_url').val(attachment.url);
                $('#qrcode_preview').html('<img src="' + attachment.url + '" style="max-width: 200px;">');
            });

            custom_uploader.open();
        });
    });
    </script>
    <?php
}

function lewechatcopy_verification_prompt_render() {
    $options = get_option('lewechatcopy_options');
    if (empty($options['verification_prompt'])) {
        $options['verification_prompt'] = '复制验证码';
    }
    ?>
    <input type='text' name='lewechatcopy_options[verification_prompt]' value='<?php echo esc_attr($options['verification_prompt']); ?>' style="width: 300px;">
    <?php
}

function lewechatcopy_options_page() {
    ?>
    <div class="wrap">
        <h1>公众号关注复制设置</h1>
        <p>WordPress 关注公众号验证可复制内容插件。<a href=" https://www.lezaiyun.com/926.html" target="_blank">插件介绍</a>（公众号：<span style="color: red;">老蒋朋友圈</span>）</p>
        <form action='options.php' method='post'>
            <?php
            settings_fields('lewechatcopy');
            do_settings_sections('lewechatcopy');
            submit_button();
            ?>
        </form>
    </div>
    <?php
} 