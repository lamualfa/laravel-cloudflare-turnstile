<?php

return [
    'missing-input-secret' => 'مفتاح التحقق السري لـ Turnstile غير مكون. يرجى فحص إعدادات services.turnstile.secret.',
    'invalid-input-secret' => 'مفتاح التحقق السري لـ Turnstile غير صالح أو غير موجود. يرجى التحقق من المفتاح السري في لوحة تحكم Cloudflare.',
    'missing-input-response' => 'لم يتم استلام أي رد من Turnstile. يرجى التأكد من أن النموذج يحتوي على عنصر Turnstile.',
    'invalid-input-response' => 'رد Turnstile غير صالح أو انتهت صلاحيته. يرجى تحديث الصفحة والمحاولة مرة أخرى.',
    'bad-request' => 'طلب التحقق من Turnstile غير صحيح. يرجى المحاولة مرة أخرى.',
    'timeout-or-duplicate' => 'انتهت صلاحية رد Turnstile أو تم استخدامه من قبل. يرجى تحديث العنصر.',
    'internal-error' => 'خدمة Cloudflare Turnstile تواجه مشاكل حالياً. يرجى المحاولة مرة أخرى لاحقاً.',
    'unexpected' => 'فشل التحقق من Turnstile. يرجى فحص الإعدادات والمحاولة مرة أخرى.',
];
