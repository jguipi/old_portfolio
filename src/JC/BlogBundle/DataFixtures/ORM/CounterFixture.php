<?php
// src/Blogger/BlogBundle/DataFixtures/ORM/CommentFixtures.php

namespace Blogger\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JC\BlogBundle\Entity\Counter;

class CounterFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

    }

    /**
     * Permet de definir l'ordre dexecution des fixture celui-ci sera le deuxieme
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }


}