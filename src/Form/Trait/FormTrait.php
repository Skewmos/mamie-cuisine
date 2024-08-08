<?php
namespace App\Form\Trait;

use App\Entity\Recipe;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait FormTrait {
    public function generateSlug(PreSubmitEvent $event, string $string): void
    {
        $data = $event->getData();
        if (empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($string));
            $event->setData($data);
        }
    }

    public function addTime(PostSubmitEvent $event, string $className): void
    {
        $data = $event->getData();
        if (!($data instanceof $className)) {
            return;
        }
        $data->setUpdatedAt(new \DateTimeImmutable());

        if (!$data->getId()) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
    }
}