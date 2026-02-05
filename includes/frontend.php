<?php
if (!defined('ABSPATH')) {
    exit;
}

// 注册短代码
add_shortcode('lewechatcopy', 'lewechatcopy_shortcode');

// 添加前端脚本和样式
add_action('wp_enqueue_scripts', 'lewechatcopy_enqueue_scripts');

function lewechatcopy_enqueue_scripts() {
    $options = get_option('lewechatcopy_options');
    
    if ($options['enabled'] !== '1') {
        return;
    }

    wp_enqueue_style(
        'lewechatcopy-style',
        LEWECHATCOPY_PLUGIN_URL . 'assets/css/style.css',
        array(),
        LEWECHATCOPY_VERSION
    );

    wp_enqueue_script(
        'lewechatcopy-script',
        LEWECHATCOPY_PLUGIN_URL . 'assets/js/script.js',
        array('jquery'),
        LEWECHATCOPY_VERSION,
        true
    );

    wp_localize_script('lewechatcopy-script', 'lewechatcopySettings', array(
        'copyType' => $options['copy_type'],
        'verificationCode' => $options['verification_code'],
        'qrcodeUrl' => $options['qrcode_url'],
        'verificationPrompt' => $options['verification_prompt']
    ));
}

// 短代码处理函数
function lewechatcopy_shortcode($atts, $content = null) {
    $options = get_option('lewechatcopy_options');
    
    if ($options['enabled'] !== '1') {
        return $content;
    }

    return '<div class="lewechatcopy-content">' . do_shortcode($content) . '</div>';
}

// 添加HTML到页面底部
add_action('wp_footer', 'lewechatcopy_add_popup_html');

function lewechatcopy_add_popup_html() {
    $options = get_option('lewechatcopy_options');
    
    if ($options['enabled'] !== '1') {
        return;
    }

    if (empty($options['verification_prompt'])) {
        $options['verification_prompt'] = '复制验证码';
    }
    ?>
    <div id="lewechatcopy-popup" style="display: none;">
        <div class="lewechatcopy-popup-content">
            <span class="lewechatcopy-close">&times;</span>
            <div class="lewechatcopy-qrcode">
                <img src="<?php echo esc_url($options['qrcode_url']); ?>" alt="公众号二维码">
            </div>
            <div class="lewechatcopy-title">请扫码关注微信公众号</div>
            <div class="lewechatcopy-subtitle">发送"<span class="verification-keyword"><?php echo esc_html($options['verification_prompt']); ?></span>"获取验证码</div>
            <div class="lewechatcopy-verify">
                <input type="text" id="lewechatcopy-code" placeholder="公众号验证码">
                <button id="lewechatcopy-submit">验证阅读</button>
            </div>
        </div>
    </div>
    <?php
} 