<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations = {
 *          "get"={
 *              "security"="is_granted('ROLE_CM')",
 *              "security_message"="VOUS N'AVEZ PAS ACCES À CE SERVICE"
 *        },
 *  },
 * itemOperations = {
 *      "get"={
 *          "security"="is_granted('view', object) and object",
 *          "security_message"="VOUS N'AVEZ PAS ACCES À CE SERVICE"
 *        },
 *      "put"={
 *          "security"="is_granted('edit', object) and object",
 *          "security_message"="VOUS N'AVEZ PAS ACCES À CE SERVICE"
 * },
 * })
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 */
class Formateur extends User
{
   
}
