<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture {
	public function load( ObjectManager $manager ) {

		$trickGroups = [
			[
				'name'        => 'Grabs',
				'description' => 'Snowboard Grabs a little different from your normal trick. Rather than being a trick on their own, they are something that you can add to any other trick that you are doing to add style and change the look of the trick. Since there are a lot of different times and places that you can do a grab there are a lot of different names for tricks that involve grabs.'
			],
			[
				'name'        => 'Straight airs',
				'description' => 'on ground'
			],
			[
				'name'        => 'Spins',
				'description' => 'Spins are typically performed in 180° increments due to the nature of the obstacles on which they are performed. Even in cases where spins are performed on unconventional obstacles, the rotation is regarded as the nearest increment of 180°, and can be identified by the direction of approach and landing (regular and switch). A spin attempted from a jump to a rail is the only time a spin can be referred to in a 90-degree increment, examples: 270 (between a 180 and 360-degree spin) or 450 (between a 360 and 540-degree spin). These spins can be frontside, backside, cab, or switch-backside just like any other spins. In April 2015 British snowboarder and Winter Olympic medallist Billy Morgan demonstrated the world\'s first quadruple cork 1800, the biggest spin ever'
			],
			[
				'name'        => 'Slides',
				'description' => 'Slides are tricks performed along the surface of obstacles like handrails and funboxes. In skateboarding, slides are distinguished from grinds because some tricks are performed by sliding on the surface of the skateboard, and others are performed by grinding on the trucks of the skateboard. However, because snowboards don\'t have trucks, the term grind doesn\'t apply to these types of maneuvers. They can still be called grinds. '
			]
		];
		foreach ( $trickGroups as $trickGroup ) {

			$newTrickGroup = new TrickGroup(
				$trickGroup['name'],
				$trickGroup['description']
			);

			$manager->persist( $newTrickGroup );
			$manager->flush();
		}


	}
}
