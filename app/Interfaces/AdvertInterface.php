<?php

namespace App\Interfaces;

interface AdvertInterface{
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT';
    const STATUS_PAYMENT_FAILED = 'PAYMENT_FAILED';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_DECLINED = 'DECLINED';
    const STATUS_PENDING_APPROVAL = 'PENDING_APPROVAL';
    const STATUS_PENDING_REAPPROVAL = 'PENDING_REAPPROVAL';

    const UPLOAD_DIR = 'ads';

    const USER_FETCH_LIMIT = 15;
}
