<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture {

	private array $trickGroups = [
		'Grabs'                 => [
			'name'        => 'Grabs',
			'description' => 'Snowboard Grabs a little different from your normal trick. Rather than being a trick on their own, they are something that you can add to any other trick that you are doing to add style and change the look of the trick. Since there are a lot of different times and places that you can do a grab there are a lot of different names for tricks that involve grabs.'
		],
		'Straight airs'         => [
			'name'        => 'Straight airs',
			'description' => 'on ground'
		],
		'Spins'                 => [
			'name'        => 'Spins',
			'description' => 'Spins are typically performed in 180° increments due to the nature of the obstacles on which they are performed. Even in cases where spins are performed on unconventional obstacles, the rotation is regarded as the nearest increment of 180°, and can be identified by the direction of approach and landing (regular and switch). A spin attempted from a jump to a rail is the only time a spin can be referred to in a 90-degree increment, examples: 270 (between a 180 and 360-degree spin) or 450 (between a 360 and 540-degree spin). These spins can be frontside, backside, cab, or switch-backside just like any other spins. In April 2015 British snowboarder and Winter Olympic medallist Billy Morgan demonstrated the world\'s first quadruple cork 1800, the biggest spin ever'
		],
		'Slides'                => [
			'name'        => 'Slides',
			'description' => 'Slides are tricks performed along the surface of obstacles like handrails and funboxes. In skateboarding, slides are distinguished from grinds because some tricks are performed by sliding on the surface of the skateboard, and others are performed by grinding on the trucks of the skateboard. However, because snowboards don\'t have trucks, the term grind doesn\'t apply to these types of maneuvers. They can still be called grinds. '
		],
		'Flip'                  => [
			'name'        => 'Flip',
			'description' => ''
		],
		'Inverted hand plants'  => [
			'name'        => 'Inverted hand plants',
			'description' => ''
		],
		'Stalls'                => [
			'name'        => 'Stalls',
			'description' => 'Stalls in snowboarding are derived from similar tricks in skateboarding, and are typically performed in halfpipes or on similar obstacles. Variations have been adapted as snowboards do not have trucks and wheels. '
		],
		'Tweaks and variations' => [
			'name'        => 'Tweaks and variations',
			'description' => ''
		]
	];

	private array $tricks = [
		[
			'name'        => 'Ollie',
			'description' => 'A trick in which the snowboarder springs off the tail of the board and into the air.[1]',
			'trickGroups' => 'Straight airs'
		],
		[
			'name'        => 'Stalefish',
			'description' => 'A trick in which the snowboarder springs off the tail of the board and into the air.[1]',
			'trickGroups' => 'Grabs'
		],
		[
			'name'        => 'Boardslide',
			'description' => 'A slide performed where the riders leading foot passes over the rail on approach, with their snowboard traveling perpendicular along the rail or other obstacle.[1] When performing a frontside boardslide, the snowboarder is facing uphill. When performing a backside boardslide, a snowboarder is facing downhill. This is often confusing to new riders learning the trick because with a frontside boardslide you are moving backward and with a backside boardslide you are moving forward.',
			'trickGroups' => 'Slides'
		]
	];

	private EntityManagerInterface $entityManager;

	public function __construct( EntityManagerInterface $entityManager ) {
		$this->entityManager = $entityManager;
	}

	public function load( ObjectManager $manager ) {

		$this->addTricks( $this->tricks, $manager );

	}

	private function addTricks( $tricks, $manager ) {
		foreach ( $tricks as $trick ) {
			$newTrickGroup    = $this->trickGroups[ $trick['trickGroups'] ];
			$entityTrickGroup = new TrickGroup( $newTrickGroup['name'], $newTrickGroup['description'] );
			$entityMedia      = new Media( $trick['name'], $trick['name'], strtolower( $trick['name'] ) . '.jpg', 'image/jpeg' );
			$newTrick         = new Trick(
				$trick['name'],
				$trick['description'],
				$entityTrickGroup,
				new ArrayCollection( [ $entityMedia ] ),
			);
			$manager->persist( $entityTrickGroup );
			$manager->persist( $entityMedia );
			$manager->persist( $newTrick );
			$manager->flush();
		}
	}

}
