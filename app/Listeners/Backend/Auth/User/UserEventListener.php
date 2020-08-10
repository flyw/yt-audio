<?php

namespace App\Listeners\Backend\Auth\User;

use Illuminate\Support\Facades\Auth;
use Joydata\Logs\Services\LogService;
use App\Events\Backend\Auth\User\UserCreated;
use App\Events\Backend\Auth\User\UserDeleted;
use App\Events\Backend\Auth\User\UserUpdated;
use App\Events\Backend\Auth\User\UserRestored;
use App\Events\Backend\Auth\User\UserConfirmed;
use App\Events\Backend\Auth\User\UserDeactivated;
use App\Events\Backend\Auth\User\UserPasswordChanged;
use App\Events\Backend\Auth\User\UserPermanentlyDeleted;
use App\Events\Backend\Auth\User\UserReactivated;
use App\Events\Backend\Auth\User\UserSocialDeleted;
use App\Events\Backend\Auth\User\UserUnconfirmed;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @param $event
     */
    public function onCreated($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_create',['name'=>$event->user->full_name]);
        //添加系统日志
        LogService::info($message);

        \Log::info('User Created');
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_updated', ['name' => $event->user->full_name]);
        //添加系统日志
        LogService::info($message);

        \Log::info('User Updated');
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_deleted',['name'=> $event->user->full_name]);
        //添加系统日志
        LogService::critical($message);

        \Log::info('User Deleted');
    }

    /**
     * @param $event
     */
    public function onConfirmed($event)
    {
        logger('User Confirmed');
    }

    /**
     * @param $event
     */
    public function onUnconfirmed($event)
    {
        logger('User Unconfirmed');
    }

    /**
     * @param $event
     */
    public function onPasswordChanged($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_password_changed',['name'=>$event->user->full_name]);
        //添加系统日志
        LogService::info($message);

        \Log::info('User Password Changed');
    }

    /**
     * @param $event
     */
    public function onDeactivated($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_deactivated',['name'=>$event->user->full_name]);
        //添加系统日志
        LogService::info($message);
        \Log::info('User Deactivated');
    }

    /**
     * @param $event
     */
    public function onReactivated($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_reactivated',['name'=>$event->user->full_name]);
        //添加系统日志
        LogService::info($message);
        \Log::info('User Reactivated');
    }

    /**
     * @param $event
     */
    public function onSocialDeleted($event)
    {
        logger('User Social Deleted');
    }

    /**
     * @param $event
     */
    public function onPermanentlyDeleted($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_permanently_deleted',['name'=>$event->user->full_name]);
        //添加系统日志
        LogService::critical($message);

        \Log::info('User Permanently Deleted');
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        $loginUser = Auth::user();
        $message = $loginUser->full_name . __('log.user_restored',['name'=>$event->user->full_name]);
        //添加系统日志
        LogService::critical($message);

        \Log::info('User Restored');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserCreated::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onCreated'
        );

        $events->listen(
            UserUpdated::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onUpdated'
        );

        $events->listen(
            UserDeleted::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onDeleted'
        );

        $events->listen(
            UserConfirmed::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onConfirmed'
        );

        $events->listen(
            UserUnconfirmed::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onUnconfirmed'
        );

        $events->listen(
            UserPasswordChanged::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onPasswordChanged'
        );

        $events->listen(
            UserDeactivated::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onDeactivated'
        );

        $events->listen(
            UserReactivated::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onReactivated'
        );

        $events->listen(
            UserSocialDeleted::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onSocialDeleted'
        );

        $events->listen(
            UserPermanentlyDeleted::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onPermanentlyDeleted'
        );

        $events->listen(
            UserRestored::class,
            'App\Listeners\Backend\Auth\User\UserEventListener@onRestored'
        );
    }
}
