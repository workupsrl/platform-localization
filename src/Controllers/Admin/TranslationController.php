<?php

namespace Stevebauman\Localization\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Stevebauman\Localization\Repositories\Locale\LocaleRepositoryInterface;
use Stevebauman\Localization\Repositories\Translation\TranslationRepositoryInterface;

/**
 * Class TranslationController
 * @package Stevebauman\Localization\Controllers\Admin
 */
class TranslationController extends AdminController
{
	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

    /**
     * The Locale repository instance
     *
     * @var LocaleRepositoryInterface
     */
    protected $locales;

	/**
	 * The Translation repository instance
	 *
	 * @var \Stevebauman\Localization\Repositories\Translation\TranslationRepositoryInterface
	 */
	protected $translations;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

    /**
     * @param LocaleRepositoryInterface $locales
     * @param TranslationRepositoryInterface $translations
     */
	public function __construct(LocaleRepositoryInterface $locales, TranslationRepositoryInterface $translations)
	{
		parent::__construct();

        $this->locales = $locales;
		$this->translations = $translations;
	}

	/**
	 * Display a listing of translation.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index($localeId)
	{
        $locale = $this->locales->find($localeId);

        if( ! $locale) return redirect(route('admin.localization.locales.index'));

		return view('stevebauman/localization::translations.index', compact('locale'));
	}

	/**
	 * Datasource for the translation Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid($localeId)
	{
		$data = $this->translations->grid($localeId);

		$columns = [
			'*',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element) use ($localeId)
		{
			$element->edit_uri = route('admin.localization.locales.translations.edit', array($localeId, $element->id));

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new translation.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create($localeId)
	{
        $locale = $this->locales->find($localeId);

        if( ! $locale) return redirect(route('admin.localization.locales.index'));

		return $this->showForm('create', $locale);
	}

	/**
	 * Handle posting of the form for creating new translation.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating translation.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($localeId, $id)
	{
        $locale = $this->locales->find($localeId);

        if( ! $locale) return redirect(route('admin.localization.locales.index'));

		return $this->showForm('update', $locale, $id);
	}

	/**
	 * Handle posting of the form for updating translation.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified translation.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->translations->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("stevebauman/localization::translations/message.{$type}.delete")
		);

		return redirect()->route('admin.stevebauman.localization.translations.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->translations->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $locale, $id = null)
	{
		// Do we have a translation identifier?
		if (isset($id))
		{
			if ( ! $translation = $this->translations->find($id))
			{
				$this->alerts->error(trans('stevebauman/localization::translations/message.not_found', compact('id')));

				return redirect()->route('admin.stevebauman.localization.translations.all');
			}
		}
		else
		{
			$translation = $this->translations->createModel();
		}

        $translations = $translation->getTranslations();

		// Show the page
		return view('stevebauman/localization::translations.form', compact('mode', 'translation', 'translations', 'locale'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the translation
		list($messages) = $this->translations->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("stevebauman/localization::translations/message.success.{$mode}"));

			return redirect()->route('admin.stevebauman.localization.translations.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
