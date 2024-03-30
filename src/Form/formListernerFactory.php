<?php

namespace App\Form;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class formListernerFactory
{

    public function __construct(private SluggerInterface $slugger){
        
    }

    public function autoSlug(string $flied): callable
    {

        return function (PreSubmitEvent $event) use ($flied) {

            $data = $event->getData();
            if (empty($data['slug'])) {
                $data['slug'] = strtolower($this->slugger->slug($data[$flied]));
            }
            // dd($event->getData());
            $event->setData($data);
        };
    }


    public function timeStamps(): callable
    {
        return function (PostSubmitEvent $event) {

            $data = $event->getData();

            $data->setUpdatedAt(new \DateTimeImmutable());

            if (!$data->getId()) {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
}
