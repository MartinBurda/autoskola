<?php
declare(strict_types=1);

namespace App\UI\Admin\Dashboard;

use Nette\Application\UI\Presenter;
use Nette\Application\UI\Form;
use App\Model\PageFacade;
use App\Model\UserFacade;
use App\Model\EmailFacade;

/**
 * DashboardPresenter provides separate admin pages for editing each section.
 */
final class DashboardPresenter extends Presenter
{
    /** @var PageFacade */
    private PageFacade $pageFacade;
    private UserFacade $userFacade;
    private EmailFacade $EmailFacade;

    /**
     * Constructor.
     *
     * @param PageFacade $pageFacade Central facade for retrieving and updating page content.
     */
    public function __construct(PageFacade $pageFacade, UserFacade $userFacade, EmailFacade $EmailFacade)
    {
        parent::__construct();
        $this->pageFacade = $pageFacade;
        $this->userFacade = $userFacade;
        $this->EmailFacade = $EmailFacade;
    }

    protected function startup(): void
    {
        parent::startup();
        if (!$this->user->isLoggedIn() || !$this->user->isInRole('admin')) {
            $this->flashMessage('Sem nemáš přístup🚫', 'danger');
            $this->redirect(':Front:Home:default');
        }
    }

    /**
     * Default dashboard view with navigation buttons.
     */
    public function renderDefault(): void
    {
    }
    public function renderUser(): void
    {
        $this->template->userData = $this->userFacade->getAllUsers();
    }

    /**
     * Render the Hero Section edit page.
     */
    public function renderHero(): void
    {
        $this->template->hero = $this->pageFacade->getHeroSection();
        $this->template->setFile(__DIR__ . '/Templates/hero.latte');
    }

    /**
     * Render the About Section edit page.
     */
    public function renderAbout(): void
    {
        $this->template->about = $this->pageFacade->getAboutSection();
        $this->template->setFile(__DIR__ . '/Templates/about.latte');
    }

    /**
     * Render the Contact Section edit page.
     */
    public function renderContact(): void
    {
        $this->template->contact = $this->pageFacade->getContactInfo();
        $this->template->setFile(__DIR__ . '/Templates/contact.latte');
    }

    public function renderRegistrations(): void
    {
        $registrations = $this->EmailFacade->getRegistrations();
        $this->template->registrations = $registrations;
    }

    public function renderAdvantages(): void
    {
        $id = $this->getParameter('id');
        if ($id) {
            $advantage = $this->pageFacade->getAdvantages()->get((int)$id);
       
            $this->template->advantage = $advantage;
        } else {
            $this->template->advantages = $this->pageFacade->getAdvantages();
        }
        $this->template->setFile(__DIR__ . '/Templates/advantages.latte');
    }

    /**
     * Render the Offerings edit page.
     */
    public function renderOfferings(): void
    {
        $id = $this->getParameter('id');
        if ($id) {
            $this->template->offering = $this->pageFacade->getOfferById((int)$id);
        }
        $this->template->offerings = $this->pageFacade->getOfferings();
        $this->template->setFile(__DIR__ . '/Templates/offerings.latte');
    }

    /**
     * Render the Course Prices ("Ceník") edit page.
     */
    public function renderPrices(): void
    {
        $id = $this->getParameter('id');
        if ($id) {
            $this->template->price = $this->pageFacade->getPriceById((int)$id);
        }
        $this->template->groupedPrices = $this->pageFacade->getGroupedCoursePrices();
        $this->template->setFile(__DIR__ . '/Templates/prices.latte');
    }

    /**
     * Render the Courses edit page.
     */
    public function renderCourses(): void
    {
        $this->template->courses = $this->pageFacade->getAllCourses();
        $this->template->setFile(__DIR__ . '/Templates/courses.latte');
    }

    /* ------------------- Form Components ------------------- */

