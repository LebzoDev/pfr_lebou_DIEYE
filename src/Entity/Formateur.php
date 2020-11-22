<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations = {
 *  },
 * itemOperations = {
 *      "get"={
 *          "security"="is_granted('view', object) and object",
 *          "security_message"="Bakhoul"
 *        },
 *      "put"={
 *          "security"="is_granted('view', object) and object",
 *          "security_message"="Boooy boy beugouma liii"
 * },
 * })
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur extends User
{
   
}
