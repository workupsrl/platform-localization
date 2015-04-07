<?php namespace Stevebauman\Localization\Repositories\Translation;

use Validator;
use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;
use Stevebauman\Localization\Models\Translation;

class TranslationRepository implements TranslationRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Stevebauman\Localization\Handlers\DataHandlerInterface
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
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['localization.translation.handler.data'];

		$this->setValidator($app['localization.translation.validator']);

		$this->setModel(get_class($app['Stevebauman\Translation\Models\LocaleTranslation']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid($localeId)
	{
		return $this
			->createModel()
            ->where('locale_id', $localeId);
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('stevebauman.localization.translation.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('stevebauman.localization.translation.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
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
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new translation
		$translation = $this->createModel();

		// Fire the 'stevebauman.localization.translation.creating' event
		if ($this->fireEvent('stevebauman.localization.translation.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the translation
			$translation->fill($data)->save();

			// Fire the 'stevebauman.localization.translation.created' event
			$this->fireEvent('stevebauman.localization.translation.created', [ $translation ]);
		}

		return [ $messages, $translation ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the translation object
		$translation = $this->find($id);

		// Fire the 'stevebauman.localization.translation.updating' event
		if ($this->fireEvent('stevebauman.localization.translation.updating', [ $translation, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($translation, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the translation
			$translation->fill($data)->save();

			// Fire the 'stevebauman.localization.translation.updated' event
			$this->fireEvent('stevebauman.localization.translation.updated', [ $translation ]);
		}

		return [ $messages, $translation ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the translation exists
		if ($translation = $this->find($id))
		{
			// Fire the 'stevebauman.localization.translation.deleted' event
			$this->fireEvent('stevebauman.localization.translation.deleted', [ $translation ]);

			// Delete the translation entry
			$translation->delete();

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

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
