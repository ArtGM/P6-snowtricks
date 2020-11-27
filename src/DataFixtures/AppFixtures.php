<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture {
	public function load( ObjectManager $manager ) {
		$trickGroup = new TrickGroup(
			'Grabs',
			'Snowboard Grabs a little different from your normal trick. Rather than being a trick on their own, they are something that you can add to any other trick that you are doing to add style and change the look of the trick. Since there are a lot of different times and places that you can do a grab there are a lot of different names for tricks that involve grabs.'
		);

		$manager->persist( $trickGroup );
		$manager->flush();
	}
}
