<?php namespace Stevebauman\Localization\Handlers\Translation;

use Illuminate\Events\Dispatcher;
use Stevebauman\Localization\Models\Translation;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class TranslationEventHandler extends BaseEventHandler implements TranslationEventHandlerInterface
{

    /**
     * {@inheritDoc}
     */
    public function subscribe(Dispatcher $dispatcher)
    {
        $dispatcher->listen('stevebauman.localization.translation.creating', __CLASS__ . '@creating');
        $dispatcher->listen('stevebauman.localization.translation.created', __CLASS__ . '@created');

        $dispatcher->listen('stevebauman.localization.translation.updating', __CLASS__ . '@updating');
        $dispatcher->listen('stevebauman.localization.translation.updated', __CLASS__ . '@updated');

        $dispatcher->listen('stevebauman.localization.translation.deleted', __CLASS__ . '@deleted');
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
    public function created(Translation $translation)
    {
        $this->flushCache($translation);
    }

    /**
     * {@inheritDoc}
     */
    public function updating(Translation $translation, array $data)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function updated(Translation $translation)
    {
        $this->flushCache($translation);
    }

    /**
     * {@inheritDoc}
     */
    public function deleted(Translation $translation)
    {
        $this->flushCache($translation);
    }

    /**
     * Flush the cache.
     *
     * @param  \Stevebauman\Localization\Models\Translation $translation
     * @return void
     */
    protected function flushCache(Translation $translation)
    {
        $this->app['cache']->forget('stevebauman.localization.translation.all');

        $this->app['cache']->forget('stevebauman.localization.translation.' . $translation->id);
    }

}
