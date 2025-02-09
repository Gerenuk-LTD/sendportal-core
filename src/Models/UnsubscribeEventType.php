<?php

namespace Sendportal\Base\Models;

class UnsubscribeEventType extends BaseModel
{
    protected $table = 'sendportal_unsubscribe_event_types';

    public const BOUNCE = 1;
    public const COMPLAINT = 2;
    public const MANUAL_BY_ADMIN = 3;
    public const MANUAL_BY_SUBSCRIBER = 4;

    public static array $types = [
        1 => 'Bounced',
        2 => 'Complained',
        3 => 'Manual by Admin',
        4 => 'Manual by Subscriber',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the unsubscribe type by ID
     *
     * @param int $id
     * @return mixed
     */
    public static function findById(int $id): string
    {
        return \Arr::get(static::$types, $id);
    }
}