    /**
     * Helper method to build an edit form.
     *
     * @param object   $entity              The entity providing default values.
     * @param array    $fields              Array of field definitions.
     *                                    Example:
     *                                    [
     *                                      'heading' => ['type' => 'text', 'label' => 'Nadpis:', 'required' => true],
     *                                      'subheading'  => ['type' => 'textArea', 'label' => 'Podnadpis:', 'required' => true],
     *                                    ]
     * @param callable $updateCallback      Callback to update the entity: fn($id, $values)
     * @param string   $flashMessage        The flash message on success.
     * @param string   $redirectDestination The redirect destination.
     * @return Form
     */
    private function createEditForm(
        object $entity,
        array $fields,
        callable $updateCallback,
        string $flashMessage,
        string $redirectDestination
    ): Form {
        $form = new Form;
        foreach ($fields as $name => $config) {
            $method = 'add' . ucfirst($config['type']);
            $field = $form->$method($name, $config['label'] ?? '');
            
            // Set the default value from config or the entity
            if (array_key_exists('default', $config)) {
                $field->setDefaultValue($config['default']);
            } else {
                $field->setDefaultValue($entity->{$name});
            }
            
            // If an HTML type is provided (e.g. 'date'), set it
            if (isset($config['htmlType'])) {
                $field->setHtmlType($config['htmlType']);
            }
            
            if (!empty($config['required'])) {
                $field->setRequired();
            }
            
            // Add Bootstrap styling for most fields (skip hidden fields)
            if ($config['type'] !== 'hidden') {
                $field->getControlPrototype()->addClass('form-control');
            }
        }
        
        // Add a submit button with Bootstrap classes
        $form->addSubmit('save', 'Uložit')
             ->getControlPrototype()->addClass('btn btn-primary');
        
        $form->onSuccess[] = function (Form $form, $values) use ($entity, $updateCallback, $flashMessage, $redirectDestination): void {
            $updateCallback($entity->id, (array)$values);
            $this->flashMessage($flashMessage, 'success');
            $this->redirect($redirectDestination);
        };
    
        return $form;
    }

    /**
     * Create a form to edit an Offering.
     *
     * @return Form
     */
    public function createComponentOfferForm(): Form
    {
        $id = (int)$this->getParameter('id');
        if (!$id) {
            $this->error('Nabídka nenalezena');
        }
        $offer = $this->pageFacade->getOfferById($id);
        if (!$offer) {
            $this->error('Nabídka nenalezena');
        }
        $fields = [
            'label'   => ['type' => 'text',     'label' => 'Označení:', 'required' => true],
            'content' => ['type' => 'textArea', 'label' => 'Obsah:',    'required' => true],
        ];

        $form = $this->createEditForm(
            $offer,
            $fields,
            function ($submittedId, $values) {
                $offerId = (int)$values['id'];
                $this->pageFacade->updateOffer($offerId, (array)$values);
            },
            'Nabídka byla úspěšně aktualizována.',
            'Dashboard:offerings'
        );

        // Add a hidden field for the offer ID
        $form->addHidden('id')->setDefaultValue($offer->id);

        // Set the form action so that the ID remains in the URL upon submission
        $form->setAction($this->link('Dashboard:offerings', ['id' => $offer->id]));

        return $form;
    }
    
    /**
     * Create a form to edit the Hero Section.
     *
     * @return Form
     */
    public function createComponentHeroForm(): Form
    {
        $hero = $this->pageFacade->getHeroSection();
        $fields = [
            'heading'     => ['type' => 'text',     'label' => 'Nadpis:',       'required' => true],
            'subheading'  => ['type' => 'textArea', 'label' => 'Podnadpis:',    'required' => true],
            'button_text' => ['type' => 'text',     'label' => 'Text tlačítka:', 'required' => true],
            'button_link' => ['type' => 'text',     'label' => 'Odkaz tlačítka:', 'required' => true],
        ];

        return $this->createEditForm(
            $hero,
            $fields,
            fn($id, $values) => $this->pageFacade->updateHeroSection($id, $values),
            'Hlavní sekce byla úspěšně aktualizována.',
            'Dashboard:hero'
        );
    }

