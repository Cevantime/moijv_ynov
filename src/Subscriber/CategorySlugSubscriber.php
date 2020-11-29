<?php

namespace App\Subscriber;

use App\Entity\Category;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugSubscriber
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * CategorySlugSubscriber constructor.
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $category = $args->getEntity();

        if(! ($category instanceof Category)) {
            return;
        }

        if($category->getSlug() == null) {
            $category->setSlug($this->slugger->slug($category->getName()));
        }
    }
}
