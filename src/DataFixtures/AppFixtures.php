<?php

namespace App\DataFixtures;

use App\Entity\MarketingSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Marketing sources
        $marketingSources = [
            'facebook-ads' => 'Facebook Ads',
            'google-ads' => 'Google Ads',
        ];

        foreach ($marketingSources as $slug => $label) {
            $marketingSource = new MarketingSource();
            $marketingSource->setSlug($slug)
                ->setName($label);

            $manager->persist($marketingSource);
        }
        $manager->flush();
    }
}
