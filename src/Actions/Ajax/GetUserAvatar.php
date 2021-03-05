<?php


namespace App\Actions\Ajax;

use App\Domain\Media\Handlers\MediaHandler;
use App\Domain\Media\ImageFormType;
use App\Repository\MediaRepository;
use App\Responders\JsonResponders;
use App\Responders\ViewResponders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewUserAvatar
 * @package App\Actions\Ajax
 * @Route("/user/profile/get-avatar/{id}", name="get-avatar")
 */
class GetUserAvatar {

}