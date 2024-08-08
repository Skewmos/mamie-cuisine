<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class ContactDto {

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    public string $subject = '';

    #[Assert\NotBlank]
    public string $message = '';

    #[Assert\NotBlank]
    public string $service = '';

}