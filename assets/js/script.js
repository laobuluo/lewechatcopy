jQuery(document).ready(function($) {
    let isVerified = false;
    const popup = $('#lewechatcopy-popup');
    const closeBtn = $('.lewechatcopy-close');
    const submitBtn = $('#lewechatcopy-submit');
    const codeInput = $('#lewechatcopy-code');
    
    // 禁用复制功能
    function disableCopy(event) {
        if (!isVerified) {
            event.preventDefault();
            showPopup();
            return false;
        }
    }

    // 显示弹窗
    function showPopup() {
        popup.fadeIn(300);
    }

    // 隐藏弹窗
    function hidePopup() {
        popup.fadeOut(300);
        codeInput.val('');
    }

    // 验证码验证
    function verifyCode() {
        const inputCode = codeInput.val().trim();
        if (inputCode === lewechatcopySettings.verificationCode) {
            isVerified = true;
            hidePopup();
            alert('验证成功！现在您可以复制内容了。');
        } else {
            alert('验证码错误，请重试！');
        }
    }

    // 根据设置类型添加复制限制
    if (lewechatcopySettings.copyType === 'all') {
        // 全站复制控制
        $(document).on('copy cut', disableCopy);
    } else {
        // 短代码复制控制
        $('.lewechatcopy-content').on('copy cut', function(e) {
            if (!isVerified) {
                e.preventDefault();
                showPopup();
                return false;
            }
        });
    }

    // 绑定事件
    closeBtn.on('click', hidePopup);
    submitBtn.on('click', verifyCode);
    
    // 按下回车键时触发验证
    codeInput.on('keypress', function(e) {
        if (e.which === 13) {
            verifyCode();
        }
    });

    // 点击弹窗外部时关闭弹窗
    $(window).on('click', function(e) {
        if ($(e.target).is(popup)) {
            hidePopup();
        }
    });
}); 