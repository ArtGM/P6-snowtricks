<?php


namespace App\Domain\Comment;


use Symfony\Component\Validator\Constraints as Assert;

class CommentDTO {

	/**
	 * @var string
	 * @Assert\NotBlank
	 * @Assert\Length (
	 *      min = 5,
	 *      minMessage = "Your message must be at least {{ limit }} characters long",
	 * )
	 */
	public string $content;

}
