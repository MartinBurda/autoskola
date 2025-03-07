<?php
declare(strict_types=1);

namespace App\UI\Front\Home;

use Nette;
use App\Model\PageFacade;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    /** @var PageFacade */
    private PageFacade $pageFacade;

    /**
     * Constructor.
     *
     * @param PageFacade $pageFacade Central facade for retrieving page content from multiple tables.
     */
    public function __construct(PageFacade $pageFacade)
    {
        parent::__construct();
        $this->pageFacade = $pageFacade;
    }

    /**
     * Render the default homepage.
     *
     * Loads the hero, about, advantages, offerings, contact, course prices, and courses data.
     */
    public function renderDefault(): void
    {

        $this->template->hero = $this->pageFacade->getHeroSection();
        $this->template->about = $this->pageFacade->getAboutSection();
        $this->template->advantages = $this->pageFacade->getAdvantages();
        $this->template->offers = $this->pageFacade->getOfferings();
        $this->template->contact = $this->pageFacade->getContactInfo();
        $this->template->groupedPrices = $this->pageFacade->getGroupedCoursePrices();
        $this->template->courses = $this->pageFacade->getAllCourses();
    }

    /**
     * Render the course pricing ("Ceník") page.
     */
    public function renderCenik(): void
    {
        // Only the grouped course prices are needed here.
        $this->template->groupedPrices = $this->pageFacade->getGroupedCoursePrices();
    }

    public function renderDetail($id): void
    {
        // Fetch course record through PageFacade
        $course = $this->pageFacade->getCourseById((int)$id);
        if (!$course) {
            $this->error('Course not found');
        }
    
        // Fetch related other_services using the new facade method
        $otherServices = $this->pageFacade->getOtherServices((int)$id);
    
        $this->template->course = $course;
        $this->template->otherServices = $otherServices;
    }
}
