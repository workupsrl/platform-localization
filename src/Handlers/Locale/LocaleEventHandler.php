<?php namespace Stevebauman\Localization\Handlers\Locale;

use Illuminate\Events\Dispatcher;
use Stevebauman\Localization\Models\Locale;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class LocaleEventHandler extends BaseEventHandler implements LocaleEventHandlerInterface
{

    /**
     * {@inheritDoc}
     */
    public function subscribe(Dispatcher $dispatcher)
    {
        $dispatcher->listen('stevebauman.localization.locale.creating', __CLASS__ . '@creating');
        $dispatcher->listen('stevebauman.localization.locale.created', __CLASS__ . '@created');

        $dispatcher->listen('stevebauman.localization.locale.updating', __CLASS__ . '@updating');
        $dispatcher->listen('stevebauman.localization.locale.updated', __CLASS__ . '@updated');

        $dispatcher->listen('stevebauman.localization.locale.deleted', __CLASS__ . '@deleted');
    }

    /**
     * {@inheritDoc}
     */
    public function creating(array $data)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function created(Locale $locale)
    {
        $this->flushCache($locale);
    }

    /**
     * {@inheritDoc}
     */
    public function updating(Locale $locale, array $data)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function updated(Locale $locale)
    {
        $this->flushCache($locale);
    }

    /**
     * {@inheritDoc}
     */
    public function deleted(Locale $locale)
    {
        $this->flushCache($locale);
    }

    /**
     * Flush the cache.
     *
     * @param  \Stevebauman\Localization\Models\Locale $locale
     * @return void
     */
    protected function flushCache(Locale $locale)
    {
        $this->app['cache']->forget('stevebauman.localization.locale.all');

        $this->app['cache']->forget('stevebauman.localization.locale.' . $locale->id);
    }

}
