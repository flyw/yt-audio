<?php

namespace App\Listeners\Backend\Auth\Role;

use Illuminate\Support\Facades\Auth;
use Joydata\Logs\Services\LogService;
use App\Events\Backend\Auth\Role\RoleCreated;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\Events\Backend\Auth\Role\RoleUpdated;

/**
 * Class RoleEventListener.
 */
class RoleEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.role_create',['name'=>$event->role->name]);
        //添加系统日志
        LogService::info($message);

        \Log::info('Role Created');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.role_updated',['name'=>$event->role->name]);
        //添加系统日志
        LogService::info($message);

        \Log::info('Role Updated');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.role_deleted',['name'=>$event->role->name]);
        //添加系统日志
        LogService::critical($message);

        \Log::info('Role Deleted');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            RoleCreated::class,
            'App\Listeners\Backend\Auth\Role\RoleEventListener@onCreated'
        );

        $events->listen(
            RoleUpdated::class,
            'App\Listeners\Backend\Auth\Role\RoleEventListener@onUpdated'
        );

        $events->listen(
            RoleDeleted::class,
            'App\Listeners\Backend\Auth\Role\RoleEventListener@onDeleted'
        );
    }
}