    /**
     * Create a form to edit the About Section.
     *
     * @return Form
     */
    public function createComponentAboutForm(): Form
    {
        $about = $this->pageFacade->getAboutSection();
        $fields = [
            'heading'  => ['type' => 'text',     'label' => 'Nadpis:', 'required' => true],
            'alt_text' => ['type' => 'text',     'label' => 'Alternativní text:', 'required' => true],
            'content'  => ['type' => 'textArea', 'label' => 'Obsah:',  'required' => true],
        ];

        $form = $this->createEditForm(
            $about,
            $fields,
            function ($id, $values) use ($about) {
                /** @var \Nette\Http\FileUpload $image */
                $image = $values['image'];
                $values['image'] = \App\Utils\ImageUploader::uploadImage($image, 'uploads/about', $about->image);
                $this->pageFacade->updateAboutSection($id, $values);
            },
            'Sekce O nás byla úspěšně aktualizována.',
            'Dashboard:about'
        );

        $form->addHidden('id')->setDefaultValue($about->id);

        if ($form->getComponent('image', false)) {
            $form->removeComponent($form['image']);
        }
        $form->addUpload('image', 'Obrázek:')
             ->setHtmlAttribute('class', 'form-control');

        $form->getElementPrototype()->enctype = 'multipart/form-data';

        $form->setAction($this->link('Dashboard:about', ['id' => $about->id]));

        return $form;
    }

    /**
     * Create a form to edit the Contact Section.
     *
     * @return Form
     */
    public function createComponentContactForm(): Form
    {
        $contact = $this->pageFacade->getContactInfo();
        $fields = [
            'name'      => ['type' => 'text',     'label' => 'Jméno:',      'required' => true],
            'address'   => ['type' => 'text',     'label' => 'Adresa:',     'required' => true],
            'ico'       => ['type' => 'text',     'label' => 'IČO:',        'required' => true],
            'phone'     => ['type' => 'text',     'label' => 'Telefon:',    'required' => true],
            'email'     => ['type' => 'text',     'label' => 'Email:',      'required' => true],
            'map_embed' => ['type' => 'textArea', 'label' => 'Kód vložení mapy:', 'required' => true],
        ];

        return $this->createEditForm(
            $contact,
            $fields,
            fn($id, $values) => $this->pageFacade->updateContactInfo($id, $values),
            'Kontaktní informace byly úspěšně aktualizovány.',
            'Dashboard:contact'
        );
    }

    /**
     * Create a form to edit an Advantage.
     *
     * When an 'id' parameter is provided (via the URL), the form edits that specific advantage.
     *
     * @return Form
     */
    public function createComponentAdvantageForm(): Form
    {
        $id = (int)$this->getParameter('id');
        $advantage = $this->pageFacade->getAdvantages()->get($id);
        if (!$advantage) {
            $this->error('Výhoda nenalezena');
        }
        $fields = [
            'icon'        => ['type' => 'text',     'label' => 'Ikona:',       'required' => true],
            'title'       => ['type' => 'text',     'label' => 'Název:',      'required' => true],
            'description' => ['type' => 'textArea', 'label' => 'Popis:',       'required' => true],
        ];
    
        $form = $this->createEditForm(
            $advantage,
            $fields,
            fn($id, $values) => $this->pageFacade->updateAdvantage($id, $values),
            'Výhoda byla úspěšně aktualizována.',
            'Dashboard:advantages'
        );
    
        $form->addHidden('id')->setDefaultValue($advantage->id);
    
        $form->setAction($this->link('Dashboard:advantages', ['id' => $advantage->id]));
    
        return $form;
    }

