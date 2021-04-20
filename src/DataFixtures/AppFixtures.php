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
		'Flips'                 => [
			'name'        => 'Flips',
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
		],
		[
			'name'        => 'Backflip',
			'description' => 'Flipping backwards (like a standing backflip) off of a jump. A Layout Backflip is a variation of a regular backflip, but you fully extend your body for the first half of the rotation. This can be done barrel, or more in the wildcat style of backflip.',
			'trickGroups' => 'Flips'
		],
		[
			'name'        => 'Handplant',
			'description' => 'A 180° degree handplant in which the rear hand is planted on the lip of the wall and the rotation is frontside.',
			'trickGroups' => 'Inverted hand plants'
		],
		[
			'name'        => 'Tail-stall',
			'description' => 'The opposite of a nose-stall, this trick involves stalling on an obstacle with the tail of the snowboard. Often performed by approaching an obstacle fakie or by doing a 180 after approaching the feature normally,',
			'trickGroups' => 'Stalls'
		],
		[
			'name'        => 'Shifty',
			'description' => 'An aerial trick in which a snowboarder twists their body, rotating their board 90° and then returning it to its original position before landing. This trick can be performed frontside or backside, and also in variation with other tricks and spins.',
			'trickGroups' => 'Tweaks and variations'
		],
		[
			'name'        => 'Alley-oop',
			'description' => 'An alley-oop is a spin performed in a halfpipe or quarterpipe in which the spin is rotated in the opposite direction of the air. For example, performing a frontside rotation on the backside wall of a halfpipe, or spinning clockwise while traveling right-to-left through the air on a quarterpipe would mean the spin was alley-oop. ',
			'trickGroups' => 'Spins'
		],
		[
			'name'        => 'Method',
			'description' => 'A fundamental trick performed by bending the knees to lift the board behind the rider\'s back, and grabbing the heel edge of the snowboard with the leading hand. Variations on the method include :
				Power method, cross bone, or Palmer method:  Performed by grabbing the heel edge with the leading hand, and tucking up the board while kicking out the rear foot in such a way that the base of the board is facing forward. Derived from the snowboarder Chris Roach of Grass Valley, CA. Other notable riders who popularized this air include snowboarders Jamie Lynn, Shaun Palmer, Terry Kidwell, and skateboarders Steve Caballero and Christian Hosoi.
				Suitcase: A method in which the knees are bent so that the front hand is able to grab the toe edge and hold the board "like a suitcase."',
			'trickGroups' => 'Grabs'
		],
		[
			'name'        => 'Cork',
			'description' => 'Spins are corked or corkscrew when the axis of the spin allows for the snowboarder to be oriented sideways or upside-down in the air, typically without becoming completely inverted (though the head and shoulders should drop below the relative position of the board). A Double-Cork refers to a rotation in which a snowboarder inverts or orients themselves sideways at two distinct times during an aerial rotation. David Benedek is the originator of the Double-Cork in the Half-pipe, but the Double-Cork is also a very common trick in Big-Air competitions. Shaun White is known for making this trick famous in the half-pipe. Several snowboarders have recently extended the limits of technical snowboarding by performing triple-cork variations, Torstein Horgmo being the first to land one in competition. Mark McMorris originated Backside Triple-Cork 1440\'s in 2011. In April 2015 British snowboarder and Winter Olympic medallist Billy Morgan demonstrated the world\'s first quadruple cork 1800.',
			'trickGroups' => 'Flips'
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
			$fileName         = strtolower( $trick['name'] ) . '.jpg';
			copy( dirname( __FILE__, 3 ) . '/public/images/' . $fileName, dirname( __FILE__, 3 ) . '/public/uploads/' . $fileName );
			$entityMedia = new Media( $trick['name'], $trick['name'], $fileName, 'image/jpeg' );
			$newTrick    = new Trick(
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
