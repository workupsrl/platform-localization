<?php

namespace Stevebauman\Localization\Repositories\Locale;

use Validator;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;

/**
 * Class LocaleRepository
 * @package Stevebauman\Localization\Repositories\Locale
 */
class LocaleRepository implements LocaleRepositoryInterface
{
    use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

    /**
     * The Data handler.
     *
     * @var \Stevebauman\Localization\Handlers\Locale\LocaleDataHandlerInterface
     */
    protected $data;

    /**
     * The Eloquent localization model.
     *
     * @var string
     */
    protected $model;

    /**
     * Constructor.
     *
     * @param  \Illuminate\Container\Container $app
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->setContainer($app);

        $this->setDispatcher($app['events']);

        $this->data = $app['localization.locale.handler.data'];

        $this->setValidator($app['localization.locale.validator']);

        $this->setModel(get_class($app['Stevebauman\Translation\Models\Locale']));
    }

    /**
     * {@inheritDoc}
     */
    public function grid()
    {
        return $this
            ->createModel();
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        return $this->container['cache']->rememberForever('stevebauman.localization.locale.all', function () {
            return $this->createModel()->get();
        });
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->container['cache']->rememberForever('stevebauman.localization.locale.' . $id, function () use ($id) {
            return $this->createModel()->find($id);
        });
    }

    /**
     * Returns a non-cached locale
     *
     * @param $id
     * @return mixed
     */
    public function findNonCached($id)
    {
        return $this->createModel()->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function validForCreation(array $input)
    {
        return $this->validator->on('create')->validate($input);
    }

    /**
     * {@inheritDoc}
     */
    public function validForUpdate($id, array $input)
    {
        return $this->validator->on('update')->validate($input);
    }

    /**
     * {@inheritDoc}
     */
    public function store($id, array $input)
    {
        return !$id ? $this->create($input) : $this->update($id, $input);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $input)
    {
        // Create a new locale
        $locale = $this->createModel();

        // Fire the 'stevebauman.localization.locale.creating' event
        if ($this->fireEvent('stevebauman.localization.locale.creating', [$input]) === false) {
            return false;
        }

        // Prepare the submitted data
        $data = $this->data->prepare($input);

        // Validate the submitted data
        $messages = $this->validForCreation($data);

        // Check if the validation returned any errors
        if ($messages->isEmpty()) {
            // Save the locale
            $locale->fill($data)->save();

            // Fire the 'stevebauman.localization.locale.created' event
            $this->fireEvent('stevebauman.localization.locale.created', [$locale]);
        }

        return [$messages, $locale];
    }

    /**
     * {@inheritDoc}
     */
    public function update($id, array $input)
    {
        // Get the locale object
        $locale = $this->find($id);

        // Fire the 'stevebauman.localization.locale.updating' event
        if ($this->fireEvent('stevebauman.localization.locale.updating', [$locale, $input]) === false) {
            return false;
        }

        // Prepare the submitted data
        $data = $this->data->prepare($input);

        // Validate the submitted data
        $messages = $this->validForUpdate($locale, $data);

        // Check if the validation returned any errors
        if ($messages->isEmpty()) {
            // Update the locale
            $locale->fill($data)->save();

            // Fire the 'stevebauman.localization.locale.updated' event
            $this->fireEvent('stevebauman.localization.locale.updated', [$locale]);
        }

        return [$messages, $locale];
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id)
    {
        // Check if the locale exists
        if ($locale = $this->find($id)) {
            // Fire the 'stevebauman.localization.locale.deleted' event
            $this->fireEvent('stevebauman.localization.locale.deleted', [$locale]);

            // Delete the locale entry
            $locale->delete();

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function enable($id)
    {
        $this->validator->bypass();

        return $this->update($id, ['enabled' => true]);
    }

    /**
     * {@inheritDoc}
     */
    public function disable($id)
    {
        $this->validator->bypass();

        return $this->update($id, ['enabled' => false]);
    }

}