    /**
     * Create a form component to edit a single Price row.
     *
     * The form loads a price by its id (passed as a URL parameter) and updates it on submission.
     *
     * @return Form
     */
    public function createComponentPriceForm(): Form
    {
        $id = (int)$this->getParameter('id');
        $price = $this->pageFacade->getPriceById($id);
        if (!$price) {
            $this->error('Cena nenalezena');
        }
        $fields = [
            'item'        => ['type' => 'text',     'label' => 'Položka:',    'required' => true],
            'price'       => ['type' => 'text',     'label' => 'Cena:',       'required' => true],
            'description' => ['type' => 'textArea', 'label' => 'Popis:'],
            'section'     => ['type' => 'hidden',   'label' => ''],
        ];
    
        $form = $this->createEditForm(
            $price,
            $fields,
            fn($id, $values) => $this->pageFacade->updatePrice($id, $values),
            'Cena byla úspěšně aktualizována.',
            'Dashboard:prices'
        );
        
        $form->addHidden('id')->setDefaultValue($price->id);
        
        $form->setAction($this->link('Dashboard:prices', ['id' => $price->id]));
        
        return $form;
    }

    /**
     * Create a form to edit a Course.
     *
     * @return Form
     */
    public function createComponentCourseForm(): Form
    {
        $id = $this->getParameter('id');
        if (!$id) {
            $this->error('Kurz nenalezen');
        }
        $course = $this->pageFacade->getCourseById((int)$id);
        if (!$course) {
            $this->error('Kurz nenalezen');
        }
        
        $fields = [
            'name'        => ['type' => 'text',     'label' => 'Název kurzu:', 'required' => true],
            'description' => ['type' => 'textArea', 'label' => 'Popis:',        'required' => true],
            'price'       => ['type' => 'text',     'label' => 'Cena:',         'required' => true],
            'location'    => ['type' => 'text',     'label' => 'Adresa:',       'required' => true],
            'start_date'  => ['type' => 'text',     'label' => 'Začíná:',       'required' => true, 'htmlType' => 'date'],
        ];
    
        $form = $this->createEditForm(
            $course,
            $fields,
            function ($submittedId, $values) use ($course) {
                /** @var \Nette\Http\FileUpload $image */
                $image = $values['image'];
                $values['image'] = \App\Utils\ImageUploader::uploadImage($image, 'uploads/courses', $course->image);
                $values['show_ribbon'] = $values['show_ribbon'];
                $courseId = (int)$values['id'];
                $this->pageFacade->updateCourse($courseId, (array)$values);
            },
            'Kurz byl úspěšně aktualizován.',
            'Dashboard:courses'
        );
    
        $form->addHidden('id')->setDefaultValue($course->id);
    
        if ($course->start_date instanceof \DateTimeInterface) {
            $form->getComponent('start_date')->setDefaultValue($course->start_date->format('Y-m-d'));
        }
    
        if ($form->getComponent('image', false)) {
            $form->removeComponent($form['image']);
        }
        $form->addUpload('image', 'Obrázek:')
             ->setHtmlAttribute('class', 'form-control');
    
        $form->addCheckbox('show_ribbon', 'Zobrazit ribbon')
             ->setDefaultValue($course->show_ribbon ?? true);
    
        $form->getElementPrototype()->enctype = 'multipart/form-data';
    
        if ($form->getComponent('save', false)) {
            $form->removeComponent($form['save']);
        }
        $form->addSubmit('save', 'Uložit')
             ->getControlPrototype()->addClass('btn btn-primary');
    
        $form->setAction($this->link('Dashboard:courses', ['id' => $course->id]));
    
        return $form;
    }

    public function actionDeleteUser(int $id): void
    {
        if ($this->user->getIdentity()->id === $id) {
            $this->flashMessage('Nemůžeš odstranit sebe.', 'danger');
            $this->redirect('user');
        }
        $this->userFacade->deleteUser($id);
        $this->flashMessage('Uživatel byl úspěšně odstraněn.', 'success');
        $this->redirect('user');
    } 
}